Moodbile.modules.notifications = {
    'status': {
        'dataLoaded': false
    },
    'menu': {
        'itemName': 'Notifications',
        'mainItem': false,
        'secondaryItem': false
    },
    'dependency' : 'courses',
    'initBehavior' : null,
    'depBehavior' : function(context) {
        var context = context || document;
        var exception = ['forum']; //Provisional
        var lastLogin =  $.evalJSON($.readCookie('Moodbile')).lastLogin;
        var matchCookie = $.readCookie('Moodbile_notifications');
        var matches = {};
        
        for(modName in Moodbile.processedData) {
            if(Moodbile.modules[modName+'s'] && $.inArray(modName, exception) == -1) {
                matches[modName] = [];
                $.each(Moodbile.processedData[modName], function() {
                    var added = this.added;
                    var thisId = this.id;
                    if (added >= lastLogin) {
                        matches[modName].push(thisId);
                    }
                });
            }
        }
        
        if(matchCookie){
            
        } else {
        
        }
        alert($.toJSON(matches));
    },
    'auxFunc' : {}        
}