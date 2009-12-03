$(function(){
    //hacemos desaparecer los menus indecesaios
    var menu_items = $('nav li').length-1;
    //alert(menu_items);
    
    $('nav').css('display', 'none'); //ocultamos todas barra de navegacion
    //$('nav li:eq(0)').show();

    //una vez pulsamos el curso
    $('.course a').live('click', function(){
        var id = $(this).attr('id')
        var nav = $('nav li');
        
        $.each(nav, function(i, nav){
               var item = $('nav li:eq('+i+')').attr('id');
               
               $('nav li:eq('+i+')').removeClass(); //borramos las clases que hagan referencia al contenido que queremos ver
               $('nav li:eq('+i+')').addClass(id);
               
               
               $('nav').show();
        });
    });
    
    $('nav li#courses a, #sitename').live('click', function(){
        $('nav').hide(); //ocultamos todas las opciones excepto el curso, que es el home
        $('section:visible').hide();
    });
});