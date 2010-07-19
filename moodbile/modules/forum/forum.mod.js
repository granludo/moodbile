Moodbile.modules.forum = {};

Moodbile.modules.forum.status = {
    'dataLoaded': false
};

Moodbile.modules.forum.menu = {
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
};

Moodbile.modules.forum.notification = {
    'method' : 'custom',
    'data'   : function () {
        
    }
};

Moodbile.modules.forum.dependency = 'course';

Moodbile.modules.forum.initBehavior = null;

Moodbile.modules.forum.depBehavior = function(context) {
    var context = context || document;
    var data = Moodbile.processedData['forum'];
        
    if(data) {
        Moodbile.modules.forum.auxFunc.loadForums(data);
    }
};

Moodbile.modules.forum.auxFunc = {};

Moodbile.modules.forum.auxFunc.loadForums = function(data){
    var courseid = null, coursename = null, currentItem = null;
            
    $.each(data, function(){
        forumid = this.instance;
        courseid = this.course;
        coursename = Moodbile.enroledCourses[courseid].fullname;
                
        currentItem = "#wrapper div.moodbile-course-data[data-course-id='"+courseid+"']";
        Moodbile.cloneTemplate('forum', currentItem);
                
        //miramos si hay el titulo del curso creado
        currentItem = $(currentItem).find('.moodbile-forum:last-child');
        currentItem.attr({'data-forum-id': forumid, 'data-course-id': courseid, 'data-type': 'forum'});
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
};

Moodbile.modules.forum.auxFunc.loadDiscussions = function (data) {
    var forumid = data[0].searchparam, discussions = data[0].discussions, discussionid = null;
    var currentPost = null, str = null, title = null;
    var selector = "#templates .moodbile-discussions[data-forum-id='"+forumid+"']";
            
    if ( $(selector).length == 0 ) {
        $.each(discussions, function() {
            discussionid = this.discussion;
                
            if($(selector).length == 0) {
                Moodbile.cloneTemplate('discussions:first', '#templates');
                $('.moodbile-discussions:last').attr('data-forum-id', forumid);
            } else {
                Moodbile.cloneTemplate('discussion:first', "#templates .moodbile-discussions[data-forum-id='"+forumid+"']");
            }
                
            currentPost = $(selector +" .moodbile-discussion:last");
            currentPost.attr({'data-discussion-id': discussionid, 'data-forum-id': forumid});
            currentPost.find('.moodbile-avatar').css({'background-image' : 'url('+Moodbile.userAvatarUrl(this.userid)+')'});
            currentPost.find('.moodbile-discussion-link').addClass('arrow');
            currentPost.find('.moodbile-discussion-link .moodbile-discussion-title').append(this.name);

            str = Moodbile.t('lastModified'); 
            str += this.firstname+" "+this.lastname +" - ";
            str += Moodbile.time.getDateTime(this.timemodified);
            
            currentPost.find('.moodbile-discussion-link .moodbile-discussion-autor').append(str);
        });
    }
            
    //Next, copy html an show discussion inside infor-viewer
    opts = {
        'title': Moodbile.t('Forum'),
        'subtitle' : $("#wrapper div[data-forum-id='"+ forumid +"'][data-type='forums'] .moodbile-forum-title").text(),
        'id' : forumid
    };
    str = $(".moodbile-discussions[data-forum-id='"+forumid+"']").html();
    extraButton = [{
        'buttonName' : 'newDiscussion',
        'buttonStr'  : Moodbile.t('New discussion'),
        'eventName'  : 'extraEvents'
    }];
            
    Moodbile.infoViewer.show(opts, 'forum-'+ forumid, str, extraButton, null);
};

Moodbile.modules.forum.auxFunc.loadPosts = function (data) {
    var discussionid = data[0].discussionid, posts = data[0].posts, postid = null;
    var currentPost = null, _str = null, title = null;
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
            currentPost.attr({'data-post-id': postid, 'data-type' : 'post'});
            currentPost.find('.moodbile-avatar').css({'background-image' : 'url('+Moodbile.userAvatarUrl(this.userid)+')'});
            currentPost.find('.moodbile-post-link').attr('data-user-id', this.userid).addClass('arrow');
            currentPost.find('.moodbile-post-link .moodbile-post-title').append(this.subject);
            
            _str = Moodbile.t('lastModified')+" "+this.firstname+" "+this.lastname +" - "+Moodbile.time.getDateTime(this.modified);
            currentPost.find('.moodbile-post-link .moodbile-post-autor').append(_str);
            
            if (this.userid == Moodbile.user.id) {
                _str = '<button class="edit-in-place"><span class="moodbile-icon icon-edit">';
                _str += Moodbile.t('Edit');
                _str += '</span></button>';
                currentPost.find('a:last').after(_str);
                currentPost.find('.moodbile-post-msg').addClass('editable');
            }
            
            currentPost.find('.moodbile-post-msg').append(this.message);
        });
    }
            
    //Next, copy html an show discussion inside infor-viewer
    title = $("#container .moodbile-discussion[data-discussion-id='"+discussionid+"'] .moodbile-discussion-title").text();
    title = {
        'title': Moodbile.t('Discussion'),
        'subtitle' : title,
        'id' : discussionid
    };
    str = $(".moodbile-posts[data-discussion-id='"+discussionid+"']").html();
    extraButton = [{
        'buttonName' : 'newPost',
        'buttonStr'  : Moodbile.t('New post'),
        'eventName'  : 'extraEvents'
    }];
            
    Moodbile.infoViewer.show(title, 'discussion-'+discussionid+'-posts', str, extraButton, null);
    
    //attach essential for edit in place
    Moodbile.editInPlace.options.post = {
        'callback' : function () {
            var _selector = "div[data-"+ Moodbile.editInPlace.context +"-id='"+ Moodbile.editInPlace.id +"']:visible";
            var _subject = $(_selector).find('.moodbile-post-title').text();
            var _message = $(_selector).find('textarea').val();
            var _petition = {};
            
            //renueva contenido en objecto
            Moodbile.editInPlace.content = _message;
            
            //rellenara las opciones necesarias
            _petition.name = 'editInPlace', 
            _petition.wsfunction = 'moodle_forum_update_posts';
            _petition.context    = {
                'posts' : [{
                    'postid'  : Moodbile.editInPlace.id,
                    'subject' : _subject,
                    'message' : _message
                }]
            };
            _petition.callback = function (data) {
                var _editable = $(_selector).find('.editable');

                _editable.html(Moodbile.editInPlace.content);
            };
            _petition.cache = false;
            
            //completamos la peticion
            Moodbile.editInPlace.options.post.petition = _petition;
        },
        'petition' : {}
    }
    
};

