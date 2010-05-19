var Moodbile = {'user': null, 'modules': {}, 'behaviors': {}, 'aux': {}, 'templatesUrl': [], 'templatesLastMod': [], 'i18n': [], 'alert': { 'error':[], 'warning':[], 'success':[] }, 'fx' : {}};
Moodbile.wsurl = "dummie/ws.dum.php";
//Moodbile.wsurl = "http://localhost/~index02/moodbile/moodle/webservice/json/server.php";
Moodbile.location =  location.href;
Moodbile.lang = "es_ES";
Moodbile.tStrings = null;
Moodbile.currentJson = null;
Moodbile.requestJson = [];
Moodbile.queueJson = [];
Moodbile.processedData = []; //Array con datos JSON ya procesados o filtrados
Moodbile.enroledCourses = []; //Array donde dentro se guardan los ids de los cursos del cual el usuario esta enrolado
Moodbile.intervalDelay = 25;
Moodbile.actualDate = new Date();
Moodbile.ExpireTimes = [];//in minutes
Moodbile.ExpireTimes['requestJSON'] = 5;
Moodbile.needReload = true;
Moodbile.templatesHTML = [];
Moodbile.dbCache = null;
Moodbile.enableFx = true;

//Funcion que ejecuta los comportamientos de los js de cada modulo
Moodbile.attachBehaviors = function(context) {
    var context = context || document;
    
    //Attach general purpouse behaviours
    jQuery.each(Moodbile.behaviors, function() {
        this(context);
    });
    
    //Attach modules behaviours
    Moodbile.showMask(true);
    var userCheck = setInterval(function(){
        if(Moodbile.user != null) {
            clearInterval(userCheck);
            jQuery.each(Moodbile.modules, function() {
                if(this.initBehavior != null) {
                    this.initBehavior(context);
                }
      
                if(this.dependency != null){
                    var depend = Moodbile.modules[this.dependency];
                    var behavior = this.depBehavior;
        
                    var depInterval = setInterval(function() {
                        if(depend.status.dataLoaded) {
                            behavior(context);
                            clearInterval(depInterval);
                        }
                    }, Moodbile.intervalDelay);
                }
            }); 
        }
    }, Moodbile.intervalDelay);
    Moodbile.showMask(false);
}

//Internacionalization
Moodbile.t = function(stringToTranslate) {
    if(Moodbile.i18n[stringToTranslate] != null) {
        var string = Moodbile.i18n[stringToTranslate];
    } else {
        var string = "STRING!";
    }
    return string;
}

//Site header (logo) behavior
Moodbile.behaviors.sitename = function(context) {
    $('a.sitename').live('click', function() {
        window.location = Moodbile.location;
    });
}

//Loading & other mask
Moodbile.behaviors.createMask = function(context) {
    $('#container').after('<div id="mask"><div>'+Moodbile.t('Loading')+'...</div></div>');
}

Moodbile.showMask = function(op) {
    if(op == true){
        if($('#mask').is(':hidden')) {
            $('#mask').show();
        }
    } else {
        $('#mask').hide();
    }
}

Moodbile.behaviors.infoViewer = function() {
    var callback = function() {
        $('.moodbile-info-view').attr('id', 'info-viewer');
        $('#info-viewer').find('.back').find('.moodbile-icon').append(Moodbile.t('Back'));
        $('#info-viewer').find('.close').find('.moodbile-icon').append(Moodbile.t('Close'));
        $('#info-viewer').hide();
        $('.back').hide(); 
    }
    
    Moodbile.getTemplate('info-viewer', '#container', callback);
    
    $('.back').live('click', function(){
        Moodbile.fx.SlideRigthLeft('#info-viewer .content:last-child');

        var intervalToRemove = setInterval(function() {
            if($('#info-viewer .content:last-child').is(':hidden')) {
                clearInterval(intervalToRemove);
                $('#info-viewer .content:last-child').remove();
                $('#info-viewer .content:last-child').show();
                
                if ($('#info-viewer').find('.content').length == 1) {
                   $('.back').hide(); 
                }
            }
        }, Moodbile.intervalDelay);
    });
    
    //Habilitamos el boton de cerrar
    $('.close').live('click', function(){
        Moodbile.fx.SlideUpDown('#info-viewer');
        
        if ($('#info-viewer').find('.content').length != 0) {
            $('#info-viewer').find('.content:gt(0)').remove();
            $('#info-viewer').find('.content:last-child').show();
            $('.back').hide(); 
        }
    });
}

Moodbile.infoViewer = function(title, type, info) {
    $('#info-viewer').find('.moodbile-info-view-title').find('h1').text(title);
    
    //Añadimos info
    if ($('#info-viewer').is(':hidden')) {
        $('#info-viewer').find('.content').removeClass().addClass('content');
        $('#info-viewer').find('.content').addClass(type).html(info);
    } else {
        var length = $('#info-viewer').find('.content').length;
        
        $('#info-viewer').find('.content:last-child').clone().appendTo('.moodbile-info-view-content');
        $('#info-viewer').find('.content:last-child').removeClass().addClass('content');
        $('#info-viewer').find('.content:last-child').addClass(type).html(info).hide();
        $('#info-viewer').find('.content:lt('+length+')').hide();
        
        $('.back').show();
        
        Moodbile.fx.SlideRigthLeft('#info-viewer .content:last-child');
    }
    
    //Habilitamos el callback
    if (Moodbile.infoViewer.arguments[3] != null){
        var callback = Moodbile.aux.infoViewer.arguments[3];
        callback();
    }
    
    //Subimos el scroll hasta arriba y mostramos infoViewer
    $('#info-viewer').css({"min-height": $(document).height()-10+"px"});
    if ($('#info-viewer').is(':hidden')) {
        Moodbile.fx.SlideUpDown('#info-viewer');
    }
}

Moodbile.behaviors.collapsible = function() {
    $(".collapse").live('click', function(){
        if($(this).parent().find('.collapsible').is('.expanded')) {
            $(this).parent().find('.collapsible').removeClass('expanded');
            $(this).parent().find('.collapsible').addClass('collapsed');
        } else {
            $(this).parent().find('.collapsible').removeClass('collapsed');
            $(this).parent().find('.collapsible').addClass('expanded');
        }
        
        return false;
    });
}

$(document).ready(function() { 
    Moodbile.attachBehaviors(this);
});