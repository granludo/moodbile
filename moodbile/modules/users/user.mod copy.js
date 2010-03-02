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
        var op = "profile";
        Moodbile.json(context, 'user', op, Moodbile.templates.user);
        $('#content, #toolbar').hide();
    });
}

Moodbile.templates.user = function (json) {
    var title = 'User Profile';
    var content = '<div class="user"><span class="avatar"></span>'+json.name +' '+ json.lastname+'</div>';
    content += '<div class="email"><span class="icon-email">Correo electrónico</span><a href="mailto:'+ json.email +'">'+ json.email +'</a></div>';
    content += '<div class="city"><span class="icon-city">Ciudad</span>'+json.city+'</div>';
    //alert(json.email);
    var courses = json.courses;
    content += '<div class="courses-list"><span class="icon-courses">Cursos</span><dl>';
    $.each(courses, function() {
        content += '<dd>' + this + '</dd>';
    });
    content += '</dl></div>';
    content += '<div class="roles"><span class="icon-roles">Roles</span>'+ json.roles +'</div>';
    
    Moodbile.aux.infoViewer(title, "user", content);
    $('#info-viewer').find('.avatar').css({'background-image' : 'url('+json.avatar+')'});
}