//Auxiliar function to load Form
Moodbile.modules.forum.auxFunc.loadForm = function (id, type, subject) {
    var selector = "#container section.moodbile-info-viewer div.moodbile-info-viewer-wrapper:last div.moodbile-extra-options";
    
    $(selector).children().remove();
    Moodbile.cloneTemplate('new-discussion', selector);
            
    //Labels
    $("div.moodbile-new-discussion label[for='subject']").text(Moodbile.t('Subject'));
    $("div.moodbile-new-discussion label[for='discussion-message']").text(Moodbile.t('Message'));

    //Inputs
    $("div.moodbile-new-discussion input#subject").val(subject);
            
    //submit
    $("div.moodbile-new-discussion button[type='submit']").attr({'data-submit-type': type, 'data-send-id': id});
    $("div.moodbile-new-discussion button[type='submit']").html(Moodbile.t('Submit')+'<span class=""/>');
};

Moodbile.events.extraEvents = $('button.newDiscussion, button.newPost, a.reply-this-post').live('click', function() {
    var id = null, subject = null, type = null;
    var selector = "#container section.moodbile-info-viewer div.moodbile-info-viewer-wrapper:last";
    id = $(selector).attr('data-infoviewer-id');
      
    if ( $(this).is('button.newDiscussion') ) {
    
        type = 'newDiscussion';
        
    } else if ( $(this).is('button.newPost') ) {
    
        id = $(selector +" div.moodbile-post:first").attr('data-post-id');
        type = 'newPost';
        subject = "Re: " + $(selector +" div.moodbile-post:first").find("div.moodbile-post-title").text();
    
    } else {
        
        id = $(this).parent().attr('data-post-id');
        type = 'replyThisPost';
        subject = "Re: " + $(this).parent().find('div.moodbile-post-title').text();
    
    }
            
    if ( $(selector +' div.moodbile-extra-options').length == 0 ) {
        
        $(selector +' .moodbile-info-view-title').after('<div class="moodbile-extra-options" />');
    }
            
    $(selector +' div.moodbile-extra-options').toggle();
    
    Moodbile.modules.forum.auxFunc.loadForm(id, type, subject);
            
    return false;
});
//Evento al pulsar un foro
Moodbile.events.getDiscussion = $('div.moodbile-forum a.moodbile-forum-title').live('click', function(){
    var forumid = $(this).parent().attr('data-forum-id');    
    var discussions = [{'criteria':'forumid', 'searchparam':forumid}];
    var petitionOpts = {
        'name'       : 'getDiscussions', 
        'wsfunction' : 'moodle_forum_get_discussions',
        'context'    : { 'discussions': discussions },
        'callback'   : Moodbile.modules.forum.auxFunc.loadDiscussions,
        'cache'      : false
    };
        
    Moodbile.json(petitionOpts);
            
    return false;
});
        
