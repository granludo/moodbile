Moodbile.behaviorsPatterns.forum = function(context){
    var context = context || document;
    
    var petitionOpts = {'wsfunction':'forums'};
    var loadInterval = setInterval(function(){
        if(Moodbile.enroledCoursesid.length != 0) {
            Moodbile.json(context, petitionOpts, Moodbile.jsonCallbacks.forums, true);
            clearInterval(loadInterval);
        }
    }, Moodbile.intervalDelay);

}

Moodbile.jsonCallbacks.forums = function(json){
    var callback = function() {
        var itemHTML = $('.moodbile-forums:eq(0)').html();
        
        $.each(Moodbile.enroledCoursesid, function(){
            var id = this.toString();
            
            $('.moodbile-forums:eq(0)').clone().appendTo('#wrapper');
            
            var sectionsLength = $('.moodbile-forums').length-1;
            $('.moodbile-forums:eq('+sectionsLength+')').addClass('forums-'+ id);
            $('.forums-'+ id).hide();
        });
    
        $.each(json, function(i, json){
            var courseid = json.courseid;

            $('#wrapper .forums-'+courseid).append(itemHTML);
            
            var currentItem = $('#wrapper .forums-'+courseid).find('.moodbile-forum:last-child');
            
            currentItem.addClass(json.id.toString());
            currentItem.find('.moodbile-forum-title').append(json.title);
            currentItem.find('.moodbile-icon').addClass('icon-'+json.type);
        });
        
        $('.moodbile-forum:first-child').remove();
        $('.moodbile-forums:visible').remove();
    }
    
    Moodbile.loadTemplate('forums', '#wrapper', callback);
}

Moodbile.behaviorsPatterns.posts = function(context){
    var context = context || document;
        
    $('.moodbile-forum a').live('click', function(){
        var forumclass = $(this).parent().attr('class');
        var forumid = forumclass.split(' ');
        forumid = forumid[1];
            
        $('section:visible').hide();
        $('.moodbile-posts').remove();
                
        var petitionOpts = {'wsfunction':'posts'};
        Moodbile.json(context, petitionOpts, Moodbile.jsonCallbacks.post, false);
            
        return false;
    });
}

Moodbile.jsonCallbacks.post = function(json) {
    var callback = function() {
        var itemHTML = $('.moodbile-posts:eq(0)').html(); //One post html
        var replyHTML = $('.moodbile-posts:eq(0)').find('.moodbile-post-replyes').find('dialog').html();
        
        $.each(json, function(i, json){
            var forumid = json.forumid;
            var postid = json.id;
            
            $('.moodbile-posts').append(itemHTML);
            
            var currentPost = $('.moodbile-post:last-child');
            
            currentPost.addClass('post-'+postid);
            currentPost.find('.avatar').css({'background-image' : 'url('+json.avatar+')'});
            currentPost.find('.moodbile-post-title').find('a').addClass('arrow').append(json.title);
            
            var currentReply = $('.moodbile-post:last-child').find('.moodbile-post-replyes').find('dialog');
            
            currentReply.find('.moodbile-post-reply:first-child').find('.avatar').css({'background-image' : 'url('+json.avatar+')'});
            currentReply.find('.moodbile-post-reply:first-child').find('.moodbile-post-reply-title').append(json.title);
            currentReply.find('.moodbile-post-reply:first-child').find('.user').addClass(json.userid+' fx arrow').append(json.name +' '+ json.lastname );
            currentReply.find('.moodbile-reply-msg').append(json.msg);
                    
            var reply = json.replyes;
            $.each(reply, function(i, reply){
                $('.moodbile-post:last-child').find('.moodbile-post-replyes').find('dialog').append(replyHTML);

                var currentDt = $('.moodbile-post:last-child').find('.moodbile-post-replyes').find('dialog').find('.moodbile-post-reply:last-child');
                var currentDd = $('.moodbile-post:last-child').find('.moodbile-post-replyes').find('dialog').find('.moodbile-post-reply-msg:last-child');
                
                /*currentDt.find('.avatar').css({'background-image' : 'url('+reply.avatar+')'});
                currentDt.find('.moodbile-reply-title').append(reply.title);*/
                currentDt.find('.user').addClass(reply.userid+' fx arrow').append(reply.name +' '+ reply.lastname);
                currentDd.append(reply.msg);
            });
        });
        //$('.moodbile-replyes:visible').hide();
        $('.moodbile-post:first-child').remove();
    }
    Moodbile.loadTemplate('posts', '#wrapper', callback);
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
