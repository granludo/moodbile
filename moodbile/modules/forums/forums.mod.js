Moodbile.behaviorsPatterns.forum = function(context){
    var context = context || document;
    
    var loadInterval = setInterval(function(){
        if(Moodbile.enroledCoursesid.length != 0) {
            Moodbile.aux.forums(context, Moodbile.enroledCoursesid);
            clearInterval(loadInterval);
        }
    }, Moodbile.intervalDelay);

}

Moodbile.aux.forums = function(context, courseids) {
    $.each(courseids, function(){
        $('#wrapper').append('<section class="forums forums-'+ this +'"></section>');
        $('.forums-'+ this).hide();
    });
    
    var petitionOpts = {'wsfunction':'forums'};
    Moodbile.json(context, petitionOpts, Moodbile.templates.forums);
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
                
            var petitionOpts = {'wsfunction':'posts'};
            Moodbile.json(context, petitionOpts, Moodbile.templates.post);
            
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

        $('.posts-'+forumid).append('<div class="post post-' + json.id + '"><div class="avatar"></div><div class="post-title fx"><a href="#">' + json.title + '</a></div><div class="replyes '+json.id+'"></div></div>');
        $('.posts-'+forumid).find('.avatar').css({'background-image' : 'url('+json.avatar+')'});
        $('.posts-'+forumid).find('.replyes.'+json.id).append('<dialog><dt><span class="avatar"></span>'+json.title+'<br /><a href="#" class="user '+json.userid+' fx">'+ json.name +' '+ json.lastname +'</a></dt><dd class="msg-' + json.id + '">'+ json.msg +'</dd></dialog>');
                    
        var reply = json.replyes;
        $.each(reply, function(i, reply){
            $('.replyes.'+json.id).find('dialog').append('<dt><span class="avatar"></span>'+reply.title+'<br /><a href="#" class="user '+reply.userid+' fx">'+reply.author+'</a></dt><dd>'+ reply.msg +'</dd>');
            $('.replyes.'+json.id).find('dialog').find('.avatar').css({'background-image' : 'url('+reply.avatar+')'});
        });
    });
    $('.replyes:visible').hide();
}

Moodbile.behaviorsPatterns.replyes = function(){
    //Funcion para los replies
    $('.post-title a').live('click', function(){
            
        var postid = $(this).parent().parent().attr('class');
        postid = postid.split('-');
        postid = postid[1];
        
        var postTitle = $(this).text();
        var replybutton = '<button id="replyButton">'+Moodbile.t('Reply')+'</button><div id="replyPost" style="display:none;"><textarea id="textareaReply"/><button id="sendReply">'+Moodbile.t('Send')+'</button></div>';
        var replyes = $(this).parent().parent().find('.replyes.'+postid).html();
        
        var content = replybutton+replyes;
        
        Moodbile.aux.infoViewer(postTitle, "post", content);
        
    });
}
Moodbile.behaviorsPatterns.sendReply = function(){
    $('#replyButton').live('click', function(){ //Unicamente se realizara una vez.
        $('#replyPost').toggle();
        $('#replyPost').find('textarea').focus();
        
        return false;
    });
            
    $('#sendReply').live('click', function(){

        if($('#textareaReply').val() != "undefined") {
            //Deshabilitaremos el botton para evitar envios inecesarios
            $(this).attr("disabled","disabled").css({"opacity":"0.5"});
                    
            //Enviamos peticion, aqui aprovechare la funcion de peticiones, esta devolvera un estado: enviado/error...
                    
            //Añadiremos la respuesta junto a los otros, asi dara mejor sensacion de continuidad.
            $('dialog').append('<dt><span class="avatar"></span>'+Moodbile.user.name+' '+Moodbile.user.lastname+'</dt><dd>'+$('#textareaReply').val()+'</dd>');
            $('.dialog').find('.avatar').css({'background-image' : 'url('+Moodbile.user.avatar+')'});
        }
        
        return false;
    });
}
