//TODO: Terminar de pulir
Moodbile.behaviors.breadcrumb = function(context){
    //Preparando breadcrumb
    $('nav#breadcrumb').hide();
}

Moodbile.events.breadcrumbCourses = $('.moodbile-courses a').live('click', function(){
    var courseid = $(this).parent().attr('id'), item = $(this).find('div.moodbile-course-fullname').text();
        
    if($('nav#breadcrumb li:eq(1)').is('#level-1') == false) {
        $('nav#breadcrumb ul').append('<li id="level-1"><span><a href="#" class="'+courseid+'">'+item+'</a></span></li>');
        $('nav#breadcrumb li a').show();
    } else {
        $('#level-1 a').text(item).removeClass().addClass(courseid).show();
    }
        
    $('nav#breadcrumb').show();
});
    
    //Acciones cuando se pulsa un link del breadcrumb
Moodbile.events.breadcrumbEvents = $('nav#breadcrumb li a').live('click', function(){
    if($(this).parent().is('#home') == false){
        var courseid = $(this).attr('class');
                
        $('nav#toolbar').find('.active').removeClass('active');
        $('nav#breadcrumb li:eq(2)').remove();
        $('.frontpage-'+courseid).children().show();
    } else {
        $('nav#breadcrumb, nav#toolbar #secondary-menu').hide();
        $('#wrapper').children().hide();
        $('nav#toolbar #main-menu, .moodbile-courses, .moodbile-event-course').show();
        $('nav#toolbar #main-menu').find("li[data-menu-item='courses']").addClass('active');
    }
        
    return false;
});