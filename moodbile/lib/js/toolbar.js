Moodbile.toolbarOpts = {
        //Type of optional Events are functions, all them inside an object and sorted by menuitem. 
        'mainMenu' : {},
        'secondaryMenu': {
            'frontpage': function () {
                $('.moodbile-course-name, .moodbile-event-date, .moodbile-event-course').hide();
            }
        }
};
    
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
        
        //Make toolbar
        $.each(Moodbile.modules, function() {
            var itemName = this.menu.itemName, mainItem = this.menu.mainItem, mainItemOpts = this.menu.mainItemOpts, secondaryItem = this.menu.secondaryItem, secondaryItemOpts = this.menu.secondaryItemOpts;
        
            if(mainItem) {
                $('nav#toolbar #main-menu').append('<li id="'+itemName.toLowerCase() +'"><a href="#">'+ Moodbile.t(itemName) +'</a></li>');
                if(itemName == "Courses") {
                    $('nav#toolbar #main-menu').find('li#'+itemName.toLowerCase()).addClass('active');
                }
                
                //attach optional function referenced to mainItem
                if (mainItemOpts != null) {
                    Moodbile.toolbarOpts.mainMenu[itemName.toLowerCase()] = mainItemOpts;
                }
            }
            if(secondaryItem) {
                $('nav#toolbar #secondary-menu').append('<li id="'+itemName.toLowerCase() +'"><a href="#">'+ Moodbile.t(itemName) +'</a></li>');
                
                //attach optional function referenced to secondaryItem
                if (secondaryItemOpts != null) {
                    Moodbile.toolbarOpts.secondaryMenu[itemName.toLowerCase()] = secondaryItemOpts;
                }
            }
        });
    }
}

Moodbile.behaviors.toolbarEvents = function(context){
    var context = context || document;
    var menuitem = null, menuitemClass = null, opts = null, courseid = null;
    
    //Main menu events
    $('nav#toolbar #main-menu li a').live('click', function(){
        menuitem = $(this).parent().attr('id');
        opts = Moodbile.toolbarOpts.mainMenu[menuitem];
        
        $("#wrapper div[data-type]").hide();
        $("#wrapper div[data-type='"+menuitem+"']").show().parent().show();
        
        if(opts != null) {
            opts();
        }
        
        return false;
    });
    
    //Secondary menu events
    $('nav#toolbar #secondary-menu li a').live('click', function(){
        menuitem = $(this).parent().attr('id');
        opts = Moodbile.toolbarOpts.secondaryMenu[menuitem];
        courseid = $(this).parent().attr('data-course-id');
        
        if(menuitem != "frontpage") {
            $("#wrapper  div[data-type]").hide();
            $("#wrapper  div[data-course-id*='"+courseid+"'][data-type='data-group']").show();
            $("#wrapper  div[data-course-id*='"+courseid+"'][data-type='"+menuitem+"']").show();
        } else {
            $("#wrapper  div[data-course-id*='"+courseid+"']:not('.moodbile-courses')").show();
        }

        if(opts != null) {
            opts(courseid);
        }
        
        return false;
    });
    
    //Event to attach ids in secondary-menu when access in course
    $('.moodbile-course a').live('click', function() {
        var id = $(this).parent().attr('data-course-id'), nav = $('nav#toolbar #secondary-menu li');
        
        $.each(nav, function(){
            $(this).attr('data-course-id', id);
            
            $('nav#toolbar #main-menu').hide();
            $('nav#toolbar #secondary-menu').show();
        });
        
        $('nav#toolbar #secondary-menu li#frontpage').addClass('active');
    });
}