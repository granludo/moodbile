Moodbile.behaviorsPatterns.courses = function(context){
    var context = context || document;
    
    $('#wrapper').append('<div class="courses-links"></div>');
    $('#wrapper').find('.courses-links').append('<section class="courses"></section>');
    
    var op = "courses";
    Moodbile.json(context, 'courses', op, Moodbile.templates.courses);
    
    //Es necesario esperar un tiempo hasta que se complete el request inicial para 
    var loadFrontpage = setInterval(function(){
        if (Moodbile.requestJson.courses != null){
            clearInterval(loadFrontpage);
            Moodbile.aux.frontpage(context, Moodbile.enroledCoursesid);
        }
    }, Moodbile.intervalDelay);
    
    $('.course a').live('click', function(){
        var id = $(this).parent().attr('id');
        
        $('section:visible').hide();
        $('.frontpage-'+id).show();
        
        return false;
    });
}

Moodbile.templates.courses = function(json) {
    $.each(json, function(i, json){
        $('#wrapper .courses-links').find('.courses').append('<div id="' + json.id + '" class="course '+ json.format +' arrow"><a title="'+ json.title +'" href="#" class="course-title">' + json.title + '</a><div class="info collapsed"></div></div>');
        $('#'+json.id).find('.info').append('<div class="more visible"><a href="#" class="collapsible"><span class="icon-info"/></a></div>');
        $('#'+json.id).find('.info').append('<div class="summary">'+json.summary+'</div>');
            
        Moodbile.enroledCoursesid[i] = json.id;
    });
}

Moodbile.aux.frontpage = function(context, ids){
    var context = context || document;
    
    $.each(ids, function(){
        $('#wrapper').append('<section class="frontpage-'+ this +'"></section>');
        $('.frontpage-'+ this).hide();
    });
    
   var loadFrontpage = setInterval(function(){
        if(Moodbile.requestJson.courses != null && Moodbile.requestJson.resources != null && Moodbile.requestJson.events != null && Moodbile.requestJson.forums != null) {
            clearInterval(loadFrontpage);
            
            Moodbile.templates.frontpage(Moodbile.requestJson.courses);
            Moodbile.templates.frontpageResources(Moodbile.requestJson.resources);
            Moodbile.templates.frontpageEvents(Moodbile.requestJson.events);
            Moodbile.templates.frontpageForums(Moodbile.requestJson.forums);
        }
    }, Moodbile.intervalDelay);
}

Moodbile.templates.frontpage = function(json){
    var expanded = 1;
    
    $.each(json, function(i, json){
        $.each(json.sections, function(i, data){
            if (expanded == 1){
                $('#wrapper .frontpage-'+json.id).append('<div class="'+ data.sectionid +' expanded"><div class="summary visible"><a href="#" class="collapsible">' + data.summary + '<span class="collapse-icon"></span></a></div></div>');
                expanded += 1;
            } else {
                $('#wrapper .frontpage-'+json.id).append('<div class="' + data.sectionid + ' collapsed"><div class="summary visible"><a href="#" class="collapsible">' + data.summary + '<span class="collapse-icon"></span></a></div></div>');
            }
        });
    });
}

Moodbile.templates.frontpageResources = function(json){
    $.each(json, function(i, json){
        $('#wrapper .frontpage-'+json.courseid).find('.'+json.resource.section).append('<div class="resource ' + json.resource.id + ' fx"><a href="#"><span class="icon-'+json.resource.type+'"></span>' + json.resource.title + '</a></div>');
    }); 
}

Moodbile.templates.frontpageEvents = function(json){
    $.each(json, function(i, json){
        $('#wrapper .frontpage-'+json.courseid).find('.'+json.section).append('<div class="event ' + json.id + ' fx"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
    });
}

Moodbile.templates.frontpageForums = function(json){
    $.each(json, function(i, json){
        $('#wrapper .frontpage-'+json.courseid).find('.'+json.section).append('<div class="forum ' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
    });
}