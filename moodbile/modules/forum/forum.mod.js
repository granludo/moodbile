Moodbile.behaviorsPatterns.forum = function(context){
    var context = context || document;
    
    var loadInterval = setInterval(function(){
        if(Moodbile.enroledCoursesid.length != 0) {
            Moodbile.aux.forums(context, Moodbile.enroledCoursesid);
            clearInterval(loadInterval);
        }
    }, Moodbile.intervalDelay);

    $('nav#toolbar li#forum').live('click', function(){
        var id = $(this).attr('class');
        id = id.split(' ');
        id = id[0];
        
        $('section:visible').hide();
        $('.forums-'+id).show();
        $('.posts').remove();
        
        return false;
    });
}

Moodbile.aux.forums = function(context, courseids) {
    $.each(courseids, function(){
        $('#wrapper').append('<section class="forums forums-'+ this +'"></section>');
        $('.forums-'+ this).hide();
    });
    
    var requestName = 'forums';
    var op = "forums";
    Moodbile.json(context, requestName, op, Moodbile.templates.forums);
}

Moodbile.templates.forums = function(json){
    $.each(json, function(i, json){
        var courseid = json.courseid;

        $('#wrapper .forums-'+courseid).append('<div class="forum ' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
    });
}

Moodbile.behaviorsPatterns.posts = function(context){
    var context = context || document;
        
    $('.forum a').live('click', function(){
        var forumclass = $(this).parent().attr('class');
        var forumid = forumclass.split(' ');
        forumid = forumid[1];
            
            
        $('section:visible').hide();
        $('.posts').remove();
            
        if($(this).parent().find('.posts').is('.posts-'+forumid) == false) {
                
            $('#wrapper').append('<section class="posts posts-'+forumid+'"></section>');
            $('.posts-'+forumid).hide();
                
            var requestName = 'posts';
            var op = "posts";
            Moodbile.json(context, requestName, op, Moodbile.templates.post);
            
            $('.posts-'+forumid).show();
                
        } else { 
            $(this).next().show();
        }
            
        return false;
    });
}

Moodbile.templates.post = function(json) {
    $.each(json, function(i, json){
        var forumid = json.forumid;

        $('.posts-'+forumid).append('<div class="post post-' + json.id + '"><div class="avatar"></div><div class="post-title"><a href="#">' + json.title + '</a></div><div class="replyes '+json.id+'"></div></div>');
        $('.posts-'+forumid).find('.avatar').css({'background-image' : 'url('+json.avatar+')'});
        $('.posts-'+forumid).find('.replyes.'+json.id).append('<dialog><dt>'+ json.name +' '+ json.lastname +'</dt><dd class="msg-' + json.id + '">'+ json.msg +'</dd></dialog>');
                    
        var reply = json.replyes;
        $.each(reply, function(i, reply){
            $('.replyes.'+json.id).find('dialog').append('<dt>'+reply.author+'</dt><dd class="reply-' + reply.id + '">'+ reply.msg +'</dd>');
        });
    });
    $('.replyes:visible').hide();
}

Moodbile.behaviorsPatterns.replyes = function(){
    //Funcion para los replies
    $('.post-title').find('a').live('click', function(){
            
        var postid = $(this).parent().parent().attr('class');
        postid = postid.split('-');
        postid = postid[1];
                        
        $('.replyes:visible').hide();
        $('.replyes.'+postid).show();
            
            
        return false;
    });
}