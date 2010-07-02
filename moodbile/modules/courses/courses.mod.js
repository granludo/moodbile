Moodbile.modules.courses = {};

Moodbile.modules.courses.status = {
        'dataLoaded': false
};

Moodbile.modules.courses.menu = {
    'itemName': 'Courses',
    'mainItem': true,
    'mainItemOpts': function () {
            $('.moodbile-course-name, .moodbile-event-date').hide();
            Moodbile.filter.hideFilter();
        },
    'secondaryItem': false
};

Moodbile.modules.courses.dependency = null;

Moodbile.modules.courses.initBehavior = function(context) {
    var context = context || document;
    
    var checkUserVariable = setInterval(function() {
        if(Moodbile.user != null) {
            clearInterval(checkUserVariable);

            var userids = [Moodbile.user.id];
            var petitionOpts = {
                'name'       : 'getCourses', 
                'wsfunction' : 'moodle_course_get_courses_by_userid',
                'context'    : { 'userids': userids },
                'callback'   : Moodbile.modules.courses.auxFunc.jsonCallback
            }
            Moodbile.json(petitionOpts);
        }
    }, Moodbile.intervalDelay);
    
    $('.moodbile-course a').live('click', function(){
        var courseid = $(this).parent().attr('data-course-id');
        
        $("#wrapper div[data-course-id]").hide();
        $("#wrapper div[data-course-id*='"+courseid+"']:not('.moodbile-courses')").show();
        Moodbile.toolbarOpts.secondaryMenu.frontpage();
        
        return false;
    });
};

Moodbile.modules.courses.depBehavior = null;

Moodbile.modules.courses.auxFunc = {};
Moodbile.modules.courses.auxFunc.jsonCallback = function(data) {
    //Aqui se cargan los cursos
    var currentCourse = null, courseid = null, courseidClass = null, coursename = null;
    var modules = null, modname = null, str = null;
        
    Moodbile.courses = data;
    Moodbile.enroledCourses = []; //Array donde dentro se guardan los ids de los cursos del cual el usuario esta enrolado
    Moodbile.processedData = {}; //Array con datos JSON ya procesados o filtrados
            
    $.each(data, function() {
        courseid = this.id;
        coursename = this.fullname;
            
        modules = this.modules;
        $.each(modules, function(){
            modname = this.modname;
            
            if(Moodbile.processedData[modname] == null) {
                Moodbile.processedData[modname] = [];
            }
            
            Moodbile.processedData[modname].push(this);
        });

        Moodbile.enroledCourses[courseid] = {'fullname': coursename, 'shortname': this.shortname, 'summary' : this.summary};

        Moodbile.cloneTemplate('courses', '#wrapper');
                    
        currentCourse = $('.moodbile-course:last-child');
        currentCourse.attr({'data-course-id' : courseid, 'data-type' : 'courses'});
        currentCourse.find('.moodbile-course-title').attr('title', coursename);
        currentCourse.find('.moodbile-course-fullname').append(coursename);
        currentCourse.find('.moodbile-course-shortname').append(this.shortname);
        currentCourse.find('.moodbile-course-title .moodbile-icon').addClass('icon-courses');
        currentCourse.find('details .summary').append(this.summary);
                
        Moodbile.cloneTemplate('course-name:last', '#wrapper');
        $('#wrapper a.moodbile-course-name:last').attr('data-course-id', courseid).addClass(' collapse').text(coursename);
            
        str = '<div class="moodbile-course-data collapsible" data-course-id="'+courseid+'" data-type="data-group"/>';
        $('#wrapper a.moodbile-course-name:last').after(str);
            
    });
            
    $('div.moodbile-course-data').hide();
            
    Moodbile.modules.courses.status.dataLoaded = true;
};