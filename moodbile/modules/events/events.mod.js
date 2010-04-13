Moodbile.behaviorsPatterns.events = function(context){
    var context = context || document;
    
    var petitionOpts = {'wsfunction':'events'};
    var loadInterval = setInterval(function(){
        if(Moodbile.enroledCoursesid.length != 0){
            Moodbile.json(context, petitionOpts, Moodbile.jsonCallbacks.events, true);
            clearInterval(loadInterval);
        }
    }, Moodbile.intervalDelay);

}

Moodbile.behaviorsPatterns.eventViewMoreInfo = function(context){
    var context = context || document;
    
    $('.moodbile-event a').live('click', function(){
        var id = $(this).parent().attr('class');
        id = id.split(' ');
        id = id[1];
        
        var title = $(this).text();
        
        var petitionOpts = {'wsfunction':'event', 'eventid': id};
        Moodbile.json(context, petitionOpts, function(json){
            var content = json.description;
            Moodbile.aux.infoViewer(title, "event", content);
        }, false);
    });
}

Moodbile.jsonCallbacks.events = function(json){
    var callback = function(){
        var itemHTML = $('.moodbile-events:eq(0)').html();
        
        $.each(Moodbile.enroledCoursesid, function(){
            var id = this.toString();
            
            $('.moodbile-events:eq(0)').clone().appendTo('#wrapper');
            
            var sectionsLength = $('.moodbile-events').length-1;
            $('.moodbile-events:eq('+sectionsLength+')').addClass('events-'+ id);
            $('.events-'+ id).hide();
        });

        $.each(json, function(i, json) {
            var courseid = json.courseid;
        
            $('#wrapper .events-'+courseid).append(itemHTML);
            
            var currentItem = $('#wrapper .events-'+courseid).find('.moodbile-event:last-child');
        
            currentItem.addClass(json.id +' fx');
            currentItem.find('.moodbile-event-title').append(json.title).addClass('arrow');
            currentItem.find('.moodbile-event-title').find('.moodbile-icon').addClass('icon-'+json.type);
            currentItem.find('.info').find('.date').append(''+Moodbile.t('enddate')+': <em>'+ json.enddata +'</em>');
            currentItem.find('.info').find('.description').append(json.description);
        });
        
        $('.moodbile-event:first-child').remove();
        $('.moodbile-events:visible').remove();
    }
    
    Moodbile.loadTemplate('events', '#wrapper', callback);
}