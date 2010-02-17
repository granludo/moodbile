Moodbile.behaviorsPatterns.events = function(context){
    var context = context || document;
    
    var loadInterval = setInterval(function(){
        if(Moodbile.enroledCoursesid.length != 0){
            Moodbile.aux.events(context, Moodbile.enroledCoursesid); 
            clearInterval(loadInterval);
        }
    }, Moodbile.intervalDelay);
    
    $('nav#toolbar li#events').live('click', function(){
        var id = $(this).attr('class');
        id = id.split(' ');
        id = id[0];
        
        $('section:visible').hide();
        $('.events-'+id).show();
        
        return false;   
    });
}

Moodbile.behaviorsPatterns.eventViewMoreInfo = function(context){
    var context = context || document;
    
    $('.event a').live('click', function(){
        var id = $(this).parent().attr('class');
        id = id.split(' ');
        id = id[1];
        
        var title = $(this).text();
        
        var op = [];
        op["op"] = "event";
        op["eventid"] = id;
        
        Moodbile.json(context, "event", op, function(json){
            var content = json.description;
            Moodbile.aux.infoViewer(title, "event", content);
        });
    });
}

Moodbile.aux.events = function(context, courseids) {
    $.each(courseids, function(){
        $('#wrapper').append('<section class="events-'+ this +'"></section>');
        $('.events-'+ this).hide();
    });
    
    var requestName = 'events';
    var op = 'events';
    Moodbile.json(context, requestName, op, Moodbile.templates.events);
}

Moodbile.templates.events = function(json){
    $.each(json, function(i, json){
        var courseid = json.courseid;
        
        $('#wrapper .events-'+courseid).append('<div class="event ' + json.id + ' fx"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a><div class="info collapsed"></div></div>');
        $('#wrapper .events-'+courseid).find('.'+json.id).find('.info').append('<span class="more visible"><a href="#" class="collapsible"><span class="icon-info"></span></a></span>');
        $('#wrapper .events-'+courseid).find('.'+json.id).find('.info').append('<div class="data">Fecha de entrega: <em>'+ json.enddata +'</em></div>');
        $('#wrapper .events-'+courseid).find('.'+json.id).find('.info').append('<div class="description">'+json.description+'</div>');
    });
}