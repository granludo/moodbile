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
        $('nav#toolbar #secondary-menu').append('<li id="frontpage"><a href="#">'+ Moodbile.t('Frontpage') +'</a></li>');
        
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
        $('.moodbile-course-name').show();
        
        return false;
    });
    
    //Secondary menu events
    $('nav#toolbar #secondary-menu li a').live('click', function(){
        var menuitem = $(this).parent().attr('id');
        var courseid = $(this).parent().attr('class');
        courseid = courseid.split(' ');
        courseid = courseid[0];
        
        if(menuitem != "frontpage") {
            $('.frontpage-'+courseid).find('section:is(.moodbile-'+menuitem+')').show();
            $('.frontpage-'+courseid).find('section:not(.moodbile-'+menuitem+')').hide();
        } else {
            $('.frontpage-'+courseid).find('section').show();
        }
        
        return false;
    });
    
    //Event to attach ids in secondary-menu when access in course
    $('.moodbile-course a').live('click', function() {
        var id = $(this).parent().attr('id');
        var nav = $('nav#toolbar #secondary-menu li');
        
        $.each(nav, function(){
            $(this).removeClass(); //borramos las clases que hagan referencia al contenido que queremos ver
            $(this).addClass(id);
            
            $('nav#toolbar #main-menu').hide();
            $('nav#toolbar #secondary-menu').show();
        });
        
        $('.moodbile-course-name').hide();
        $('nav#toolbar #secondary-menu li#frontpage').addClass('active');
    });
}