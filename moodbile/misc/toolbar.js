Moodbile.behaviorsPatterns.activeSection = function(context){
    $('nav#toolbar li a').live('click', function(){
        $('nav#toolbar').find('.active').removeClass('active');
        $(this).parent().addClass('active'); 
    });
}

Moodbile.behaviorsPatterns.toolbar = function(context){ //CAMBIAR NOMBRE de ID. DE #toolbar -> #navbar
    //hacemos desaparecer los menus indecesaios
    var menu_items = $('nav li').length-1;
    
    $('nav#toolbar').css('display', 'none'); //ocultamos todas barra de navegacion

    //una vez pulsamos el curso
    $('.moodbile-course a').live('click', function(){
        var id = $(this).parent().attr('id');
        var nav = $('nav#toolbar li');
        
        $.each(nav, function(){
               //var item = $('nav#toolbar li:eq('+i+')').attr('id');
               
               $(this).removeClass(); //borramos las clases que hagan referencia al contenido que queremos ver
               $(this).addClass(id);
               $('nav#toolbar').show();
        });
        
        if($('nav#toolbar li:last-child').is('#more')){
            $('section.toolbar-more div').removeClass(); //borramos las clases que hagan referencia al contenido que queremos ver
            $('section.toolbar-more div').addClass(id);
        }
    });
    
    $('nav#toolbar li#courses a').live('click', function(){
        $('nav#toolbar').hide(); //ocultamos todas las opciones excepto el curso, que es el home
        $('div.moodbile-courses-links section').show();
    });
}

Moodbile.behaviorsPatterns.toolbarEvents = function(context){
    var context = context || document;
    
    $('nav#toolbar li:not(:first-child) a, .toolbar-more div a').live('click', function(){
        var menuitem = $(this).parent().attr('id');
        var courseid = $(this).parent().attr('class');
        courseid = courseid.split(' ');
        courseid = courseid[0];
        
        $('#wrapper').children().hide();
        if(menuitem != "more"){
            $('.'+ menuitem +'-'+courseid).show().children().show();
        } else {
            $('.toolbar-more').show();
        }
    });
    
    $('nav#toolbar li#courses a').live('click', function(){
        $('#wrapper').children().hide();
        $('div.moodbile-courses-links').show();
        $('nav#breadcrumb').hide();
        
        return false;
    });
}

Moodbile.behaviorsPatterns.toolbarAcomodation = function(context) {
    var width = $('body').width();
    var menuItems = $('nav#toolbar li').length-1;
    
    //Mejorar estos calculos
    var itemsWidth = $('nav#toolbar li').css('width').indexOf('px');
    var itemsMargin = $('nav#toolbar li').css('marginLeft').indexOf('px');
    var itemsPadding = $('nav#toolbar li').css('paddingLeft').indexOf('px');
    itemsWidth = $('nav#toolbar li').css('width').slice(0, itemsWidth);
    itemsMargin = $('nav#toolbar li').css('marginLeft').slice(0, itemsMargin);
    itemsPadding = $('nav#toolbar li').css('paddingLeft').slice(0, itemsPadding);
    var itemsWidth = eval(itemsWidth) + (eval(itemsMargin) + eval(itemsPadding))*2;
    var maxItems = (Math.round(width/itemsWidth))-1;
    
    if(menuItems > maxItems){
        $('#wrapper').append('<section class="toolbar-more"/>').find('.toolbar-more').hide();
        
        maxItems -= 1;
        var itemsToChange = $('nav#toolbar li:gt('+ maxItems +')');
        
        $.each(itemsToChange, function(){
            var linkTitle = $(this).text();
            var itemid = $(this).attr('id');
            
            $('.toolbar-more').append('<div id="'+ itemid +'"><a href="#" class="arrow"><span></span>'+ linkTitle +'</a></div>');
        });
        
        maxItems += 1;
        $('nav#toolbar li:gt('+ maxItems +')').remove();
        $('nav#toolbar li:eq('+ maxItems +')').removeAttr('id').attr('id','more').find('a').text(Moodbile.t('More'));
    }
    
}