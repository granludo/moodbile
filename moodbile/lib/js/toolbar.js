Moodbile.behaviors.activeSection = function(context){
    $('nav#toolbar li a').live('click', function(){
        $('nav#toolbar').find('.active').removeClass('active');
        $(this).parent().addClass('active'); 
    });
}

Moodbile.behaviors.toolbar = function(context){
    if(Moodbile.isLoged()) {
        $('nav#toolbar').show();
        
        //main menu
        $('nav#toolbar').append('<ul id="main-menu" class="moodbile-toolbar"/>');
        
        //secondary menu
        $('nav#toolbar').append('<ul id="secondary-menu" class="moodbile-toolbar"/>');
        $('nav#toolbar #secondary-menu').hide();
    
        $.each(Moodbile.modules, function() {
            var itemName = this.menu.itemName;
            var mainItem = this.menu.mainItem;
            var secondaryItem = this.menu.secondaryItem;
        
            if(mainItem) {
                $('nav#toolbar #main-menu').append('<li id="'+itemName.toLowerCase() +'"><a href="#">'+ Moodbile.t(itemName) +'</a></li>');
                if(itemName == "Courses") {
                    $('nav#toolbar #main-menu').find('li#'+itemName.toLowerCase()).addClass('active');
                }
            }
            if(secondaryItem) {
                $('nav#toolbar #secondary-menu').append('<li id="'+itemName.toLowerCase() +'"><a href="#">'+ Moodbile.t(itemName) +'</a></li>');
            }
        });
    }
}

Moodbile.behaviors.toolbarEvents = function(context){
    var context = context || document;
    //TODO: NO OLVIDARSE DEL CASO CUANDO LOS ITEMS NO ENTRAN POR PANTALLA
    
    //Main menu events
    $('nav#toolbar #main-menu li a').live('click', function(){
        var itemToShow = $(this).parent().attr('id');
        
        if(itemToShow != "courses") {
            $('#wrapper').find('.moodbile-frontpage').find('section:is(.moodbile-'+itemToShow+')').show();
            $('#wrapper').find('section:not(.moodbile-'+itemToShow+')').hide();
        } else {
            $('#wrapper').find('section:is(.moodbile-'+itemToShow+')').show();
            $('#wrapper').find('section:not(.moodbile-'+itemToShow+')').hide();
        }
        
        return false;
    });
    
    //Secondary menu events
    $('nav#toolbar #secondary-menu li a').live('click', function(){
        var menuitem = $(this).parent().attr('id');
        var courseid = $(this).parent().attr('class');
        courseid = courseid.split(' ');
        courseid = courseid[0];
        
        //if(menuitem != "more"){
            $('.frontpage-'+courseid).find('section:is(.moodbile-'+menuitem+')').show();
            $('.frontpage-'+courseid).find('section:not(.moodbile-'+menuitem+')').hide();
        //} else {
          //  $('.toolbar-more').show();
        //}
        
        return false;
    });
    
    //Event to attach ids in secondary-menu when access in a course
    $('.moodbile-course a').live('click', function(){
        var id = $(this).parent().attr('id');
        var nav = $('nav#toolbar #secondary-menu li');
        
        $.each(nav, function(){
            $(this).removeClass(); //borramos las clases que hagan referencia al contenido que queremos ver
            $(this).addClass(id);
            
            $('nav#toolbar #main-menu').hide();
            $('nav#toolbar #secondary-menu').show();
        });
    });
}

/*Moodbile.behaviors.toolbarAcomodation = function(context) {
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
    
}*/