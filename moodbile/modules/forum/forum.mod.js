Moodbile.behaviorsPatterns.forum = function(){
    $('nav#toolbar li#forum').live('click', function(){
        var id = $(this).attr('class');
        id = id.split(' ');
        id = id[0];
        
        $('section:visible').hide();
        
        //Toca comprobar si existe o no la seccion de recursos de un id dado
        if($('#wrapper').find('.forums-'+id).is('.forums-'+id)) { //FUNCIONA OK!
        
            $('#wrapper .forums-'+id).show();
        
        } else {
        
            //$(this).addClass('loaded');
            $('#wrapper').append('<section class="forums-'+id+'"></section>');
            $.getJSON("dummie/ws.dum.php?jsoncallback=forums", {op: 3}, forums = function(json){
                $.each(json, function(i, json){
                    $('#wrapper .forums-'+id).append('<div class="forum ' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
                });        
            });
        }
        
        //Funcion para la llamada a los post
        
        $('.forums-'+id+' .forum a').live('click', function(){
            //$(this).removeClass('open');
            
            
            var forumbox = $(this).parent().parent().attr('class');
            var forumclass = $(this).parent().attr('class');
            //alert(forumid);
            var forumid = forumclass.split(' ');
            forumid = forumid[1];
            
            $('.posts:visible').hide();
            
            //if($(this).is('.open') == false){
            if($(this).parent().find('.posts').is('.'+forumid) == false) {
                //$(this).addClass('open');
                $(this).parent().after('<div class="posts '+forumid+'"></div>');
                $.getJSON("dummie/ws.dum.php?jsoncallback=posts", {op: 4}, posts = function(json){
                    $.each(json, function(i, json){
                        $('.posts.'+forumid).append('<div class="post ' + json.id + '"><a href="#">' + json.title + '<em>'+json.author+'</em></a></div>');
                        //alert(i);
                    });        
                });
            } else { 
                $(this).next().show();
            }
            
            return false;
        });
        
        //Funcion para los replies
        $('.posts').find('.post').find('a').live('click', function(){
            //$(this).removeClass('open');
            //$('.replyes').empty().remove();
            
            var postid = $(this).parent().attr('class');
            postid = postid.split(' ');
            postid = postid[1];
            //alert(forumid);
            
            $('.replyes:visible').hide();
            
            if($(this).parent().parent().find('.replyes').is('.'+postid) == false){
            
                //$(this).addClass('open');
                $(this).parent().after('<div class="replyes '+postid+'"></div>');
                $.getJSON("dummie/ws.dum.php?jsoncallback=post", {op: 5, postid: postid}, post = function(json){
                    $('.replyes.'+postid).append('<div class="msg-' + json.id + '">'+ json.msg +'</div>');
                    
                    var reply = json.replyes;
                    $.each(reply, function(i, reply){
                        $('.replyes.'+postid).append('<div class="reply-' + reply.id + '">'+ reply.msg +'<em>'+reply.author+'</em></div>');
                        //alert(i);
                    });        
                });
            } else {
                $(this).next().show();
            }
            
            return false;
        });
    return false;
    });
}