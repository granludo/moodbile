//Funcion encargada de cargar todos los templates desde un inicio
//Opts = {}
//Antiguamente en la function opts, iban las variables que se enviaran
//Ahora nos centramos en la basae de datos y el ajax es algo secundario, por lo tanto se formara un objeto que contega la informacion de la DB (en la cual ira incluido las opciones de la pecicion)

//EJEMPLO de opts {name: null, wsfunction:null, context:{[userids]:null}, wsusername: null, wspassword = null }; 
Moodbile.currentJson = null;
Moodbile.queueJson = [];

Moodbile.json = function(opts) {
    var context = context || document;
    var requestOpts = null, dbOpts= null; callbacks = {}, getValueCallback = null;
    
    if (opts) {
        requestOpts = {
            'name'       : opts.name,
            'userid'     : Moodbile.user.id,
            'wsfunction' : opts.wsfunction,
            'context'    : opts.context,
            'callback'   : opts.callback,
            'cache'      : opts.cache
        };
        
        dbOpts = {'name': requestOpts.name, 'userid': requestOpts.userid, 'context' : $.toJSON(requestOpts.context)};
        
        //callback.t(true) execute when selected content is empty
        callbacks.t = function() { Moodbile.ajax(requestOpts); }
        
        //callback.t(true) execute when selected content is in DB
        callbacks.f = function(){
            getValueCallback = function(tx, rs) {
                var dataDB = $.evalJSON(rs.rows.item(0).data);
                
                //if (Moodbile.online) {
                    requestOpts.callback(dataDB);
                    requestOpts.callback = function (requestData) {
                        if ($.toJSON(dataDB) != $.toJSON(requestData)) {
                            window.location.reload();
                        }
                    }
                
                    Moodbile.ajax(requestOpts);
                //}
            }
            
            Moodbile.webdb.getDataByOpts('requestData', dbOpts, getValueCallback);
        }
        
        Moodbile.webdb.isEmpty('requestData', dbOpts, callbacks);
    }
}

Moodbile.ajax = function (requestOpts) {
    var cookie = null, currentRequest = null, toRequest = {}, segureInterval = null;
    
    if(requestOpts) {
        Moodbile.mask.show();
        
        cookie = $.readCookie('Moodbile');
        
        if(requestOpts.cache == null) { requestOpts.cache = true; }
    
        //FIFO with json petitions
        Moodbile.queueJson.push(requestOpts);
        
        segureInterval = setInterval(function() {
            if(Moodbile.currentJson == null) {
                if(Moodbile.queueJson.length-1 == 0) {
                    clearInterval(segureInterval);
                }
            
                currentRequest = Moodbile.queueJson[Moodbile.queueJson.length-1];
                
                toRequest.wsfunction = currentRequest.wsfunction;
            
                if ( Moodbile.isLoged() ) {
                    toRequest.wsusername = $.evalJSON(cookie).user;
                    toRequest.wspassword = $.evalJSON(cookie).pass;
                } else {
                    toRequest.wsusername = currentRequest.wsusername;
                    toRequest.wspassword = currentRequest.wspassword;
                }
                 
                for (varname in currentRequest.context) {
                    toRequest[varname] = currentRequest.context[varname];
                }
                
                Moodbile.currentJson = $.ajax({
                    type       : 'GET',
                    url        : Moodbile.wsurl,
                    data       : ({request: encodeURIComponent($.toJSON(toRequest))}),
                    dataType   : 'jsonp',
                    success    : function(data) {
                        if(data != null){
                            currentRequest.callback(data);
                            
                            if(currentRequest.cache) {
                                Moodbile.webdb.deleteValues('requestData', {
                                    'name': currentRequest.name,
                                    'userid': currentRequest.userid,
                                    'context': $.toJSON(currentRequest.context)
                                });
                                Moodbile.webdb.addValues('requestData', {
                                    'name'             : currentRequest.name,
                                    'wsfunction'       : currentRequest.wsfunction,
                                    'userid'           : currentRequest.userid,
                                    'context'          : $.toJSON(currentRequest.context),
                                    'data'             : $.toJSON(data)
                                });
                            }
                        } else {
                            Moodbile.addAlert('error','RequestError');
                        }
                        
                        if(Moodbile.queueJson.length-1 == 0) {
                            Moodbile.mask.hide();
                        }
                        
                        Moodbile.currentJson = null;
                        Moodbile.queueJson.pop();
                    },
                    error      : function(XHR, textStatus, errorThrown) {
                        alert("ERREUR: " + textStatus +"\nERREUR: " + errorThrown);
                    }
                }); 
            }
        }, Moodbile.intervalDelay);
    }
}