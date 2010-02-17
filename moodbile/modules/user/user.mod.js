Moodbile.behaviorsPatterns.getUserProfile = function(context){
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
    var title = Moodbile.t('Profile');
    var content = '<div class="user"><span class="avatar"></span><span>'+json.name +' '+ json.lastname+'</span></div>';
    content += '<div class="email"><span class="icon-email">Correo electrónico</span><span><a href="mailto:'+ json.email +'">'+ json.email +'</a></span></div>';
    content += '<div class="city"><span class="icon-city">Ciudad</span><span>'+json.city+'</span></div>';

    var courses = json.courses;
    content += '<div class="courses-list"><span class="icon-courses">Cursos</span><span><dl>';
    $.each(courses, function() {
        content += '<dd>' + this + '</dd>';
    });
    content += '</dl></span></div>';
    content += '<div class="roles"><span class="icon-roles">Roles</span><span>'+ json.roles +'</span></div>';
    
    Moodbile.aux.infoViewer(title, "user", content);
    $('#info-viewer').find('.avatar').css({'background-image' : 'url('+json.avatar+')'});
}

Moodbile.behaviorsPatterns.getProfile = function(context){
    var context = context || document;
    
    $('#user-profile').css({'background-image':'url('+Moodbile.user.avatar+')'});
    $('#user-profile').live('click', function(){
        var userid = Moodbile.user.id;
        
        var op = [];
        op["op"] = "profile"
        op["userid"] = userid;
        
        Moodbile.json(context, "user", op, Moodbile.templates.user);
    });
}