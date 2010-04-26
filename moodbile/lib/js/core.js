var Moodbile = {'user': null, 'behaviorsPatterns': {}, 'aux': {}, 'jsonCallbacks': {}, 'templatesUrl': [], 'i18n': [], 'alert': { 'error':[], 'warning':[], 'success':[] }};

//Moodbile.wsurl = "http://basketpc.com/ind3x/ws.dum.php";
Moodbile.wsurl = "dummie/ws.dum.php";
Moodbile.location =  location.href;
Moodbile.lang = "es_ES";
Moodbile.tStrings = null;
Moodbile.currentJson = null;
Moodbile.requestJson = [];
Moodbile.queueJson = [];
Moodbile.processedData = []; //Array con datos JSON ya procesados o filtrados
Moodbile.enroledCoursesid = []; //Array donde dentro se guardan los ids de los cursos del cual el usuario esta enrolado
Moodbile.intervalDelay = 50;
Moodbile.actualDate = new Date();
Moodbile.ExpireTimes = [];//in minutes
Moodbile.ExpireTimes['requestJSON'] = 5;
Moodbile.ExpireTimes['templatesHTML'] = 10
Moodbile.needReload = true;
Moodbile.templatesHTML = [];
Moodbile.dbCache = null;
Moodbile.enableFx = true;
Moodbile.fx = {};

//Funcion que ejecuta los comportamientos de los js de cada modulo
Moodbile.attachBehaviors = function(context) {
    var context = context || document;
    jQuery.each(Moodbile.behaviorsPatterns, function() {
      this(context);
    });
}

//Internacionalization
//MEJORAR
Moodbile.t = function(stringToTranslate) {
    if(Moodbile.i18n[stringToTranslate] != null) {
        var string = Moodbile.i18n[stringToTranslate];
    } else {
        var string = "STRING!";
    }
    return string;
}

Moodbile.behaviorsPatterns.createMask = function(context){
    $('#container').after('<div id="mask"><div>'+Moodbile.t('Loading')+'...</div></div>');
}

Moodbile.showMask = function(op){
    if(op == true){
        if($('#mask').is(':hidden')) {
            $('#mask').show();
        }
    } else {
        $('#mask').hide();
    }
}

Moodbile.behaviorsPatterns.infoViewer = function(){
    var callback = function() {
        $('.moodbile-info-view').attr('id', 'info-viewer');
        $('#info-viewer').find('.back').find('.moodbile-icon').append(Moodbile.t('Back'));
        $('#info-viewer').find('.close').find('.moodbile-icon').append(Moodbile.t('Close'));
        $('#info-viewer').hide();
        $('.back').hide(); 
    }
    
    Moodbile.loadTemplate('info-viewer', '#container', callback);
    
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
    //Añadimos info
    $('#info-viewer').find('.moodbile-info-view-title').find('h1').text(title);
    
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

Moodbile.behaviorsPatterns.collapsible = function() {
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