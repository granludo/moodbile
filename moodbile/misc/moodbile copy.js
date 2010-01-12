var Moodbile = {'behaviorsPatterns': {}, 'aux': {}, 'templates': {}};
//Moodbile.behaviorsPatterns.helloword = function (){ alert('hello word'); };
Moodbile.wsurl = "dummie/ws.dum.php";
Moodbile.currentJson = null;
Moodbile.requestJson = [];
Moodbile.enroledCoursesid = []; //Array donde dentro se guardan los ids de los cursos del cual el usuario esta enrolado

//Funcion que ejecuta los comportamientos de los js de cada modulo
Moodbile.attachBehaviors = function(context) {
    var context = context || document;
    jQuery.each(Moodbile.behaviorsPatterns, function() {
      this(context);
      //alert(Moodbile.behaviorsPatterns);
    });
}

//Funcion basica para realizar peticiones JSON
Moodbile.json = function(context, requestName, op, callbackFunction) {
    //TODO: Probar de hacer un check del estado de currentJson al estilo de la animacion
    //comprobamos si hay una peticion (debido al retardo intentaremos evitar conflictos)
    if (Moodbile.currentJson == null){ //Si no la hay, procedemos con la siguiente
        Moodbile.requestJson[requestName] = null; //Definimos que es null, para comprobaciones
        Moodbile.currentJson = $.getJSON(Moodbile.wsurl +'?jsoncallback=?', {op: op}, function(json){
            Moodbile.requestJson[requestName] = json;
            callbackFunction(json);
            Moodbile.currentJson = null;
        });
    } else { //sino, esperaremos a que no haya peticiones para despues volver a realizarla.
        var loadInterval = setInterval(function(){
            if(Moodbile.currentJson == null){
                clearInterval(loadInterval);
                Moodbile.json(context, requestName, op, callbackFunction);
            }
        }, 100);
    }
}

//Funcion que añade a una cola las peticiones para realizar peticiones JSON
Moodbile.queueJson = function(context, requestName, op, callbackFunction) {

}

Moodbile.behaviorsPatterns.activeSection = function(context){
    $('nav#toolbar li a').live('click', function(){
        $('nav#toolbar').find('.active').removeClass('active');
        $(this).parent().addClass('active'); 
    });
}

Moodbile.behaviorsPatterns.createLoadingBox = function(){
    $('#container').after('<div id="loading">Loading...</div>');
    $('#loading').hide();
}

Moodbile.aux.loading = function(op) {
    if(op == true) {
        $('#loading').show();
    } else {
        $('#loading').hide();
    }
}

Moodbile.aux.infoViewer = function(title, info) {
    $('#container').append('<section id="info-viewer"><header class="title"><a href="#" class="back fx"><span class="icon-back">back</span></a><h1>'+title+'</h1></header><div class="content">'+info+'</div></section>');
    $('#info-viewer').height($(window).height()-10);
    
    //Habilitamos el boton de regreso
    //TODO: Probar si los bottones son admitidos como clickables
    $('.back').live('click', function(){
        $('#info-viewer').remove();
        $('#content, #toolbar').show();
    });
}

Moodbile.behaviorsPatterns.collapsible = function() {
    //Prevent CSS
    $(".collapsible").live('click', function(){
        //TODO: Mejorarlo, aprender a crear eventos.
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

Moodbile.behaviorsPatterns.toolbar = function(context){
    //hacemos desaparecer los menus indecesaios
    var menu_items = $('nav li').length-1;
    //alert(menu_items);
    
    $('nav#toolbar').css('display', 'none'); //ocultamos todas barra de navegacion
    //$('nav li:eq(0)').show();

    //una vez pulsamos el curso
    $('.course a').live('click', function(){
        var id = $(this).parent().attr('id');
        var nav = $('nav#toolbar li');
        
        $.each(nav, function(i, nav){
               var item = $('nav#toolbar li:eq('+i+')').attr('id');
               
               $('nav#toolbar li:eq('+i+')').removeClass(); //borramos las clases que hagan referencia al contenido que queremos ver
               $('nav#toolbar li:eq('+i+')').addClass(id);
               $('nav#toolbar').show();
        });
    });
    
    $('nav#toolbar li#courses a, #sitename').live('click', function(){
        $('nav#toolbar').hide(); //ocultamos todas las opciones excepto el curso, que es el home
        $('section:visible').hide();
    });
}

$(document).ready(function() { 
    Moodbile.attachBehaviors(this);
});