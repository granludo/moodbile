var Moodbile = {'user': null, 'modules': {}, 'behaviors': {}, 'events': {}, 'i18n': {}};
Moodbile.wsurl = "http://localhost/~index02/moodbile/moodle/webservice/json/server.php";
//Moodbile.wsurl = "http://omega-72-243.lsi.upc.edu:8888/moodle20/webservice/json/server.php";
Moodbile.serverLocation = "http://omega-72-243.lsi.upc.edu:8888/moodle20";
Moodbile.location =  location.href;
//Moodbile.online = navigator.onLine ? true : false;
Moodbile.intervalDelay = 25;
Moodbile.actualDate = new Date();
Moodbile.enableFx = true;

/**
 * Attach behaviors & module behaviors
 *
 * @param document context
 * 
 */
Moodbile.startClient = function(context) {
    var context = context || document;
    
    //Attach general purpouse behaviours     
    jQuery.each(Moodbile.behaviors, function() {
        this(context);
    });
    
    //Attach modules behaviours
    Moodbile.mask.show();
    
    var userCheck = setInterval(function(){
        if(Moodbile.user) {
            clearInterval(userCheck);
            
            jQuery.each(Moodbile.modules, function(i, module) {
                if(module.initBehavior) {
                    module.initBehavior(context);
                }
      
                if(module.dependency){
                    var depend = Moodbile.modules[module.dependency], behavior = module.depBehavior;
        
                    var depInterval = setInterval(function() {
                        if(depend.status.dataLoaded) {
                            clearInterval(depInterval);
                            
                            if(behavior) {
                                behavior(context);
                            }
                        }
                    }, Moodbile.intervalDelay);
                }
            });
        }
    }, Moodbile.intervalDelay);
    
    Moodbile.mask.hide();
};

/**
 * Sitename event. On click, redirect to index page
 * 
 */
Moodbile.events.sitename = $('a.sitename').live('click', function() {
    window.location = Moodbile.location;
});

/**
 * Event for collapsible data
 * 
 */
Moodbile.events.collapsible = $(".collapse").live('click', function(){
    $(this).next('.collapsible').toggle().toggleClass('collapsed');
        
    return false;
});

/**
 * Insert mask in document
 * 
 */
Moodbile.behaviors.createMask = function(context) {
    $('#container').after('<div id="mask"><div>'+Moodbile.t('Loading')+'...</div></div>');
};

/**
 * Functions for control mask
 * 
 */
Moodbile.mask = {};
Moodbile.mask.show = function(){
    var _mask = $('#mask');
    
    _mask.css({'height': '100%'});
    
    if (_mask.is(':hidden')) {
        _mask.show();
    }
}
Moodbile.mask.hide = function(){
    var _mask = $('#mask');
    
    _mask.hide();
}

/**
 * Internacionalization function
 * 
 * @str Key of string to translate
 * @return trasnlated string
 */
Moodbile.t = function(str) {
    if (Moodbile.i18n[str]) {
        var string = Moodbile.i18n[str];
    } else {
        var string = "STRING!";
    }
    
    return string;
};

/**
 * Timestamp corvertion functions
 * 
 * @unixTimestamp 
 * @return trasnlated string
 */
Moodbile.time = {};

Moodbile.time.getDate = function (unixTimestamp) {
    var date = new Date();
    date.setTime(unixTimestamp*1000);
    
    return date.toLocaleDateString();
};

Moodbile.time.getTime = function (unixTimestamp) {
    var date = new Date();
    date.setTime(unixTimestamp*1000);
        
    return date.toLocaleTimeString();
};
    
Moodbile.time.getDateTime = function (unixTimestamp) {
    var date = new Date();
    date.setTime(unixTimestamp*1000);
        
    return date.toLocaleString();
};

Moodbile.time.convertTimestamp = function(unixTimestamp) {
    return unixTimestamp*1000;
};

/**
 * Get user avatar
 * 
 * @userid User id
 * @return String with avatar location in server
 */
Moodbile.userAvatarUrl = function(userid) {
    return Moodbile.serverLocation+'/user/pix.php/'+userid+'/f2.jpg';
};

/**
 * Activate edit in place
 * 
 * @userid User id
 * @return String with avatar location in server
 */
Moodbile.editInPlace = {
    'events' : {},
    'content' : null
};
Moodbile.editInPlace.events.edit = $('.edit-in-place').live('click', function () {
//busca la clase .editable dentro del parent y cambia el contenido por un textarea con el contenido editable
    var _this = $(this);
    var _editable = $(this).parent().find('.editable');
    
    Moodbile.editInPlace.content = _editable.html();
    
    _editable.html('<textarea>'+  Moodbile.editInPlace.content +'</textarea>');
    _this.after('');
});
Moodbile.editInPlace.events.save = $('.edit-in-place').live('click', function () {
//una vez se pulsa se envia la informacion dentro del textarea. Si es igual, se cancela la operacion 
});
Moodbile.editInPlace.events.cancel = $('.edit-in-place').live('click', function () {

});


$(document).ready(function() {
    Moodbile.startClient(this);
});