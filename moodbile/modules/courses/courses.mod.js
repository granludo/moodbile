Moodbile.behaviorsPatterns.courses = function(context){
    var context = context || document;
    
    $('#wrapper').append('<section class="courses"></section>');
    
    var op = 0;
    
    Moodbile.jsonRequest(context, op, Moodbile.templates.courses);
    
    //Es necesario esperar un tiempo hasta que se complete el request inicial para 
    setTimeout(function(){
        var ncourses = Moodbile.enroledCoursesid.length;
        var loadInterval = setInterval(function(){
            ncourses = ncourses-1;
            Moodbile.aux.frontpage(Moodbile.enroledCoursesid[ncourses]);
            if(ncourses == 0) clearInterval(loadInterval);
        }, 500);
    }, 500);
    
    $('.course a').live('click', function(){
        var id = $(this).parent().attr('id');
        
        $('section:visible').hide();
        $('.frontpage-'+id).show();
        
        return false;
    });
    
    //funcion para el caso de pulsar el icono de navegacion
    $('nav#toolbar li#courses a').live('click', function(){
        $('section:visible').hide();
        $('section.courses').show();
        
        return false;
    });
}

Moodbile.templates.courses = function(json) {
    $.each(json, function(i, json){
        $('#wrapper .courses').append('<div id="' + json.id + '" class="course '+ json.format +'"><a title="'+ json.title +'" href="#" class="course-title">' + json.title + '</a><div class="info collapsed"></div></div>');
        $('#'+json.id).find('.info').append('<div class="more visible"><a href="#" class="collapsible"><span class="icon-info"/></a></div>');
        $('#'+json.id).find('.info').append('<div class="summary">'+json.summary+'</div>');
            
        Moodbile.enroledCoursesid[i] = json.id;
    });
}

Moodbile.aux.frontpage = function(id){
    $('#wrapper').append('<section class="frontpage-'+id+'"></section>');
    $('.frontpage-'+id).hide();
              
    Moodbile.aux.getPetitions(id);
}

Moodbile.aux.getPetitions = function(id) {
    
    $.getJSON("dummie/ws.dum.php?jsoncallback=courses", {op: 0, courseid: id}, courses = function(json) { 
        var expanded = 1;
        $.each(json.sections, function(i, data){
            if (expanded == 1){
                $('#wrapper .frontpage-'+id).append('<div id="' + i + '" class="expanded"><div class="summary visible"><a href="#" class="collapsible">' + data.summary + '<span class="collapse-icon"></span></a></div></div>');
                expanded += 1;
            } else {
                $('#wrapper .frontpage-'+id).append('<div id="' + i + '" class="collapsed"><div class="summary visible"><a href="#" class="collapsible">' + data.summary + '<span class="collapse-icon"></span></a></div></div>');
            }
        });
        
        $.getJSON("dummie/ws.dum.php?jsoncallback=resources", {op: 1}, resources = function(json) {
            $.each(json, function(i, json){
                $('#wrapper .frontpage-'+id).find('#'+json.section).append('<div class="' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
            });
            
            $.getJSON("dummie/ws.dum.php?jsoncallback=events", {op: 6}, events = function(json) {
                $.each(json, function(i, json){
                    $('#wrapper .frontpage-'+id).find('#'+json.section).append('<div class="' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
                });
                
                $.getJSON("dummie/ws.dum.php?jsoncallback=forums", {op: 3}, forums = function(json) {
                    $.each(json, function(i, json){
                        $('#wrapper .frontpage-'+id).find('#'+json.section).append('<div class="forum ' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
                    });
                });
                   
            });      
        });
    });
}