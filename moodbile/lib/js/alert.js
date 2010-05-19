Moodbile.behaviors.alert = function(context){
    var context = context || document;
            
    Moodbile.getTemplate('alert-dialog', '#container', Moodbile.aux.loadAlert);
}

Moodbile.addAlert = function(alertType, strKey) {
    Moodbile.alert[alertType].push(strKey);
    
    Moodbile.aux.loadAlert();
}

Moodbile.aux.loadAlert = function (){
    var alertNum = 0;
    
    $('#alert-dialog').hide();
    $('#alert-dialog ul').empty();
        
    $.each(Moodbile.alert, function(i, val) {
        var alertType = i.toString();
        
        if(Moodbile.alert[i].length != 0) {
            alertNum += Moodbile.alert[i].length;
            
            $.each(Moodbile.alert[i], function() {
                $('.moodbile-alert-'+alertType).append('<li/>');
                $('.moodbile-alert-'+alertType).find('li:last-child').text(Moodbile.t(this));
            });
                
            //Moodbile.alert[alertType] = [];
        }
        
    });
    
    if(alertNum != 0) {
        var alertTitle = Moodbile.t('Alert')
        
        $('#alert-dialog').find('.moodbile-alert-num').text(Moodbile.t('Alert_num') +': '+ alertNum);
        
        var alertHTML = $('#alert-dialog').html();
        Moodbile.infoViewer(alertTitle, "alert", alertHTML);
        
        setTimeout(function(){
            Moodbile.fx('#info-viewer');
        }, Moodbile.intervalDelay*20*3);
    }
}