//Evento al pulsar una discusion
Moodbile.events.getPostFromDiscussion = $('a.moodbile-discussion-link').live('click', function() {
    var discussionid = $(this).parent().attr('data-discussion-id');
            
    var posts = [{'id': discussionid}];
    var petitionOpts = {
        'name'       : 'getPostFromDiscussions', 
        'wsfunction' : 'moodle_forum_get_posts_from_discussion',
        'context'    : { 'discussions': posts },
        'callback'   : Moodbile.modules.forum.auxFunc.loadPosts,
        'cache'      : false
    };
        
    Moodbile.json(petitionOpts);
        
    return false;
});
        
Moodbile.events.create = $("button[type='submit']").live('click', function () {
    var submitType = $(this).attr('data-submit-type');
    var id = $(this).attr('data-send-id');
    var subject = $("#info-viewer div.moodbile-discussion-subject").find("input#subject").val();
    var message = $("#info-viewer div.moodbile-discussion-message").find("textarea#discussion-message").val();
    var selector = "#container section.moodbile-info-viewer div.moodbile-info-viewer-wrapper:last";
            
    if ( submitType === 'newDiscussion' ) {
        var petitionOpts = {
            'name'       : 'getPostFromDiscussions', 
            'wsfunction' : 'moodle_forum_create_discussions',
            'context'    : { 'discussions': 
                [{
                    'forumid': id,
                    'name'   : subject,
                    'intro'  : message
                }]
            },
            'cache'      : false,
            'callback'   : function(data){
                data = data[0];
                    
                //Solucionar cuando en el foro no hay discusiones
                Moodbile.cloneTemplate('discussion:first', selector +' .moodbile-discussion:first', 'before');

                var disSelector = selector +" .moodbile-discussion:first";
                $(disSelector).attr({'data-discussion-id': data.id, 'data-forum-id': data.forumid});
                $(disSelector).find('.moodbile-avatar').css({'background-image' : 'url('+Moodbile.userAvatarUrl(Moodbile.user.id)+')'});
                $(disSelector).find('.moodbile-discussion-link').addClass('arrow');
                $(disSelector).find('.moodbile-discussion-link .moodbile-discussion-title').append(data.name);
                
                var str = Moodbile.t('lastModified') +" ";
                str += Moodbile.user.firstname+" "+Moodbile.user.lastname;
                str += " - "+ Moodbile.actualDate.toLocaleTimeString();
                $(disSelector).find('.moodbile-discussion-link .moodbile-discussion-autor').append(str);
            }
        }
    } else {
        var discussionid = $(selector).attr('data-infoviewer-id');
        var petitionOpts = {
            'name'       : 'getPostFromDiscussions', 
            'wsfunction' : 'moodle_forum_create_posts',
            'context'    : { 'posts': 
                [{
                    'discussionid': discussionid,
                    'parent'      : id,
                    'subject'     : subject,
                    'message'     : message
                }]
            },
            'cache'      : false,
            'callback'   : function(data){
                data = data[0];
                
                //Solucionar cuando en el foro no hay discusiones
                Moodbile.cloneTemplate('post:first', selector+' .moodbile-post:last', 'after');

                var postSelector = $(selector+" .moodbile-post:last");
                postSelector.attr('data-post-id', data.id);
                postSelector.find('.moodbile-avatar').css({'background-image' : 'url('+Moodbile.userAvatarUrl(Moodbile.user.id)+')'});
                postSelector.find('.moodbile-post-link').attr('data-user-id', Moodbile.user.id).addClass('arrow');
                postSelector.find('.moodbile-post-link .moodbile-post-title').append(data.subject);
                
                var str = Moodbile.t('lastModified') +" ";
                str += Moodbile.user.firstname+" "+Moodbile.user.lastname;
                str += " - "+ Moodbile.actualDate.toLocaleTimeString();
                postSelector.find('.moodbile-post-link .moodbile-post-autor').append(str);
                postSelector.find('.moodbile-post-msg').append(data.message);
            }
        }
    }

    if (subject && message) {
        //TODO: check data
        Moodbile.json(petitionOpts);
                
        $('#info-viewer div.moodbile-extra-options').toggle();
        $('#info-viewer div.moodbile-extra-options').children().remove();
    }
        
    return false;
});