/*
Como funcionan las peticiones JSON en Moodbile:
    1. Se inicializa la variable donde se guardara la info. y se meten las opciones en una cola
    2. Se inicializa un intervalo la cual no parara hasta que las siguientes comprobaciones se cumplan:
        2.1. La peticion actual haya terminado (currentJson = null) y... <- Imprescindible?????
        2.2. La peticion anterior haya terminado
    3. Una vez cumplidas condiciones, se realiza la peticion y una vez recibida la info:
        3.1. Se guardara la info recibida en la variable correspondiente
        3.2. Se ejecutara la funcion callbackFunction para procesar la info.
        3.3. Se indica que la peticion actual ha terminado <- Indispensable??????
        3.4. Se indica que la peticion de la cola realizada ha terminado.
*/
Moodbile.json = function(context, op, callbackFunction, cache) {
    var context = context || document;
    
    //Prevencion, hasta que no se cree la variable user, no se haran las peticiones
    var checkUserVariable = setInterval(function() {
        if(Moodbile.user != null) {
            clearInterval(checkUserVariable);
            //Definimos null, la variable donde se almacenara la peticion.
            Moodbile.requestJson[op.wsfunction] = null;
    
            //Añadimos user y password a la opcion de peticion
            var cookie = $.readCookie('Moodbile');
            if (cookie) {
                op.wsusername = $.evalJSON(cookie).user;
                op.wspassword = $.evalJSON(cookie).pass;
            }
    
            //Añadimos peticion en cola.
            var currentQueueKey = Moodbile.queueJson.length;
            Moodbile.queueJson[currentQueueKey] = op;
            
            var reloadCallback = function() {
                //alert(Moodbile.dbCache.toString());
                //Miramos si lo va ha meter en cache o no.
                if(Moodbile.needReload) {
                    Moodbile.ajaxJson(currentQueueKey, callbackFunction, cache);
                } else {
                    var requestName = Moodbile.queueJson[currentQueueKey].wsfunction;
                    var userid = Moodbile.user.id;
                    var loadData = function(tx, rs) {
                        for (var i=0; i < rs.rows.length; i++) {
                            Moodbile.requestJson[requestName] = $.evalJSON(rs.rows.item(i).JSON);
                            callbackFunction($.evalJSON(rs.rows.item(i).JSON));
                            Moodbile.queueJson[currentQueueKey] = null;
                            Moodbile.currentJson = null;
                        }
                    }
                }
                
                Moodbile.webdb.getRequestJSONbyUserID('requestJSON', requestName, userid, loadData);
            }
            
            var opts = {'userid': Moodbile.user.id, 'requestName': Moodbile.queueJson[currentQueueKey].wsfunction};
            Moodbile.webdb.needReload('requestJSON', opts, reloadCallback);
        }
    }, Moodbile.intervalDelay);
}

Moodbile.ajaxJson = function(currentQueueKey, callbackFunction, cache) {
    var initRequest = setInterval(function() {
        if(currentQueueKey == 0 || Moodbile.queueJson[currentQueueKey-1] == null) {
            Moodbile.currentJson = $.ajax({
                type: "POST",
                cache: true,
                url: Moodbile.wsurl,
                data: ({request: encodeURIComponent($.toJSON(Moodbile.queueJson[currentQueueKey]))}),
                dataType: 'jsonp',
                beforeSend: function(){
                    Moodbile.showMask(true);
                },
                success: function(json) {
                    json = json[json.length-1];
                    
                    if(json != null){
                        //TODO: Añadir un if para comprobar si hay o no respuesta
                        callbackFunction(json);

                        if(cache){
                            Moodbile.requestJson[Moodbile.queueJson[currentQueueKey].wsfunction] = json;
                            
                            Moodbile.webdb.deleteValues('requestJSON', {'userid': Moodbile.user.id, 'requestName': Moodbile.queueJson[currentQueueKey].wsfunction});
                            Moodbile.webdb.addValues('requestJSON', {'requestName': Moodbile.queueJson[currentQueueKey].wsfunction, 'JSON': $.toJSON(json), 'date': Date.parse(Moodbile.actualDate), 'userid': Moodbile.user.id });
                        }
                    
                    } else {
                        Moodbile.addAlert('error','RequestError');
                    }
                    
                    //alert(currentQueueKey);
                    //alert(Moodbile.queueJson.length);
                    if(currentQueueKey === Moodbile.queueJson.length-1) {
                        Moodbile.showMask(false);
                    }
                    
                    Moodbile.queueJson[currentQueueKey] = null;
                    Moodbile.currentJson = null;
                },
                error: function() {
                    alert('oops!');
                } 
            });
            clearInterval(initRequest);
        }
    }, Moodbile.intervalDelay);
}