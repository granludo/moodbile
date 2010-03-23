//Funcion que insertara el HTML en la parte indicada
Moodbile.loadTemplate = function(templateName, selector, callback) {        
    var reloadCallback = function() {
        if(Moodbile.needReload){
            if(Moodbile.templatesHTML[templateName] == null) {
                var toLoad = Moodbile.templatesUrl[templateName];
        
                var segureInterval = setInterval(function() {
                    if(Moodbile.currentJson == null) {
                        clearInterval(segureInterval);
                        Moodbile.currentJson = $.ajax({
                            type: "GET",
                            cache: true,
                            url: toLoad,
                            dataType: "html",
                            success: function(data) {
                                Moodbile.templatesHTML[templateName] = data;
                        
                                Moodbile.webdb.deleteValues('templatesHTML', {'templateName': templateName});
                                Moodbile.webdb.addValues('templatesHTML', {'templateName': templateName, 'HTML': data, 'date': Date.parse(Moodbile.actualDate)});
                        
                                $(selector).append(data);
                                callback();
                    
                                Moodbile.currentJson = null;
                            },
                            error: function() {
                                alert('oops!');
                            }
                        }); 
                    }
                }, Moodbile.intervalDelay);
            } else {
                $(selector).append(Moodbile.templatesHTML[templateName]);
                callback();
            }
        } else {
            var getValueCallback = function(tx, rs) {
                for (var i=0; i < rs.rows.length; i++) {
                    Moodbile.templatesHTML[templateName] = rs.rows.item(i).HTML;
                    $(selector).append(rs.rows.item(i).HTML);
                    callback();
                }
            }
        }
                
        Moodbile.webdb.getTemplate('templatesHTML', templateName, getValueCallback);
    }
            
    var opts = {'templateName': templateName};
    Moodbile.webdb.needReload('templatesHTML', opts, reloadCallback);
}