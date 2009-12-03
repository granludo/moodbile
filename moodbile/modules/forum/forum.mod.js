Moodbile.behaviorsPatterns.forum = function(){
    $('nav#toolbar li#forum').live('click', function(){
        var id = $(this).attr('class');
        id = id.split(' ');
        id = id[0];
        
        $('section:visible').hide();
        $('.posts').remove();
        
        //Toca comprobar si existe o no la seccion de recursos de un id dado
        if($('#wrapper').find('.forums-'+id).is('.forums-'+id)) { //FUNCIONA OK!
        
            $('#wrapper .forums-'+id).show();
        
        } else {
        
            $('#wrapper').append('<section class="forums-'+id+'"></section>');
            $('.forums-'+id).hide();
            Moodbile.aux.loading(true);
            
            $.getJSON("dummie/ws.dum.php?jsoncallback=forums", {op: 3}, forums = function(json){
                $.each(json, function(i, json){
                    $('#wrapper .forums-'+id).append('<div class="forum ' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
                });
                $('.forums-'+id).show();
                Moodbile.aux.loading(false);        
            });
        }
        
    return false;
    });
}

Moodbile.behaviorsPatterns.posts = function(){
        
        $('.forum a').live('click', function(){
            
            //var forumbox = $(this).parent().parent().attr('class');
            var forumclass = $(this).parent().attr('class');
            var forumid = forumclass.split(' ');
            forumid = forumid[1];
            
            
            $('section:visible').hide();
            $('.posts:visible').hide();
            
            if($(this).parent().find('.posts').is('.posts-'+forumid) == false) {
                
                $('#wrapper').append('<section class="posts posts-'+forumid+'"></section>');
                $('.posts-'+forumid).hide();
                Moodbile.aux.loading(true);
            
                $.getJSON("dummie/ws.dum.php?jsoncallback=posts", {op: 4}, posts = function(json){
                    $.each(json, function(i, json){
                        $('.posts-'+forumid).append('<div class="post ' + json.id + '"><a href="#">' + json.title + '<em>'+json.author+'</em></a></div>');
                        //alert(i);
                    });
                    $('.posts-'+forumid).show();
                    Moodbile.aux.loading(false);        
                });
            } else { 
                $(this).next().show();
            }
            
            return false;
    });
}

Moodbile.behaviorsPatterns.replyes = function(){}
        //Funcion para los replies
        $('.posts').find('.post').find('a').live('click', function(){
            
            var postid = $(this).parent().attr('class');
            postid = postid.split(' ');
            postid = postid[1];
                        
            $('.replyes:visible').hide();
            
            if($(this).parent().parent().find('.replyes').is('.'+postid) == false){
            
                $(this).parent().after('<div class="replyes '+postid+'"></div>');
                $('.replyes.'+postid).hide();
                Moodbile.aux.loading(true);
            
                $.getJSON("dummie/ws.dum.php?jsoncallback=post", {op: 5, postid: postid}, post = function(json){
                    $('.replyes.'+postid).append('<div class="msg-' + json.id + '">'+ json.msg +'</div>');
                    
                    var reply = json.replyes;
                    $.each(reply, function(i, reply){
                        $('.replyes.'+postid).append('<div class="reply-' + reply.id + '">'+ reply.msg +'<em>'+reply.author+'</em></div>');
                    //alert(i);
                    });
                    $('.replyes.'+postid).show();
                    Moodbile.aux.loading(false);         
                });
            } else {
                $('.replyes.'+postid).show();
            }
            
            return false;
        });
