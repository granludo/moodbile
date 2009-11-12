var Moodbile = {'behaviorsPatterns': {}, 'aux': {}};
//Moodbile.behaviorsPatterns.helloword = function (){ alert('hello word'); };

//Funcion que ejecuta los comportamientos de los js de cada modulo
Moodbile.attachBehaviors = function(context){
    var context = context || document;
    jQuery.each(Moodbile.behaviorsPatterns, function() {
      this(context);
      //alert(Moodbile.behaviorsPatterns);
    });
}


Moodbile.behaviorsPatterns.breadcrumb = function(context){
    //Acciones cuando se usa el breadcrums
    $('nav#breadcrumb li#level-1 a').live('click', function(){
        var id = $(this).parent().attr('class');
        
        if($('#wrapper').find('.frontpage-'+id).is('.frontpage-'+id)) {
            $('section:visible').hide();
            $('#wrapper .frontpage-'+id).show();
            $('nav#breadcrumb li#level-2 a').hide();
            $('nav#toolbar li a').parent().removeClass('active');
        }
        
        //$('nav#breadcrumb li#level-2').hide();
        
        return false;
    });
    $('nav#breadcrumb li#home a').live('click', function(){
        var id = $(this).parent().attr('class');
        
        if($('#wrapper').find('.courses').is('.courses')) {
            $('section:visible').hide();
            $('section.courses').show();
            $('nav#breadcrumb li#level-2 a').hide();
            $('nav#toolbar li a').parent().removeClass('active');
        }
        
        $('nav#breadcrumb li a').hide();
        $('nav#toolbar').hide();
        
        return false;
    });
    
    //Acciones cuando pulsamos tanto en el tol
    $('.course a').live('click', function(){
       var id = $(this).attr('id');
       var item = $(this).text();
       
       $('nav#breadcrumb li a').show(); //defecto
       $('nav#breadcrumb li#level-1').addClass(id);
       $('nav#breadcrumb li#level-1 span').text(item);
       $('nav#breadcrumb li#level-1 a').show();
       $('nav#breadcrumb li#level-2 a').hide();
    });
    
    $('nav#toolbar li a').live('click', function(){
       var id = $(this).parent().attr('class');
       var item = $(this).text();
       
       //$('nav#breadcrumb li#level-2').addClass(id);
       $('nav#breadcrumb li#level-2 span').text(item);
       $('nav#breadcrumb li#level-2 a').show();
       
    });
    
    $('nav#toolbar li#courses a').live('click', function(){
       $('nav#breadcrumb li a').hide();
    });
}

Moodbile.behaviorsPatterns.activeSection = function(context){
    $('nav#toolbar li a').live('click', function(){
        $('nav#toolbar').find('.active').removeClass('active');
        $(this).parent().addClass('active'); 
    });
}

Moodbile.behaviorsPatterns.core = function(context){
    //hacemos desaparecer los menus indecesaios
    var menu_items = $('nav li').length-1;
    //alert(menu_items);
    
    $('nav#toolbar').css('display', 'none'); //ocultamos todas barra de navegacion
    //$('nav li:eq(0)').show();

    //una vez pulsamos el curso
    $('.course a').live('click', function(){
        var id = $(this).attr('id');
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
    $('.dragy').touch({
        animate: true,
        sticky: false,
        dragx: false,
        dragy: true,
        rotate: false,
        resort: false,
        scale: false 
    });
});