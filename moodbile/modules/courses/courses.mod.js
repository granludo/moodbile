Moodbile.modules.courses = {
    'status': {
        'dataLoaded': false
    },
    'menu': {
        'itemName': 'Courses',
        'mainItem': true,
        'secondaryItem': false
    },
    'dependency' : null,
    'initBehavior' : function(context) {
            var context = context || document;
    
            var checkUserVariable = setInterval(function() {
                if(Moodbile.user != null) {
                    clearInterval(checkUserVariable);
                    var userids = [Moodbile.user.id];
                    var petitionOpts = {"wsfunction":"moodle_course_get_courses_by_userid", "userids": userids};
                    Moodbile.json(context,  petitionOpts, Moodbile.modules.courses.auxFunc.jsonCallback, true);
                }
            }, Moodbile.intervalDelay);
    
            $('.moodbile-course a').live('click', function(){
                var id = $(this).parent().attr('id');
        
                $('section:visible').hide();
                $('.frontpage-'+id).show().children().show();
                
                $('.moodbile-course-name').hide();
        
                return false;
            });
    },
    'depBehavior' : null,
    'auxFunc' : {
        'jsonCallback' : function(data) {
        //Aqui se cargan los cursos
            var callback = function() {
                var itemHTML = $('#wrapper').find('.moodbile-courses:eq(0)').html();
                
                $.each(data, function(){
                    var currentItem = $('#wrapper').find('.moodbile-courses:eq(0)');
            
                    currentItem.append(itemHTML);
                    currentItem.find('.moodbile-course:last-child').attr('id', this.id);
                    currentItem.find('.moodbile-course:last-child').find('.course-title').attr('title', this.shortname).append(this.fullname);
                    currentItem.find('.moodbile-course:last-child').find('.info').find('.fullname').append(this.shortname);
                    currentItem.find('.moodbile-course:last-child').find('.info').find('.summary').append(this.summary);
            
                    Moodbile.enroledCourses[this.id] = {'fullname': this.fullname, 'shortname': this.shortname };
                });
        
                $('.moodbile-course:first-child').remove();
        
                Moodbile.modules.courses.auxFunc.loadFrontpage();
            }
            Moodbile.cloneTemplate('courses', '#wrapper', callback);
    
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
        },
        'loadFrontpage' : function(){
                for (courseid in Moodbile.enroledCourses) {
                    Moodbile.cloneTemplate('frontpage', '#wrapper');
                    $('.moodbile-frontpage:last-child').addClass('frontpage-'+courseid);
                }
        
                Moodbile.modules.courses.status.dataLoaded = true;
        }
    }        
}