//TODO: Cambiar estructura de datos

Moodbile.behaviorsPatterns.resources = function(context){
    var context = context || document;
    
    var loadInterval = setInterval(function(){
        if(Moodbile.enroledCoursesid.length != 0) {
            Moodbile.aux.resources(context, Moodbile.enroledCoursesid);
            clearInterval(loadInterval);
        }
    }, Moodbile.intervalDelay);
    
    //una vez pulsamos el curso
    $('nav#toolbar li#resources a').live('click', function(){
        var id = $(this).parent().attr('class');
        id = id.split(' ');
        id = id[0];
        
        $('section:visible').hide();
        $('.resources-'+id).show();
        
        return false;
    });
}

Moodbile.aux.resources = function(context, ids){
    $.each(ids, function(){
        $('#wrapper').append('<section class="resources-'+ this +'"></section>');
        $('.resources-'+this).hide();
    });
    
    var requestName = 'resources';
    var op = "resources";
    var petition = Moodbile.json(context, requestName, op, Moodbile.templates.resources);
}

Moodbile.templates.resources = function(json){
    $.each(json, function(i, json){
        var courseid = json.courseid;
        var resource = json.resource;
        
        $('#wrapper .resources-'+courseid).append('<div class="' + resource.id + '"><a href="#"><span class="icon-'+resource.type+'"></span>' + resource.title + '</a><div class="info collapsed"></div></div>');
        $('#wrapper .resources-'+courseid).find('.'+resource.id).find('.info').append('<div class="more visible"><a href="#" class="collapsible"><span class="icon-info"></span></a></div>');
        $('#wrapper .resources-'+courseid).find('.'+resource.id).find('.info').append('<div class="description">'+resource.description+'</div>');
    });
}