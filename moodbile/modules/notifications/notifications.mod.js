Moodbile.behaviorsPatterns.notifications = function(context){
    var context = context || document;
    
    Moodbile.notificationsNum = 0;
     
    var loadInterval = setInterval(function(){
        if(Moodbile.enroledCoursesid.length != 0){
            Moodbile.aux.notifications(context); 
            clearInterval(loadInterval);
        }
    }, Moodbile.intervalDelay);
    
    $('#wrapper').find('.courses').before('<section class="notifications-links"><div class="arrow"><a href="#">'+ Moodbile.t('Notifications') +'</a><span class="notification-num"></span></div></section>');
    $('.notification-num').addClass('loading');
    
    $('.notifications-links a').live('click', function(){
                
        $('section:visible').hide();
        $('.notifications, .notifications section').show();
        
        return false;
    });
    
    $('.notifications section a').live('click', function(){
        if($(this).is('.clicked') == false) {
            Moodbile.notificationsNum -= 1;
            
            if (Moodbile.notificationsNum < 1) {
                $('.notification-num').hide();
            }
            
            $('.notification-num').text(Moodbile.notificationsNum);
            $(this).addClass('clicked');
        }
    });
}

Moodbile.aux.notifications = function(context){
    var context = context || document;
    var userLastlogin = Moodbile.user.lastlogin;
    
    $('#wrapper').append('<div class="notifications"></div>');
    $('.notifications').hide();
    
    //TODO: Hacer que sea excalable para otros modulos.
    
    var loadNotifications = setInterval(function(){
        if(Moodbile.requestJson.courses != null && Moodbile.requestJson.resources != null && Moodbile.requestJson.events != null && Moodbile.requestJson.forums != null) {
            clearInterval(loadNotifications);
            
            Moodbile.templates.NotificationsResources(Moodbile.requestJson.resources, userLastlogin);
            Moodbile.templates.NotificationsEvents(Moodbile.requestJson.events, userLastlogin);
            //Moodbile.templates.NotificationsForums(Moodbile.requestJson.forums, userLastlogin);
            
            $('.notification-num').text(Moodbile.notificationsNum).removeClass('loading').addClass('loaded').show();
            //$('.notifications-links').show();
        }
    }, Moodbile.intervalDelay);
}

Moodbile.templates.NotificationsResources = function(json, userLastlogin){
    $('#wrapper').find('.notifications').append('<section class="notification-resources"></section>');
    
    $.each(json, function(i, json){
        var lastmodification = json.resource.lastmodification; 
        
        if(lastmodification > userLastlogin){
            var coursename = $('.courses div#'+json.courseid+' a').text();
            
            $('#wrapper .notifications').find('.notification-resources').append('<div class="resource ' + json.resource.id + '"><a href="#"><span class="icon-'+json.resource.type+'"></span>' + json.resource.title + '</a><div class="description">' + coursename + '</div></div>');
            
            Moodbile.notificationsNum += 1;
        }
   });
}

Moodbile.templates.NotificationsEvents = function(json, userLastlogin){
    $('#wrapper').find('.notifications').append('<section class="notification-events"></section>');
    
    $.each(json, function(i, json){
        var lastmodification = json.lastmodification; 
        
        if(lastmodification > userLastlogin){
            var coursename = $('.courses div#'+json.courseid+' a').text();
        
            $('#wrapper .notifications').find('.notification-events').append('<div class="event ' + json.id + ' fx"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a><div class="description">' + coursename + '</div></div>');
            
            Moodbile.notificationsNum += 1;
        }
    });
}

Moodbile.templates.NotificationsForums = function(json, userLastlogin){
    $('#wrapper').find('.notifications').append('<section class="notification-forum"></section>');
    
    $.each(json, function(i, json){
        var lastmodification = json.resource.lastmodification; 
        
        if(lastmodification > userLastlogin){
            var coursename = $('.courses div#'+json.courseid+' a').text();
        
            $('#wrapper .notifications').find('.notification-forum').append('<div class="forum ' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a><div class="description">' + coursename + '</div></div>');
        }
    });
}