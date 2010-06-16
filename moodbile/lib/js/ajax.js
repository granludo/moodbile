//Funcion encargada de cargar todos los templates desde un inicio
//Opts = {}
//Antiguamente en la function opts, iban las variables que se enviaran
//Ahora nos centramos en la basae de datos y el ajax es algo secundario, por lo tanto se formara un objeto que contega la informacion de la DB (en la cual ira incluido las opciones de la pecicion)

//EJEMPLO de opts {name: null, wsfunction:null, context:{[userids]:null}, wsusername: null, wspassword = null }; 
Moodbile.json = function(opts) {
    var context = context || document;
    var requestOpts = null, dbOpts= null; callbacks = {}, getValueCallback = null;
    
    if (opts) {
        requestOpts = {
            'name'       : opts.name,
            'userid'     : Moodbile.user.id,
            'wsfunction' : opts.wsfunction,
            'context'    : opts.context,
            'callback'   : opts.callback
        };
        
        dbOpts = {'name': requestOpts.name, 'userid': requestOpts.userid, 'context' : $.toJSON(requestOpts.context)};
        
        //callback.t(true) execute when selected content is empty
        callbacks.t = function() { Moodbile.ajax(requestOpts); }
        
        //callback.t(true) execute when selected content is in DB
        callbacks.f = function(){
            getValueCallback = function(tx, rs) {
                Moodbile.showMask(true);
                
                for (var i=0; i < rs.rows.length; i++) {
                    requestOpts.callback($.evalJSON(rs.rows.item(i).data));
                }
                
                Moodbile.showMask(false);
            }
            
            Moodbile.webdb.getDataByOpts('requestData', dbOpts, getValueCallback);
        }
        
        Moodbile.webdb.isEmpty('requestData', dbOpts, callbacks);
    }
}

Moodbile.ajax = function (requestOpts) {
    var cookie = null, currentRequest = null, toRequest = {}, segureInterval = null;
    
    if(requestOpts && Moodbile.isLoged()) {
        cookie = $.readCookie('Moodbile');
    
        //FIFO with json petitions
        Moodbile.queueJson.push(requestOpts);
        
        segureInterval = setInterval(function() {
            if(Moodbile.currentJson == null) {
                if(Moodbile.queueJson.length-1 == 0) {
                    clearInterval(segureInterval);
                }
            
                currentRequest = Moodbile.queueJson[Moodbile.queueJson.length-1];
                
                toRequest.wsfunction = currentRequest.wsfunction;
                toRequest.wsusername = $.evalJSON(cookie).user;
                toRequest.wspassword = $.evalJSON(cookie).pass;
                 
                for (varname in currentRequest.context) {
                    toRequest[varname] = currentRequest.context[varname];
                }
                
                //alert($.toJSON(toRequest));
                
                Moodbile.currentJson = $.ajax({
                    type: "POST",
                    cache: true,
                    url: Moodbile.wsurl,
                    data: ({request: encodeURIComponent($.toJSON(toRequest))}),
                    dataType: 'jsonp',
                    beforeSend: function() {
                        Moodbile.showMask(true);
                    },
                    success: function(data) {
                        if(data != null){
                            currentRequest.callback(data);
                                
                            Moodbile.webdb.deleteValues('requestData', {'name': currentRequest.name, 'userid': currentRequest.userid, 'context': $.toJSON(currentRequest.context)});
                            Moodbile.webdb.addValues('requestData', {
                                'name'    : currentRequest.name,
                                'userid'  : currentRequest.userid,
                                'context' : $.toJSON(currentRequest.context),
                                'data'    : $.toJSON(data),
                                'lastmodification' : Date.parse(Moodbile.actualDate)
                            });
                    
                        } else {
                            Moodbile.addAlert('error','RequestError');
                        }
                        
                        if(Moodbile.queueJson.length-1 === 0) {
                                Moodbile.showMask(false);
                        }
                        
                        Moodbile.currentJson = null;
                        Moodbile.queueJson.pop();
                    }
                }); 
            }
        }, Moodbile.intervalDelay);
    }
}