/*Moodbile.behaviorsPatterns.participants = function(context){
    var context = context || document;
    
    setTimeout(function(){
        Moodbile.aux.participants(context, Moodbile.enroledCoursesid);
    }, 700);
    
    $('nav#toolbar li#user').live('click', function(){
        var id = $(this).attr('class');
        id = id.split(' ');
        id = id[0];
        
        $('section:visible').hide();
        $('.grade-'+id).show();
        
        return false;
    });
}

Moodbile.aux.participants = function(context, courseids) {
    $.each(courseids, function(){
        $('#wrapper').append('<section class="participants-'+ this +'"></section>');
        $('.participants-'+ this).hide();
    });
    
    var op = 2;
    Moodbile.jsonRequest(context, op, Moodbile.templates.participants);
}

Moodbile.templates.participants = function(json){
    $.each(json, function(i, json){
        var courseid = json.courseid;
        var grades = json.grades;
        
        $('#wrapper .grade-'+ courseid).append('<h6>'+json.name+' '+json.lastname+'</h6>');
        
        $.each(grades, function(i, grades){
            $('#wrapper .grade-'+ courseid).append('<div class="' + grades.id + '"><a href="#"><span class="icon-'+grades.type+'"></span>' + grades.title + '</a><div class="info collapsed"></div></div>');
            $('#wrapper .grade-'+ courseid).find('.' + grades.id).find('.info').append('<span class="more visible"><a href="#" class="collapsible"><span class="icon-info"></span></a></span>');
            $('#wrapper .grade-'+ courseid).find('.' + grades.id).find('.info').append('<div class="data">Calificación: <em>'+ grades.grade +'</em></div>');
            $('#wrapper .grade-'+ courseid).find('.' + grades.id).find('.info').append('<div class="description">'+ grades.description +'</div>');
        });
    });
}*/

Moodbile.behaviorsPatterns.user = function(context){
    var context = context || document;
    
    $('.user').live('click', function(){
        var op = 7;
        $('#content').hide();
        Moodbile.aux.loading(true);
        Moodbile.jsonRequest(context, op, Moodbile.templates.user);
        
        return false;
    });
}

Moodbile.templates.user = function (json) {
    var avatar = '<div class="avatar"><img alt="'+ json.name +' '+ json.lastname +'" src="'+ json.avatar +'" /></div>';
    var user = '<div class="user"><h6>'+ json.name +' '+ json.lastname +'<h6></div>';
    var email = '<div class="email"><a href="mailto:'+ json.email +'">'+ json.email +'</a></div>';
    
    var courses = json.courses;
    var courseslist = '<ul class="courses-list">';
    $.each(courses, function() {
        courseslist = courseslist + '<li>' + this + '</li>';
    });
    courseslist = courseslist + '</ul>';
    
    var html = avatar + user + email + courseslist;
    Moodbile.aux.infoViewer(html);
    Moodbile.aux.loading(false);
}