Moodbile.behaviors.templatesDOM = function(context) {
    var context = context || document;
            
    $('#container').append('<div id="templates"/>');
    $('#templates').hide();
}

//Funcion encargada de cargar todos los templates desde un inicio
Moodbile.behaviors.loadTemplates = function() {
    var context = context || document;
    var templateFIFO = [];
    
    for(templateName in Moodbile.templatesUrl) {
        templateFIFO.push(templateName);
    }
    
    var trueCallback = function(){
        var segureInterval = setInterval(function() {
            if(Moodbile.currentJson == null) {
                if(templateFIFO.length-1 == 0) {
                    clearInterval(segureInterval);
                }
            
                var currentLoad = templateFIFO[templateFIFO.length-1];
            
                Moodbile.currentJson = $.ajax({
                    type: "GET",
                    cache: true,
                    url: Moodbile.templatesUrl[currentLoad],
                    dataType: "html",
                    beforeSend: function(){
                        Moodbile.showMask(true);
                    },
                    success: function(data) {
                        Moodbile.templatesHTML[currentLoad] = data;
                                
                        Moodbile.webdb.deleteValues('templates', {'templateName': currentLoad});
                        Moodbile.webdb.addValues('templates', {'templateName': currentLoad, 'HTML': data, 'modDate': Moodbile.templatesLastMod[currentLoad]});
                    
                        Moodbile.currentJson = null;
                        
                        if(templateFIFO.length-1 === 0) {
                            Moodbile.showMask(false);
                        }
                        
                        templateFIFO.pop();
                    }
                }); 
            }
        }, Moodbile.intervalDelay);
    }
    
    var falseCallback = function(){
        var getValueCallback = function(tx, rs) {
            Moodbile.showMask(true);
            for (var i=0; i < rs.rows.length; i++) {
                var templateName = rs.rows.item(i).templateName;
                Moodbile.templatesHTML[templateName] = rs.rows.item(i).HTML;
                if(rs.rows.item(i).modDate != Moodbile.templatesLastMod[templateName]) {
                    Moodbile.webdb.deleteAllValues('templates');
                }
            }
            Moodbile.showMask(false);
        }
                
        Moodbile.webdb.getTemplate(templateName, getValueCallback);
    }
    
    Moodbile.webdb.isEmpty('templates', trueCallback, falseCallback);
}

Moodbile.getTemplate = function(templateName, selector, callback) {
    if(Moodbile.templatesHTML[templateName]){
        $(selector).append(Moodbile.templatesHTML[templateName]);
        callback();
    } else {
        var checkTemplate = setInterval(function() {
            if(Moodbile.templatesHTML[templateName]) {
                clearInterval(checkTemplate);    
                $(selector).append(Moodbile.templatesHTML[templateName]);
                callback();
            }
        }, Moodbile.intervalDelay);
    }
}