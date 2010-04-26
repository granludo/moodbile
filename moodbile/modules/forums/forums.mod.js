Moodbile.behaviorsPatterns.forums = function(context){
    var context = context || document;
    
    var loadInterval = setInterval(function(){
        var data = Moodbile.processedData['forum'];
        
        if(data && Moodbile.frontpageLoaded) {
            clearInterval(loadInterval);
            Moodbile.aux.forums(data);
        }
    }, Moodbile.intervalDelay);


}
Moodbile.aux.forums = function(data){
    var callback = function() {
        $.each(data, function(){
            var courseid = this.course;
            
            if($('.frontpage-'+courseid).find('.moodbile-forums').length == 0) {
                $('#templates .moodbile-forums').clone().appendTo('.frontpage-'+courseid);
            } else {
                $('#templates .moodbile-forums').find('.moodbile-forum').clone().appendTo('.frontpage-'+courseid+' .moodbile-forums');
            }
            
            var currentItem = $('.frontpage-'+courseid).find('.moodbile-forum:last-child');
            
            currentItem.addClass(this.id);
            currentItem.find('.moodbile-forum-title').append(this.name).addClass('arrow');
            currentItem.find('.moodbile-icon').addClass('icon-'+this.modname);
            currentItem.find('.info').find('.description').append(this.intro);
        });
    }
    Moodbile.loadTemplate('forums', '#templates', callback);
}

Moodbile.behaviorsPatterns.posts = function(context){
    var context = context || document;
        
    $('.moodbile-frontpage .moodbile-forum .moodbile-forum-title').live('click', function(){
        var forumclass = $(this).parent().attr('class');
        var forumid = forumclass.split(' ');
        forumid = forumid[1];

        $('.moodbile-posts').remove();
                
        var petitionOpts = {'wsfunction':'posts'};
        Moodbile.json(context, petitionOpts, Moodbile.jsonCallbacks.post, false);
            
        return false;
    });
}

Moodbile.jsonCallbacks.post = function(json) {
    var callback = function() {
        var itemHTML = $('.moodbile-posts:eq(0)').html(); //One post html
        var replyHTML = $('.moodbile-posts:eq(0)').find('.moodbile-post-replyes').html();
        
        $.each(json, function(i, json){
            var forumid = json.forumid;
            var postid = json.id;
            
            $('.moodbile-posts').append(itemHTML);
            
            var currentPost = $('.moodbile-post:last-child');
            
            currentPost.addClass('post-'+postid);
            currentPost.find('.moodbile-avatar').addClass('avatar').css({'background-image' : 'url('+json.avatar+')'});
            currentPost.find('.moodbile-post-title').addClass('arrow').append(json.title);
            
            var currentReplyes = $('.moodbile-post:last-child').find('.moodbile-post-replyes');
            
            currentReplyes.find('.moodbile-post-reply:first-child').addClass('reply-'+json.id);
            currentReplyes.find('.moodbile-post-reply:first-child').find('.user').addClass(json.userid+' arrow');
            currentReplyes.find('.moodbile-post-reply:first-child').find('.avatar').css({'background-image' : 'url('+json.avatar+')'});
            currentReplyes.find('.moodbile-post-reply:first-child').find('.moodbile-post-reply-title').append(json.title);
            currentReplyes.find('.moodbile-post-reply:first-child').find('.moodbile-post-reply-autor').append(json.name +' '+ json.lastname );
            currentReplyes.find('.moodbile-post-reply-msg').html(json.msg);
                    
            var reply = json.replyes;
            $.each(reply, function(i, reply){
                $('.moodbile-post:last-child').find('.moodbile-post-replyes').append(replyHTML);
                
                var currentReply = $('.moodbile-post:last-child').find('.moodbile-post-reply:last-child');
                
                currentReply.addClass('reply-'+reply.id);
                currentReply.find('.user').addClass(reply.userid+' arrow');
                currentReply.find('.moodbile-avatar').addClass('avatar').css({'background-image' : 'url('+reply.avatar+')'});
                currentReply.find('.moodbile-post-reply-title').append(reply.title);
                currentReply.find('.moodbile-post-reply-autor').append(reply.name +' '+ reply.lastname);
                currentReply.find('.moodbile-post-reply-msg').html(reply.msg);
            });
        });
        
        $('.moodbile-post:first-child').remove();
        
        var html = $('.moodbile-posts').html();
        Moodbile.infoViewer('Forums', 'post', html);
    }
    Moodbile.loadTemplate('posts', '#templates', callback);
}

Moodbile.behaviorsPatterns.replyes = function(){
    //Funcion para los replies
    $('.moodbile-post .moodbile-post-title').live('click', function(){
            
        var currentPost = $(this).parent().parent().attr('class');
        currentPost = currentPost.split(' ');
        currentPost = currentPost[1];

        var postTitle = $(this).text();
        
        //TODO: Alguna funcion que carge temes al gusto de los botones
        var replybutton = '<button id="replyButton" class="moodbile-post-reply-button">'+Moodbile.t('Reply')+'</button><div id="replyPost" style="display:none;"><textarea id="textareaReply"/><button id="sendReply">'+Moodbile.t('Send')+'</button></div>';
        
        var replyes = $('.'+currentPost).find('.moodbile-post-replyes').html();
        
        var content = replybutton+replyes;
        
        Moodbile.infoViewer(postTitle, "post", content);
        
        return false;
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
