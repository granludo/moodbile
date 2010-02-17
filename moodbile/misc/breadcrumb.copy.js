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