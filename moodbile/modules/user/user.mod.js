Moodbile.behaviorsPatterns.user = function(context){
    var context = context || document;
    
    $('.user').live('click', function(){
        var userid = $(this).attr('class');
        userid = userid.split(' ');
        userid = userid[1];
        
        var op = [];
        op["op"] = "profile"
        op["userid"] = userid;
        
        Moodbile.json(context, "user", op, Moodbile.templates.user);
    });
}

Moodbile.templates.user = function (json) {
    var title = 'User Profile';
    var content = '<div class="user"><span class="avatar"></span>'+json.name +' '+ json.lastname+'</div>';
    content += '<div class="email"><span class="icon-email">Correo electrónico</span><a href="mailto:'+ json.email +'">'+ json.email +'</a></div>';
    content += '<div class="city"><span class="icon-city">Ciudad</span>'+json.city+'</div>';

    var courses = json.courses;
    content += '<div class="courses-list"><span class="icon-courses">Cursos</span><dl>';
    $.each(courses, function() {
        content += '<dd>' + this + '</dd>';
    });
    content += '</dl></div>';
    content += '<div class="roles"><span class="icon-roles">Roles</span>'+ json.roles +'</div>';
    
    $('#content, #toolbar').hide();
    Moodbile.aux.infoViewer(title, "user", content);
    $('#info-viewer').find('.avatar').css({'background-image' : 'url('+json.avatar+')'});
}