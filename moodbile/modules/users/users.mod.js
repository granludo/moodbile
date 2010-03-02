Moodbile.behaviorsPatterns.getUserProfile = function(context){
    var context = context || document;
    
    $('.user').live('click', function(){
        var userid = $(this).attr('class');
        userid = userid.split(' ');
        userid = userid[1];
        
        var op = [];
        op["op"] = "profile"
        op["userid"] = userid;
        
        Moodbile.json(context, op["op"], op, Moodbile.templates.userProfile);
    });
}

Moodbile.templates.userProfile = function (json) {
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

Moodbile.behaviorsPatterns.users = function(context){
    var context = context || document;
    
    var loadInterval = setInterval(function(){
        if(Moodbile.enroledCoursesid.length != 0){
            Moodbile.aux.users(context, Moodbile.enroledCoursesid); 
            clearInterval(loadInterval);
        }
    }, Moodbile.intervalDelay);

}

Moodbile.aux.users = function(context, courseids) {
    $.each(courseids, function(){
        $('#wrapper').append('<div class="users-'+ this +'"></div>');
        Moodbile.aux.userFilter(this);
        $('.users-'+ this).append('<section class="users"></section>');
        $('.users-'+ this).hide();
    });
    
    var requestName = 'users';
    var op = 'users';
    Moodbile.json(context, requestName, op, Moodbile.templates.users);
    Moodbile.aux.addUserFilterEvents();
}

Moodbile.templates.users = function(json) {
    $.each(json, function(i, json){
        var courseid = json.courseid;
        
        $('#wrapper .users-'+courseid).find('.users').append('<div class="user user-'+json.id+'"><span class="avatar"></span><a href="#" class="user '+json.id+' fx arrow">'+json.name+' '+json.lastname+'</a></div>');
        $('#wrapper .users-'+courseid).find('.users').find('.user-'+json.id).find('.avatar').css({'background-image' : 'url('+json.avatar+')'});

    });
}

Moodbile.aux.userFilter = function(courseid) {
    $('.users-'+courseid).append('<section id="users-filter"><input id="user-filter-input" type="text"/><button id="user-filter-clear">X</button><span id="mached-num"/></section>');
    $('#user-filter-input').val('User filter');
    $("#user-filter-clear").hide();
    $("#mached-num").hide();
}

Moodbile.aux.addUserFilterEvents = function() {
    $("#user-filter-input").keyup( function() {
        var inputValue = $(this).val();
        var courseid = $(this).parent().parent().attr('class');
        courseid = courseid.split('-');
        courseid = courseid[1];
    
        $('.users-'+courseid+' .users .user').hide();
        $(".users-"+courseid+" .users .user:contains('"+inputValue+"')").show();
        
        var machedNum = $(".users-"+courseid+" .users div:visible").length;
        
        if (machedNum === 0) {
            $(".msg-empty").show();
        } else if (machedNum === 1) {
            $(".msg-empty").hide();
            $(".users-"+courseid+" .users div:visible:first-child").css({"border-bottom":"0"});
        } else {
            $(".msg-empty").hide();
            $(".users-"+courseid+" .users div:first-child").css({"border-bottom":"1px solid #999999"});
            $(".users-"+courseid+" .users div:gt("+machedNum+")").css({"border-bottom":"0"});
        }
        
        if($(this).val() != "") {
            $("#mached-num").text(machedNum).show();
            $("#user-filter-clear").show();
        } else {
            $("#mached-num").hide();
            $("#user-filter-clear").hide();
        }
        
    });
    
    $("#user-filter-input").focus( function() {
        if($(this).val() == "User filter") {
            $(this).val('');
        }
        
        var courseid = $(this).parent().parent().attr('class');
        courseid = courseid.split('-');
        courseid = courseid[1];
        
        $("#user-filter-clear").show();
        $(".users-"+courseid+" .users").append('<em class="msg msg-empty">No se encontraron resultados</em>');
        $(".msg-empty").hide();
    });
    /*$("#user-filter-input").blur( function() {
        if($(this).val() == "") {
            $(this).val('User filter');
        } else {
            $(".msg-empty").remove();
        }
    });*/
    
    $("#user-filter-clear").live('click', function(){
        $("#user-filter-input").val('');
        $(".users-"+courseid+" .users .user:contains").show();
        
        return false;
    });
}