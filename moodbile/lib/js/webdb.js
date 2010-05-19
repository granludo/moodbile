/**
   Author: Imanol Urra Ruiz
   Based on: HTML5Rock TODO list sample (http://www.html5rocks.com/samples/webdatabase/todo/)
   Description of this implementation: Implement HTML5 Web DB in Moodbile for data storage
*/

Moodbile.webdb = {};
Moodbile.webdb.db = null;

//The database needs to be opened before it can be accessed. You need to define the name, version, description and the size of the database.
Moodbile.webdb.open = function() {
    if(Moodbile.webdb.isCompatible()) {
        var dbSize = 5 * 1024 * 1024; // 5MB
        Moodbile.webdb.db = openDatabase("Moodbile", "1.0", "Moodbile Web DB", dbSize);
    }
}

Moodbile.webdb.onError = function(tx, e) {
    alert('Something unexpected happened: ' + e.message );
}

Moodbile.webdb.onSuccess = function(tx, r) {
    // re-render all the data
    //Moodbile.webdb.getAllTodoItems(tx, r);
}

Moodbile.webdb.isCompatible = function(){
    if (typeof openDatabase != "undefined"){
        return true;
    } else {
        return false;
    }
}

//Creating table
//Estructura de datos field = {'celda':'tipo de campo (sentencia SQL)'}
Moodbile.webdb.createTable = function(tableName, fields) {
    if(Moodbile.webdb.isCompatible()) {
        var sql = "";
        for(field in fields) {
            sql += ', '+field+' '+fields[field];
        }
        //alert(sql);
        Moodbile.webdb.db.transaction(function(tx) {
            tx.executeSql('CREATE TABLE IF NOT EXISTS '+ tableName +'(ID INTEGER PRIMARY KEY ASC'+sql+')', [],Moodbile.webdb.onSuccess,Moodbile.webdb.onError);
        });
    }
}

//Adding data to a table
//Estructura de datos field = {'celda':'valor'}
Moodbile.webdb.addValues = function(tableName, values) {
    if(Moodbile.webdb.isCompatible()) {
        //obtenemos las celdas
        var fieldsSQL = "";
        var intSQL = ""; //TODO: Cambiar nombre
        var valuesToInsert = [];
    
        for(field in values) {
            if(fieldsSQL == "") {
                fieldsSQL += ''+field+'';
                intSQL += '?';
            } else {
                fieldsSQL += ', '+field+'';
                intSQL += ',?';
            }
        
            valuesToInsert.push(values[field]);
        }
    
        Moodbile.webdb.db.transaction(function(tx){
            tx.executeSql("INSERT INTO "+tableName+"("+fieldsSQL+") VALUES ("+intSQL+")", valuesToInsert, Moodbile.webdb.onSuccess, Moodbile.webdb.onError);
        });
    }
}

//Deleting data from a table
Moodbile.webdb.deleteAllValues = function(tableName) {
    if(Moodbile.webdb.isCompatible()) {
        Moodbile.webdb.db.transaction(function(tx) {
            tx.executeSql("DELETE FROM "+tableName+"", [], Moodbile.webdb.onSuccess, Moodbile.webdb.onError);
        });
    }
}

Moodbile.webdb.deleteValueByID = function(tableName, id) {
    if(Moodbile.webdb.isCompatible()) {
        Moodbile.webdb.db.transaction(function(tx) {
            tx.executeSql("DELETE FROM "+tableName+" WHERE ID=?", [id], Moodbile.webdb.onSuccess, Moodbile.webdb.onError);
        });
    }
}

Moodbile.webdb.deleteValues = function(tableName, opts) {
    if(Moodbile.webdb.isCompatible()) {
        var valuesToDelete = [];
        var optsSQL = "";
        
        for(field in opts) {
            if(optsSQL == "") {
                optsSQL += ''+field+'=?';
            } else {
                optsSQL += ' AND '+field+'=?';
            }
        
            valuesToDelete.push(opts[field]);
        }
    
        Moodbile.webdb.db.transaction(function(tx) {
            tx.executeSql("DELETE FROM "+tableName+" WHERE "+optsSQL+"", valuesToDelete, Moodbile.webdb.onSuccess, Moodbile.webdb.onError);
        });
    }
}

