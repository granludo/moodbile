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
    //AÑADIR ESTO Y USAR LA FUNCION DE LOS ERRORES
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

Moodbile.webdb.getDataByOpts = function(tableName, opts, callback) {
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
            tx.executeSql("SELECT data FROM "+tableName+" WHERE "+optsSQL+"", valuesToGet, callback, Moodbile.webdb.onError);
        });
    }
}

//Other functions
Moodbile.webdb.isEmpty = function(tableName, opts, callbacks) {
    if(Moodbile.webdb.isCompatible()) {
        var callback = function(tx, rs) {
            if(rs.rows.length === 0) {
                callbacks.t();
            } else {
                callbacks.f();
            }
        }
        Moodbile.webdb.getDataByOpts(tableName, opts, callback); //Cambiar por get data by opts
    } else {
        callbacks.t();
    }
}

//Loading Implementation
Moodbile.behaviors.webdb = function (context) {
    Moodbile.webdb.open();
    
    //Creamos una tablas
    Moodbile.webdb.createTable('requestData', {
        'name'             : 'TEXT',
        'wsfunction'       : 'TEXT',
        'userid'           : 'INTEGER',
        'context'          : 'TEXT',
        'data'             : 'TEXT',
        'lastmodification' : 'DATETIME' 
    });
}