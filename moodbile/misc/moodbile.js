var Moodbile = {'behaviorsPatterns': {}, 'aux': {}, 'templates': {}};
//Moodbile.behaviorsPatterns.helloword = function (){ alert('hello word'); };
Moodbile.wsurl = "dummie/ws.dum.php";
Moodbile.enroledCoursesid = []; //Array donde dentro se guardan los ids de los cursos del cual el usuario esta enrolado

//Funcion que ejecuta los comportamientos de los js de cada modulo
Moodbile.attachBehaviors = function(context) {
    var context = context || document;
    jQuery.each(Moodbile.behaviorsPatterns, function() {
      this(context);
      //alert(Moodbile.behaviorsPatterns);
    });
}

Moodbile.jsonRequest = function(context, op, callbackFunction) {
    $.getJSON(Moodbile.wsurl +'?jsoncallback=?', {op: op}, callbackFunction);
}

Moodbile.behaviorsPatterns.breadcrumb = function(context){
    //Acciones cuando se usa el breadcrums
    $('nav#breadcrumb li#level-1 a').live('click', function() {
        var id = $(this).parent().parent().attr('class');
        
        if($('#wrapper').find('.frontpage-'+id).is('.frontpage-'+id)) {
            $('section:visible').hide();
            $('#wrapper .frontpage-'+id).show();
            $('nav#breadcrumb li:gt(1)').remove();
            $('nav#toolbar li a').parent().removeClass('active');
        }
        
        return false;
    });
    $('nav#breadcrumb li#level-2 a').live('click', function() {
        var id = $(this).parent().parent().attr('class');
        
        if($('#wrapper').find('.forums-'+id).is('.forums-'+id)) {
            $('section:visible').hide();
            $('#wrapper .forums-'+id).show();
            $('nav#breadcrumb li:gt(2)').remove();
            $('.posts').remove();
            $('nav#toolbar li a').parent().removeClass('active');
        }
        
        return false;
    });
    
    $('nav#breadcrumb li#home a').live('click', function(){
        var id = $(this).parent().attr('class');
        
        if($('#wrapper').find('.courses').is('.courses')) {
            $('section:visible').hide();
            $('section.courses').show();
            $('nav#breadcrumb li:gt(0)').remove();
            $('nav#toolbar li a').parent().removeClass('active');
        }
        
        $('nav#breadcrumb li a').hide();
        $('nav#toolbar').hide();
        
        return false;
    });
    
    //Acciones cuando pulsamos tanto en el tol
    $('.course .course-title').live('click', function(){
       var id = $(this).parent().attr('id');
       var item = $(this).text();
       
       //Añadimos nivel
       $('nav#breadcrumb ul').append('<li id="level-1"><span><a href="#"></a></span></li>');
       
       $('nav#breadcrumb li a').show(); //defecto
       $('nav#breadcrumb li#level-1').addClass(id);
       $('nav#breadcrumb li#level-1 span a').text(item);
       $('nav#breadcrumb li#level-1 span a').show();
       $('nav#breadcrumb li#level-2 a').hide();
    });
    
    $('.forum a').live('click', function(){
       var id = $(this).parent().attr('id');
       var item = $(this).text();
       
       //Añadimos nivel
       $('nav#breadcrumb ul').append('<li id="level-3"><span><a href="#"></a></span></li>');
       
       $('nav#breadcrumb li a').show(); //defecto
       $('nav#breadcrumb li#level-3').addClass(id);
       $('nav#breadcrumb li#level-3 span a').text(item);
       $('nav#breadcrumb li#level-3 span a').show();
       $('nav#breadcrumb li#level-4 a').hide();
    });
    
    $('nav#toolbar li a').live('click', function(){
       var id = $(this).parent().attr('class');
       var item = $(this).text();
       //Borramos nivel 2
       $('nav#breadcrumb li:gt(1)').remove();
       
       //Añadimos nivel
       $('nav#breadcrumb ul').append('<li id="level-2"><span><a href="#"></a></span></li>');

       
       $('nav#breadcrumb li#level-2').addClass(id);
       $('nav#breadcrumb li#level-2 span a').text(item);
       $('nav#breadcrumb li#level-2 a').show();
       
    });
    
    $('nav#toolbar li#courses a').live('click', function(){
       $('nav#breadcrumb li a').hide();
       $('nav#breadcrumb li:gt(0)').remove();
    });
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

Moodbile.aux.infoViewer = function(info) {
    $('#container').append('<section id="info-viewer"><div class="content">'+info+'</div></section>');
    $('#info-viewer').height($(window).height()-20);
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