var Moodbile = {'user': null, 'modules': {}, 'behaviors': {}, 'events': {}, 'i18n': {}};
Moodbile.wsurl = "http://localhost/~index02/moodbile/moodle/webservice/json/server.php";
//Moodbile.wsurl = "http://omega-72-243.lsi.upc.edu:8888/moodle20/webservice/json/server.php";
Moodbile.serverLocation = "http://omega-72-243.lsi.upc.edu:8888/moodle20";
Moodbile.location =  location.href;
Moodbile.intervalDelay = 25;
Moodbile.actualDate = new Date();
Moodbile.enableFx = true;

//Funcion que ejecuta los comportamientos de los js de cada modulo
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

//Site header (logo) behavior
Moodbile.events.sitename = $('a.sitename').live('click', function() {
    window.location = Moodbile.location;
});

//Loading & other mask
Moodbile.behaviors.createMask = function(context) {
    $('#container').after('<div id="mask"><div>'+Moodbile.t('Loading')+'...</div></div>');
};

Moodbile.behaviors.collapsible = function() {
    $(".collapse").live('click', function(){
        $(this).next('.collapsible').toggle().toggleClass('collapsed');
        
        return false;
    });
};

//Show mask
Moodbile.mask = {};
Moodbile.mask.show = function(){
    var _mask = $('#mask');
    
    if (_mask.is(':hidden')) {
        _mask.show();
    }
}
Moodbile.mask.hide = function(){
    var _mask = $('#mask');
    
    _mask.hide();
}

//Internacionalization
Moodbile.t = function(stringToTranslate) {
    if(Moodbile.i18n[stringToTranslate] != null) {
        var string = Moodbile.i18n[stringToTranslate];
    } else {
        var string = "STRING!";
    }
    return string;
};

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

Moodbile.userAvatarUrl = function(userid) {
    return Moodbile.serverLocation+'/user/pix.php/'+userid+'/f2.jpg';
};

$(document).ready(function() { 
    Moodbile.startClient(this);
});