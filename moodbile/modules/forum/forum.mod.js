Moodbile.modules.forum = {
    'status': {
        'dataLoaded': false
    },
    'menu': {
        'itemName': 'Forums',
        'mainItem': true,
        'mainItemOpts': function () {
            $('.moodbile-event-date').hide();
            $('a.moodbile-course-name').show();
            Moodbile.filter.hideFilter();
        },
        'secondaryItem': true,
        'secondaryItemOpts': function (courseid) {
            $(".moodbile-event-date").hide();
        }
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
        Moodbile.events['getDiscussion'] = $('div.moodbile-forum a.moodbile-forum-title').live('click', function(){
            var forumid = $(this).parent().attr('data-forum-id'), discussions = null;
            
            discussions = [{'criteria':'forumid', 'searchparam':forumid}];
            var petitionOpts = {
                'name'       : 'getDiscussions', 
                'wsfunction' : 'moodle_forum_get_discussions',
                'context'    : { 'discussions': discussions },
                'callback'   : Moodbile.modules.forum.auxFunc.loadDiscussions
            }
            Moodbile.json(petitionOpts);
            
            return false;
        });
        
        //Evento al pulsar una discusion
        Moodbile.events['getPostFromDiscussion'] = $('a.moodbile-discussion-link').live('click', function() {
            var discussionid = $(this).parent().attr('data-discussion-id'), posts = null;
            
            posts = [{'id': discussionid}];
            var petitionOpts = {
                'name'       : 'getPostFromDiscussions', 
                'wsfunction' : 'moodle_forum_get_posts_from_discussion',
                'context'    : { 'discussions': posts },
                'callback'   : Moodbile.modules.forum.auxFunc.loadPosts
            }
            Moodbile.json(petitionOpts);
        
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
            var courseid = null, coursename = null, currentItem = null;
            
            $.each(data, function(){
                forumid = this.instance;
                courseid = this.course;
                coursename = Moodbile.enroledCourses[courseid].fullname;
                
                currentItem = "#wrapper div.moodbile-course-data[data-course-id='"+courseid+"']";
                Moodbile.cloneTemplate('forum', currentItem);
                
                //miramos si hay el titulo del curso creado
                currentItem = $(currentItem).find('.moodbile-forum:last-child');
                currentItem.attr({'data-forum-id': forumid, 'data-course-id': courseid, 'data-type': 'forums'});
                currentItem.find('.moodbile-forum-title').append(this.name).attr('title', this.name).addClass('arrow');
                currentItem.find('.moodbile-forum-title').find('.moodbile-icon').addClass('icon-'+this.modname);
                
                if (this.intro != "") {
                    currentItem.find('details .intro').append(this.intro);
                } else {
                    currentItem.find('a, details').hide();
                }
                
                //Ocultamos el contenido de recursos
                $('#wrapper').find('a.moodbile-course-name, .moodbile-forums').hide();
            });
            
            Moodbile.modules.forum.status.dataLoaded = true;
        },
        'loadDiscussions' : function (data) {
            var forumid = data[0].searchparam, discussions = data[0].discussions, discussionid = null;
            var currentPost = null, str = null, title = null;
            var selector = "#templates .moodbile-discussions[data-forum-id='"+forumid+"']";
            
            if($(selector).length == 0) {
                $.each(discussions, function() {
                    discussionid = this.discussion;
                
                    if($(selector).length == 0) {
                        Moodbile.cloneTemplate('discussions:first', '#templates');
                        $('.moodbile-discussions:last').attr('data-forum-id', forumid);
                    } else {
                        Moodbile.cloneTemplate('discussion:first', "#templates .moodbile-discussions[data-forum-id='"+forumid+"']");
                    }
                
                    currentPost = $(selector +" .moodbile-discussion:last");
                    currentPost.attr('data-discussion-id', discussionid);
                    currentPost.find('.moodbile-avatar').css({'background-image' : 'url('+Moodbile.userAvatarUrl(this.userid)+')'});
                    currentPost.find('.moodbile-discussion-link').addClass('arrow');
                    currentPost.find('.moodbile-discussion-link .moodbile-discussion-title').append(this.subject);
                
                    str = Moodbile.t('lastModified')+" "+this.firstname+" "+this.lastname +" - "+Moodbile.time.getDateTime(this.timemodified);
                    currentPost.find('.moodbile-discussion-link .moodbile-discussion-autor').append(str);
                });
            }
            
            //Next, copy html an show discussion inside infor-viewer
            title = {
                'title': Moodbile.t('Forum'),
                'subtitle' : $("#wrapper div[data-forum-id='"+forumid+"'][data-type='forums'] .moodbile-forum-title").text()
            };
            str = $(".moodbile-discussions[data-forum-id='"+forumid+"']").html();
            
            Moodbile.infoViewer(title, 'discussions-'+discussionid, str);
        },
        'loadPosts' : function (data) {
            Moodbile.test = data;
            var discussionid = data[0].discussionid, posts = data[0].posts, postid = null;
            var currentPost = null, str = null, title = null;
            var selector = "#templates .moodbile-posts[data-discussion-id='"+discussionid+"']";
            
            if($(selector).length == 0) {
                $.each(posts, function() {
                    postid = this.id;
                    
                
                    if($(selector).length == 0) {
                        Moodbile.cloneTemplate('posts:first', '#templates');
                        $('.moodbile-posts:last').attr('data-discussion-id', discussionid);
                    } else {
                        Moodbile.cloneTemplate('post:first', "#templates .moodbile-posts[data-discussion-id='"+discussionid+"']");
                    }
                
                    currentPost = $(selector +" .moodbile-post:last");
                    currentPost.attr('data-post-id', postid);
                    currentPost.find('.moodbile-avatar').css({'background-image' : 'url('+Moodbile.userAvatarUrl(this.userid)+')'});
                    currentPost.find('.moodbile-post-link').attr('data-user-id', this.userid).addClass('arrow');
                    currentPost.find('.moodbile-post-link .moodbile-post-title').append(this.subject);
                
                    str = Moodbile.t('lastModified')+" "+this.firstname+" "+this.lastname +" - "+Moodbile.time.getDateTime(this.modified);
                    currentPost.find('.moodbile-post-link .moodbile-post-autor').append(str);
                    currentPost.find('.moodbile-post-msg').append(this.message);
                });
            }
            
            //Next, copy html an show discussion inside infor-viewer
            title = {
                'title': Moodbile.t('Discussion'),
                'subtitle' : $("#templates .moodbile-discussion[data-discussion-id='"+discussionid+"'] .moodbile-discussion-title").text()
            };
            str = $(".moodbile-posts[data-discussion-id='"+discussionid+"']").html();
            
            Moodbile.infoViewer(title, 'discussion-'+discussionid+'-posts', str);
        }
    }        
}