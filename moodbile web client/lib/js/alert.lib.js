Moodbile.alert = { 'error':[], 'warning':[], 'success':[] };

Moodbile.alert.addAlert = function(alertType, strKey) {
    Moodbile.alert[alertType].push(strKey);
    
    Moodbile.loadAlert();
}

Moodbile.alert.loadAlert = function (){
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
        }
        
    });
    
    if(alertNum != 0) {
        var alertTitle = Moodbile.t('Alert');
        
        $('#alert-dialog').find('.moodbile-alert-num').text(Moodbile.t('Alert_num') +': '+ alertNum);
        
        var alertHTML = $('#alert-dialog').html();
        Moodbile.infoViewer(alertTitle, "alert", alertHTML);
        
        setTimeout(function(){
            Moodbile.fx.SlideUpDown('#info-viewer');
        }, Moodbile.intervalDelay*20*3);
    }
}

//Behavior
Moodbile.behaviors.alert = function(context){
    var context = context || document;
            
    Moodbile.cloneTemplate('alert-dialog', '#container', Moodbile.loadAlert);
}