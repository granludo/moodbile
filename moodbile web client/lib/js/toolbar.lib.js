Moodbile.toolbarOpts = {
    //Type of optional Events are functions, all them inside an object and sorted by menuitem. 
    'mainMenu' : {},
    'secondaryMenu': {
        'frontpage': function () {
            $('.moodbile-course-name, .moodbile-event-date, .moodbile-event-course').hide();
        }
    }
};

Moodbile.behaviors.toolbar = function(context){
    if(Moodbile.isLoged()) {
    
        var itemName = null, modname = modname;
        var mainItem = null, mainItemOpts = null;
        var secondaryItem = null, secondaryItemOpts = null;
        var _aux = null;
        
        $('nav#toolbar').show();
        
        //main menu
        $('nav#toolbar').append('<ul id="main-menu" class="moodbile-toolbar"/>');
        
        //secondary menu
        $('nav#toolbar').append('<ul id="secondary-menu" class="moodbile-toolbar"/>');
        
        _aux = $('nav#toolbar #secondary-menu');
        _aux.hide();
        _aux.append('<li data-menu-item="frontpage"><a href="#">'+ Moodbile.t('Frontpage') +'</a></li>');
        
        //Make toolbar
        $.each(Moodbile.modules, function(module) {
        
            itemName = this.menu.itemName, modname = module;
            mainItem = this.menu.mainItem, mainItemOpts = this.menu.mainItemOpts;
            secondaryItem = this.menu.secondaryItem, secondaryItemOpts = this.menu.secondaryItemOpts;
            
            _aux = $('nav#toolbar #main-menu');
            if(mainItem) {
                _aux.append('<li data-menu-item="'+ modname +'"><a href="#">'+ Moodbile.t(itemName) +'</a></li>');
                
                if(modname == "course") {
                    _aux.find("li[data-menu-item='"+ modname +"']").addClass('active');
                }
                
                //attach optional function referenced to mainItem
                if (mainItemOpts) {
                    Moodbile.toolbarOpts.mainMenu[modname] = mainItemOpts;
                }
            }
            
            _aux = $('nav#toolbar #secondary-menu');
            if(secondaryItem) {
                _aux.append('<li data-menu-item="'+ modname +'"><a href="#">'+ Moodbile.t(itemName) +'</a></li>');
                
                //attach optional function referenced to secondaryItem
                if (secondaryItemOpts) {
                    Moodbile.toolbarOpts.secondaryMenu[modname] = secondaryItemOpts;
                }
            }
        });
    }
}

Moodbile.events.activeSection = $('nav#toolbar li a').live('click', function(){
    $('nav#toolbar').find('.active').removeClass('active');
    $(this).parent().addClass('active');
});

Moodbile.events.toolbarMainMenu = $('nav#toolbar #main-menu li a').live('click', function(){
    var menuitem = $(this).parent().attr('data-menu-item');
    var opts = Moodbile.toolbarOpts.mainMenu[menuitem];
        
    $("#wrapper div[data-type]").hide();
    $("#wrapper div[data-type='"+menuitem+"']").show().parent().show();
        
    if(opts) {
        opts();
    }
        
    return false;
});
    
//Secondary menu events
Moodbile.events.toolbarSecondaryMenu = $('nav#toolbar #secondary-menu li a').live('click', function(){
    var menuitem = $(this).parent().attr('data-menu-item');
    var opts = Moodbile.toolbarOpts.secondaryMenu[menuitem];
    var courseid = $(this).parent().attr('data-course-id');
        
    if(menuitem != "frontpage") {
        $("#wrapper  div[data-type]").hide();
        $("#wrapper  div[data-course-id*='"+courseid+"'][data-type='data-group']").show();
        $("#wrapper  div[data-course-id*='"+courseid+"'][data-type='"+menuitem+"']").show();
    } else {
        $("#wrapper  div[data-course-id*='"+courseid+"']:not('.moodbile-course')").show();
    }

    if(opts) {
        opts(courseid);
    }
        
    return false;
});
    
    //Event to attach ids in secondary-menu when access in course
Moodbile.events.toolbarCourses = $('.moodbile-course a').live('click', function() {
    var id = $(this).parent().attr('data-course-id'), nav = $('nav#toolbar #secondary-menu li');
        
    $.each(nav, function(){
        $(this).attr('data-course-id', id);
            
        $('nav#toolbar #main-menu').hide();
        $('nav#toolbar #secondary-menu').show();
    });
        
    $("nav#toolbar #secondary-menu li[data-menu-item='frontpage']").addClass('active');
});