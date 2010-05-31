Moodbile.modules.forum = {
    'status': {
        'dataLoaded': false
    },
    'menu': {
        'itemName': 'Forums',
        'mainItem': true,
        'secondaryItem': true
    },
    'dependency' : 'courses',
    'initBehavior' : null,
    'depBehavior' : function(context) {
        var context = context || document;
        var data = Moodbile.processedData['forum'];
        
        if(data) {
            Moodbile.modules.forum.auxFunc.loadForums(data);
        }
        
        //Evento al pulsar un foro
        $('.moodbile-frontpage .moodbile-forum .moodbile-forum-title').live('click', function(){
            var forumid = $(this).parent().attr('class');
            var forumid = forumid.split(' ');
            forumid = forumid[3];
            
            if(Moodbile.modules.forum.status.dataLoaded) {
                var l = $('.moodbile-posts.forum-'+forumid);
                var title = $(this).text();
                var html = "";
                $.each(l, function(){
                    html += $(this).html();
                });
            
                Moodbile.infoViewer(title, 'forum-'+forumid, html);
            }
            
            return false;
        });
        
        //Evento al pulsar una discusion
        $('.moodbile-post .moodbile-post-link').live('click', function(){
            

            var postTitle = $(this).find('.moodbile-post-title').text();
        
            //TODO: Alguna funcion que carge temes al gusto de los botones
            var replybutton = '<button id="replyButton" class="moodbile-post-reply-button">'+Moodbile.t('Reply')+'</button><div id="replyPost" style="display:none;"><textarea id="textareaReply"/><button id="sendReply">'+Moodbile.t('Send')+'</button></div>';
            var replyes = $(this).parent().find('.moodbile-post-replyes').html();
            var content = replybutton+replyes;
        
            Moodbile.infoViewer(postTitle, "post", content);
        
            return false;
        });
        
        //Eventos cuando se pulsa el boton de contestar y el de enviar
        $('#replyButton').live('click', function(){
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
    },
    'auxFunc' : {
        'loadForums' : function(data){
            $.each(data, function(){
                var courseid = this.course;
            
                if($('.frontpage-'+courseid).find('.moodbile-forums').length == 0) {
                    Moodbile.cloneTemplate('forums', '.frontpage-'+courseid);
                } else {
                    Moodbile.cloneTemplate('forum', '.frontpage-'+courseid+' .moodbile-forums');
                }
                    
                $('.frontpage-'+courseid).children().hide();
                $('.frontpage-'+courseid+' .moodbile-forums').find('a.moodbile-course-name').text(Moodbile.enroledCourses[courseid].fullname);

            
                var currentItem = $('.frontpage-'+courseid).find('.moodbile-forum:last-child');
            
                currentItem.addClass(this.instance);
                currentItem.find('.moodbile-forum-title').append(this.name).addClass('arrow');
                currentItem.find('.moodbile-icon').addClass('icon-'+this.modname);
                currentItem.find('.info').find('.description').append(this.intro);
                
                Moodbile.modules.forum.auxFunc.loadDiscussions(this.discussions);
            });  
        },
        'loadDiscussions' : function(data){
        
            $.each(data, function() {
                var discussionid = this.id;
                var forumid = this.forum;
                //var date = new Date();
                //date.setTime(this.timemodified);
            
                if($('#templates').find('.moodbile-posts.'+discussionid).length == 0) {
                    Moodbile.cloneTemplate('posts:first', '#templates');
                    $('.moodbile-posts:last').addClass(discussionid.toString()+' forum-'+forumid.toString());
                } else {
                    Moodbile.cloneTemplate('post', '.#templates .moodbile-posts.'+discussionid);
                }
                
                var currentPost = $('.moodbile-posts.'+discussionid+' .moodbile-post:last-child');
            
                currentPost.find('.moodbile-avatar').addClass('avatar').css({'background-image' : 'url('+Moodbile.userAvatarUrl(this.userid)+')'});
                currentPost.find('.moodbile-post-link').addClass('arrow');
                currentPost.find('.moodbile-post-link').find('.moodbile-post-title').append(this.name);
                
                currentPost.find('.moodbile-post-link').find('.moodbile-post-autor').append(Moodbile.t('lastModified') + ' ');
                currentPost.find('.moodbile-post-link').find('.moodbile-post-autor').append(this.firstname+' '+this.lastname + ' - ');
                //currentPost.find('.moodbile-post-link').find('.moodbile-post-autor').append(date);

                currentPost.find('.moodbile-post-replyes').children().remove();
                
                var posts = this.posts;
                $.each(posts, function(){
                
                    Moodbile.cloneTemplate('posts:first .moodbile-post-reply', '.moodbile-posts.'+discussionid+' .moodbile-post-replyes');
                    var currentReply = $('.moodbile-posts.'+discussionid+' .moodbile-post-reply:last');
                
                    currentReply.find('.user').addClass(this.userid+' arrow');
                    currentReply.find('.moodbile-avatar').addClass('avatar').css({'background-image' : 'url('+Moodbile.userAvatarUrl(this.userid)+')'});
                    currentReply.find('.moodbile-post-reply-title').append(this.subject);
                    currentReply.find('.moodbile-post-reply-autor').append(this.firstname +' '+ this.lastname);
                    currentReply.find('.moodbile-post-reply-msg').html(this.message);
                });
            });
        
            Moodbile.modules.forum.status.dataLoaded = true;
        }
    }        
}