Moodbile.behaviors.hideTemplates = function(context) {
    $('#templates').hide()
}

Moodbile.cloneTemplate = function(templateName, selector) {
    var arg = Moodbile.cloneTemplate.arguments[2];
    
    if (arg) {
        if (typeof arg == "string" && arg == "after") {
            $('#templates').find('.moodbile-'+templateName).clone().insertAfter(selector);
        } else if(typeof arg == "string" && arg == "before") {
            $('#templates').find('.moodbile-'+templateName).clone().insertBefore(selector);
        } else {
            $('#templates').find('.moodbile-'+templateName).clone().appendTo(selector);
        }
    
        if(typeof arg == "function") {
            arg();
        }
    } else {
        $('#templates').find('.moodbile-'+templateName).clone().appendTo(selector);
    }
}