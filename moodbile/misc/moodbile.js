var Moodbile = {'user': null, 'behaviorsPatterns': {}, 'aux': {}, 'jsonCallbacks': {}};

//Moodbile.wsurl = "http://basketpc.com/ind3x/ws.dum.php";
Moodbile.wsurl = "dummie/ws.dum.php";
Moodbile.location =  location.href;
Moodbile.lang = "es_ES";
Moodbile.tStrings = null;
Moodbile.currentJson = null;
Moodbile.requestJson = [];
Moodbile.queueJson = [];
Moodbile.enroledCoursesid = []; //Array donde dentro se guardan los ids de los cursos del cual el usuario esta enrolado
Moodbile.intervalDelay = 50;
Moodbile.actualDate = new Date();
Moodbile.ExpireTimes = [];//in minutes
Moodbile.ExpireTimes['requestJSON'] = 5;
Moodbile.ExpireTimes['templatesHTML'] = 10
Moodbile.needReload = true;
Moodbile.templatesHTML = [];
Moodbile.dbCache = null;

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

Moodbile.behaviorsPatterns.createLoadingBox = function(context){
    $('#container').after('<div id="loading"><div>'+Moodbile.t('Loading')+'...</div></div>');
    
    //habilitar acciones para su uso.
    $('#loading').ajaxSend(function() {
        $(this).show();
    });
    
    $('#loading').ajaxSuccess(function() {
            $(this).hide();
    });
}

Moodbile.loadig = function(){
    if($('#loading').is(':visible')){
        $('#loading').hide();
    } else {
        $('#loading').show();
    }
}

Moodbile.behaviorsPatterns.infoViewer = function(){
    var callback = function() {
        $('.moodbile-info-view').attr('id', 'info-viewer');
        $('#info-viewer').find('.moodbile-icon').addClass('icon-back').append(Moodbile.t('Back'));
        $('#info-viewer').css({"min-height": $(window).height()-10+"px"}).hide();
    }
    
    Moodbile.loadTemplate('info-viewer', '#container', callback);
    
    //Habilitamos el boton de regreso
    $('.back').live('click', function(){
        $('#info-viewer').hide();
        $('#content').show();
    });
}

Moodbile.aux.infoViewer = function(title, type, info) {
    //Borramos clases anteriores
    $('#info-viewer').find('.content').removeClass().addClass('content');
    
    //Añadimos info
    $('#info-viewer').find('.moodbile-info-view-title').find('h1').text(title);
    $('#info-viewer').find('.content').addClass(type).html(info);
    
    //Habilitamos el callback
    if (Moodbile.aux.infoViewer.arguments[3] != null){
        var callback = Moodbile.aux.infoViewer.arguments[3];
        callback();
    }
    
    //mostramos infoViewer
    $('#info-viewer').show();   
}

Moodbile.behaviorsPatterns.collapsible = function() {
    //Prevent CSS
    //TODO: Mejorarlo, aprender a crear eventos.
    $(".collapsible").live('click', function(){
        if($(this).parent().parent().is('.expanded')) {
            $(this).parent().parent().removeClass('expanded');
            $(this).parent().parent().addClass('collapsed');
        } else {
            $(this).parent().parent().removeClass('collapsed');
            $(this).parent().parent().addClass('expanded');
        }
        
        return false;
    });
}

$(document).ready(function() { 
    Moodbile.attachBehaviors(this);
});