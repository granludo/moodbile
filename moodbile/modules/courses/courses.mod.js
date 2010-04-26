Moodbile.behaviorsPatterns.courses = function(context){
    var context = context || document;
    
    var checkUserVariable = setInterval(function() {
        if(Moodbile.user != null) {
            clearInterval(checkUserVariable);
            var userids = [Moodbile.user.id]
            var petitionOpts = {"wsfunction":"moodle_course_get_courses_by_userid", "userids": userids};
            Moodbile.json(context,  petitionOpts, Moodbile.jsonCallbacks.courses, true);
        }
    }, Moodbile.intervalDelay);
    
    $('.moodbile-course a').live('click', function(){
        var id = $(this).parent().attr('id');
        
        $('section:visible').hide();
        $('.frontpage-'+id).show().children().show();
        
        return false;
    });
}

Moodbile.jsonCallbacks.courses = function(data) {
    //Aqui se cargan los cursos
    var callback = function(){
        var itemHTML = $('#wrapper .moodbile-courses-links').find('.moodbile-courses:eq(0)').html();
        
        $.each(data, function(i, data){
            var currentItem = $('#wrapper .moodbile-courses-links').find('.moodbile-courses:eq(0)');
            
            currentItem.append(itemHTML);
            currentItem.find('.moodbile-course:last-child').attr('id', data.id).addClass(data.format);
            currentItem.find('.moodbile-course:last-child').find('.course-title').attr('title', data.shortname).append(data.shortname);
            currentItem.find('.moodbile-course:last-child').find('.info').find('.fullname').append(data.fullname);
            currentItem.find('.moodbile-course:last-child').find('.info').find('.summary').append(data.summary);
            
            Moodbile.enroledCoursesid[i] = data.id;
        });
        
        $('.moodbile-course:first-child').remove();
        
        Moodbile.aux.loadFrontpage();
    }
    Moodbile.loadTemplate('courses', '#wrapper', callback);
    
    //Process data
    $.each(data, function(){
        var modules = this.modules;
            
        $.each(modules, function(){
            var modname = this.modname;
            
            if(Moodbile.processedData[modname] == null) {
                Moodbile.processedData[modname] = [];
            }
            
            Moodbile.processedData[modname].push(this);
        });
    });
}

Moodbile.aux.loadFrontpage = function() {
    Moodbile.frontpageLoaded = null;
    
    var frontpageCallback = function() {
        $.each(Moodbile.enroledCoursesid, function() {
            $('#templates .moodbile-frontpage').clone().appendTo('#wrapper');
            $('#wrapper .moodbile-frontpage:last-child').addClass('frontpage-'+ this);
            $('.frontpage-'+this).hide(); 
        });
        
        Moodbile.frontpageLoaded = true;
    }
    
    Moodbile.loadTemplate('frontpage', '#templates', frontpageCallback);
}