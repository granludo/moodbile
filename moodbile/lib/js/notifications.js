Moodbile.behaviors.notifications = function() {
    Moodbile.notifications.run();
};

Moodbile.notifications = {};

Moodbile.notifications.setup = {};

Moodbile.notifications.run = function() {
    Moodbile.notifications.attach();
    Moodbile.notifications.match();
    Moodbile.notifications.print()
};

Moodbile.notifications.attach = function () {
    var modules = Moodbile.modules, notification = null, _aux = null;

    $.each(modules, function(modname){
        notification = this.notification;
        
        if(notification) {
            Moodbile.notifications.setup[modname] = notification;
        } 
        
    });
};

Moodbile.notifications.match = function () {
    var segureInt = setInterval(function (){
        if(Moodbile.processedData) {
            clearInterval(segureInt);
            
            $.each(Moodbile.notifications.setup, function(modname){
                var data = this.data, method = this.method;

                Moodbile.notifications.setup[modname].ids = [];
                Moodbile.notifications.setup[modname].count = 0;
        
                if (method == 'processedData') {
                    $.each(Moodbile.processedData[data], function () {
                        var id = this.id;
                        var timeadded = parseInt(this.added), lastlogin = Moodbile.user.lastlogin;
                        
                        if (timeadded > lastlogin) {
                            Moodbile.notifications.setup[modname].ids.push(id);
                            Moodbile.notifications.setup[modname].count++;
                        }
                    }); 
                } else {
                    //something...
                }
            });
            Moodbile.notifications.setCookie();
            Moodbile.notifications.dataLoaded = true;
        }
    }, Moodbile.intervalDelay);
};

Moodbile.notifications.setCookie = function () {
    var cookie = $.readCookie('Moodbile_notifications');
    var value = $.toJSON(Moodbile.notifications.setup);
    var _md5 = $.md5(value);
    
    Moodbile.notifications.md5 = _md5; //md5 string is notification id, use for check future machts
    
    value = {'md5': _md5, 'value': value};
    value = $.toJSON(value);

    if(!cookie) {
        cookie = $.setCookie('Moodbile_notifications', value, {
            duration: 14 // in days
        });
    } else {
        var cookieVal = $.evalJSON(cookie);
        _md5 = cookieVal.md5;
        
        if ( _md5  != Moodbile.notifications.md5 ) {
            cookie = $.setCookie('Moodbile_notifications', value, {
                duration: 14 // in days
            }); 
        }
    }
}

//Print matched new data and insert notifications
Moodbile.notifications.print = function () {
    var segureInt = setInterval(function() {
        if (Moodbile.notifications.dataLoaded) {
            clearInterval(segureInt);
   
            var id = null, _aux = null, menuitem = null, count = null;
            var cookie = $.readCookie('Moodbile_notifications');
            var notifications = $.evalJSON($.evalJSON(cookie).value);
            
            $.each(notifications, function(modname) {
                count = notifications[modname].count;
                menuitem = Moodbile.modules[modname].menu.itemName.toLowerCase();
                
                segureInt = setInterval(function() {
                    if (Moodbile.modules[modname].status.dataLoaded) {
                        clearInterval(segureInt);
                        
                        if(count != 0) {
                            _aux = '<span class="moodbile-notification">'+count+'</span>';
                            $('nav#toolbar ul#main-menu li#'+ menuitem).find('a').append(_aux);
                    
                            $.each(notifications[modname].ids, function(){
                                id = this;
                        
                                _aux = $(".moodbile-"+ modname +"[data-"+ modname +"-id='"+ id +"']");
                                _aux.addClass('new');//ahora subrallamos los datos nuevos
                            });
                        }
                    }
                }, Moodbile.intervalDelay);
            });
        }
    }, Moodbile.intervalDelay);
}

//Events
Moodbile.events.removeNotification = $('.new a').live('click', function(){
    //TODO: Mejorar toolbar, ya que perjudica la escalabilidad, y ademas, revisar el tema de las ids, ya que exixten ids globales e ids concretos al item

    var _clickedItem = $(this).parent(), _aux = null, _value = null;
    var dataType = _clickedItem.attr('data-type');
    
    _clickedItem.removeClass('new');
    
    var modname = _clickedItem.attr('class');
    modname = modname.split('-');
    modname = modname[1];
    
    var id = _clickedItem.attr('data-'+ modname +'-id');
    var cookie = $.readCookie('Moodbile_notifications');
    
    _clickedItem = $('nav#toolbar ul#main-menu li#'+ dataType);
    _aux = parseInt(_clickedItem.find('span.moodbile-notification').text());
    _aux -= 1;
    
    _clickedItem.find('span.moodbile-notification').text(_aux);
    
    if(_aux == 0) {
        _clickedItem.find('span.moodbile-notification').hide();
    }
   
    if (cookie) {
        _value = {'md5': $.evalJSON(cookie).md5, 'value': $.evalJSON(cookie).value};
        _aux = $.evalJSON(_value.value);

        var _aux2 = $.inArray(id, _aux[modname].ids);
        
        _aux[modname].ids.splice(_aux2, 1);
        _aux[modname].count --;
        
        _value.value = $.toJSON(_aux);
        
        cookie = $.setCookie('Moodbile_notifications', $.toJSON(_value), {
                duration: 14 // in days
        });
    }
});