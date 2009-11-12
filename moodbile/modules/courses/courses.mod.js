Moodbile.behaviorsPatterns.courses = function(context){
    var context = context || document;
    
    $('#wrapper').append('<section class="courses"></section>');
    
    $.getJSON("dummie/ws.dum.php?jsoncallback=courses", {op: 0}, courses = function(json){
        $.each(json, function(i, json){
            $('#wrapper .courses').append('<div class="course '+ json.format +'"><a href="#" id="' + json.id + '">' + json.title + '</a></div>');
        });
    });
    
    $('.course a').live('click', function(){
        var id = $(this).attr('id');
        var format = $(this).parent().attr('class');
        format = format.split(' ');
        format = format[1];
        //alert(format);
        
        Moodbile.aux.frontpage(id);
    });
    
    //funcion para el caso de pulsar el icono de navegacion
    $('nav#toolbar li#courses a').live('click', function(){
        //$('section:visible').hide();
        $('section.courses').show();
        
        return false;
    });
}

Moodbile.aux.frontpage = function(id){
        //alert('hello');
        $('section:visible').hide();
        
        //Toca comprobar si existe o no la seccion de recursos de un id dado
        if($('#wrapper').find('.frontpage-'+id).is('.frontpage-'+id)) {
        
            $('#wrapper .frontpage-'+id).show();
        
        } else {
            $('#wrapper').append('<section class="frontpage-'+id+' dragy"></section>');
            
            //Pedimos datos sobre el curso
            $.getJSON("dummie/ws.dum.php?jsoncallback=courses", {op: 0, courseid: id}, courses = function(json){
            
                $.each(json.sections, function(i, data){
                    $('#wrapper .frontpage-'+id).append('<div id="' + i + '"><div class="label">' + data.label + '</div></div>');
                });
            });
            
            //Pedimos recursos
            $.getJSON("dummie/ws.dum.php?jsoncallback=resources", {op: 1}, resources = function(json){
                $.each(json, function(i, json){
                    $('#wrapper .frontpage-'+id).find('#'+json.section).append('<div class="' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
                });        
            });
            
            $.getJSON("dummie/ws.dum.php?jsoncallback=events", {op: 6}, events = function(json){
                $.each(json, function(i, json){
                    $('#wrapper .frontpage-'+id).find('#'+json.section).append('<div class="' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
                });        
            });
            
            $.getJSON("dummie/ws.dum.php?jsoncallback=forums", {op: 3}, forums = function(json){
                $.each(json, function(i, json){
                    $('#wrapper .frontpage-'+id).find('#'+json.section).append('<div class="forum ' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
                });        
            });

        } 
}