//Selecting data from a table
Moodbile.webdb.getAllValues = function(tableName, callback) {
    if(Moodbile.webdb.isCompatible()) {
        Moodbile.webdb.db.transaction(function(tx) {
            tx.executeSql("SELECT * FROM "+tableName+"", [], callback, Moodbile.webdb.onError);
        });
    }
}

Moodbile.webdb.getDateByOpts = function(tableName, opts, callback) {
    if(Moodbile.webdb.isCompatible()) {
        var valuesToGet = [];
        var optsSQL = "";
        
        for(field in opts) {
            if(optsSQL == "") {
                optsSQL += ''+field+'=?';
            } else {
                optsSQL += ' AND '+field+'=?';
            }
        
            valuesToGet.push(opts[field]);
        }
    
        Moodbile.webdb.db.transaction(function(tx) {
            tx.executeSql("SELECT date FROM "+tableName+" WHERE "+optsSQL+"", valuesToGet, callback, Moodbile.webdb.onError);
        });
    }
}

Moodbile.webdb.getTemplate = function(templateName, callback) {
    if(Moodbile.webdb.isCompatible()) {
        Moodbile.webdb.db.transaction(function(tx) {
            tx.executeSql("SELECT * FROM templates", [], callback, Moodbile.webdb.onError);
        });
    } else {
        callback();
    }
}

Moodbile.webdb.getRequestJSONbyUserID = function(tableName, requestName, userid, callback) {
    if(Moodbile.webdb.isCompatible()) {
        Moodbile.webdb.db.transaction(function(tx) {
            tx.executeSql("SELECT * FROM "+tableName+" WHERE requestName=? AND userid=?", [requestName, userid], callback, Moodbile.webdb.onError);
        });
    }
}

//Other functions
Moodbile.webdb.isEmpty = function(tableName, trueCallback, falseCallback) {
    if(Moodbile.webdb.isCompatible()) {
        Moodbile.DBisEmpty = false;
        var callback = function(tx, rs) {
            if(rs.rows.length === 0) {
                trueCallback();
            } else {
                falseCallback();
            }
        }
        Moodbile.webdb.getAllValues(tableName, callback);
    } else {
        trueCallback();
    }
}

Moodbile.webdb.needReload = function(tableName, opts, callback) {
    if(Moodbile.webdb.isCompatible()) {
        var checkdb = setInterval(function() {
            if(Moodbile.webdb.db != null) {
                clearInterval(checkdb);        
                var getValueCallback = function(tx, rs) {
                    if(rs.rows.length != 0) {
                        var timeToCompare = Moodbile.ExpireTimes[tableName] * 60 * 1000; //minutos a milisegundos
                        var dateToCheck = rs.rows.item(0).date;
                        var actualDate = Date.parse(Moodbile.actualDate);
                        
                        if(actualDate-dateToCheck <= timeToCompare) {
                            Moodbile.needReload = false;
                        } else {
                            Moodbile.needReload = true;
                        }
                    } else {
                        Moodbile.needReload = true;
                    }
            
                    callback();
                }
                
                Moodbile.webdb.getDateByOpts(tableName, opts, getValueCallback);
            }
        }, Moodbile.intervalDelay);
    } else {
        Moodbile.needReload = true;
        callback();
    }
}

//Loading Implementation
Moodbile.behaviors.webdb = function (context) {
    Moodbile.webdb.open();
    
    //Creamos una tablas
    Moodbile.webdb.createTable('requestJSON', {'requestName':'TEXT','JSON':'TEXT', 'date':'DATETIME', 'userid':'INTEGER' });
    Moodbile.webdb.createTable('templates', {'templateName':'TEXT','HTML':'TEXT', 'modDate':'DATETIME'});
}