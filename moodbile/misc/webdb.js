/**
   Author: Imanol Urra Ruiz
   Based on: HTML5Rock TODO list sample (http://www.Moodbile.com/samples/webdatabase/todo/)
   Description of this implementation: Implement HTML5 Web DB in Moodbile for data storage
*/

Moodbile.webdb = {};

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
    }
}

//Creating table
//Estructura de datos field = {'celda':'tipo de campo (sentencia SQL)'}
Moodbile.webdb.createTable = function(tableName, fields) {
    if(Moodbile.webdb.isCompatible()) {
        var sql = "";
        for(field in fields) {
            sql += ','+field+' '+fields[field];
        }
    
        Moodbile.webdb.db.transaction(function(tx) {
            tx.executeSql("CREATE TABLE IF NOT EXISTS "+tableName+"(ID INTEGER PRIMARY KEY ASC"+sql+")", [],Moodbile.webdb.onSuccess,Moodbile.webdb.onError);
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
Moodbile.webdb.deleteValue = function(tableName, id) {
    if(Moodbile.webdb.isCompatible()) {
        Moodbile.webdb.db.transaction(function(tx) {
            tx.executeSql("DELETE FROM "+tableName+" WHERE ID=?", [id], Moodbile.webdb.onSuccess, Moodbile.webdb.onError);
        });
    }
}

//Selecting data from a table
Moodbile.webdb.getAllValues = function(tableName, renderFunc) {
    Moodbile.webdb.db.transaction(function(tx) {
        tx.executeSql("SELECT "+tableName+" FROM todo", [], renderFunc, Moodbile.webdb.onError);
  });
}

//Loading Implementation
Moodbile.behaviorsPatterns.webdb = function (context) {
    Moodbile.webdb.open();
    Moodbile.webdb.createTable('test', {'celda1':'TEXT', 'celda2':'DATETIME'});
    
    var date = new Date();
    Moodbile.webdb.addValues('test', {'celda1':'testing', 'celda2': Date.parse(date)});
    Moodbile.webdb.deleteValue('test', 3);
}