Moodbile.behaviors.hideTemplates = function(context) {
    $('#templates').hide()
}

Moodbile.cloneTemplate = function(templateName, selector) {
    $('#templates').find('.moodbile-'+templateName).clone().appendTo(selector);
    
    var callback = Moodbile.cloneTemplate.arguments[2];
    if(callback) {
        callback();
    }
}