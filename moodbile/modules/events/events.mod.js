Moodbile.modules.events = {
    'status': {
        'dataLoaded': false
    },
    'menu': {
        'itemName': 'Events',
        'mainItem': true,
        'secondaryItem': true
    },
    'dependency' : 'courses',
    'initBehavior' : null,
    'depBehavior' : function(context) {
        var context = context || document;
        var data = Moodbile.processedData['assignment'];
        
        if(data) {
            Moodbile.modules.events.auxFunc.loadEvents(data);
        }
    },
    'auxFunc' : {
        'loadEvents' : function(data){
            $.each(data, function(){
                var courseid = this.course;
            
                if($('.frontpage-'+courseid).find('.moodbile-events').length == 0) {
                    Moodbile.cloneTemplate('events', '.frontpage-'+courseid);
                } else {
                    Moodbile.cloneTemplate('event', '.frontpage-'+courseid+' .moodbile-events');
                }
                    
                $('.frontpage-'+courseid).children(':not(h2)').hide();
            
                var currentItem = $('.frontpage-'+courseid).find('.moodbile-event:last-child');
        
                currentItem.addClass(this.id);
                currentItem.find('.moodbile-event-title').append(this.name).addClass('arrow');
                currentItem.find('.moodbile-event-title').find('.moodbile-icon').addClass('icon-assignment');
                currentItem.find('.info').find('.moodbile-course-shortname').text(Moodbile.enroledCourses[courseid].shortname);
                currentItem.find('.info').find('.description').append(this.intro);
            });
                
            Moodbile.modules.events.status.dataLoaded = true;
        }
    }        
}
/*
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
            Moodbile.infoViewer(title, "event", content);
        }, false);
        
        return false;
    });
}
*/