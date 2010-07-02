Moodbile.modules.events = {};

Moodbile.modules.events.status = {
    'dataLoaded': false
};

Moodbile.modules.events.menu = {
    'itemName': 'Events',
    'mainItem': true,
    'mainItemOpts': function () {
        $('.moodbile-course-name').hide();
        $('.moodbile-event-date').show();
           
        var dataToFilter = null, eventsGroupDate = null;
        var filterOpts = {
            'All' : function(){
                $('#wrapper > div.moodbile-events, a.moodbile-event-date').show();
            },
            'Upcoming events' : function() {
                $('#wrapper > div.moodbile-events, a.moodbile-event-date').show();
            
                dataToFilter = "#wrapper div.moodbile-events[data-type='data-group']";
                $.each($(dataToFilter), function(i,v) {
                    eventsGroupDate = new Date($(dataToFilter+':eq('+i+')').attr('data-event-date')).getTime();

                    //If event date is smaller than User last login date, hide
                    if (eventsGroupDate < Moodbile.time.convertTimestamp(Moodbile.user.lastlogin)) {
                        $(dataToFilter+':eq('+i+')').hide().prev().hide();
                    }
                });
            },
            'Past events' : function() {
                $('#wrapper > div.moodbile-events, a.moodbile-event-date').show();
    
                dataToFilter = "#wrapper div.moodbile-events[data-type='data-group']";;
                $.each($(dataToFilter), function(i,v) {
                    eventsGroupDate = new Date($(dataToFilter+':eq('+i+')').attr('data-event-date')).getTime();
                        
                    //If event date is bigger than User last login date, hide
                    if (eventsGroupDate > Moodbile.time.convertTimestamp(Moodbile.user.lastlogin)) {
                        $(dataToFilter+':eq('+i+')').hide().prev().hide();
                    }
                });
            }
        };
        Moodbile.filter.reloadFilter(filterOpts);
    },
    'secondaryItem': true,
    'secondaryItemOpts': function (courseid) {
        $(".moodbile-event-date[data-course-id*='"+courseid+"']").show();
    }
};

Moodbile.modules.events.dependency = 'courses';

Moodbile.modules.events.initBehavior = null;

Moodbile.modules.events.depBehavior = function(context) {
    var context = context || document, data = Moodbile.processedData['assignment'];
    
    if(data) {
        Moodbile.modules.events.auxFunc.loadEvents(data);
    }
};

Moodbile.modules.events.auxFunc = {};
Moodbile.modules.events.auxFunc.loadEvents = function(data){
    var sortedData = [], len = null, courseid = null, courseids = null, coursename = null, currentItem = null;
    var domStr = null, date = null, timeavailable = null, timemodified = null, timedue = null;
            
    //sort data by id (low id -> old event)
    $.each(data, function() {
        sortedData[this.id] = this;
    });
            
    //display data
    $.each(sortedData, function(i, data){
        if(data){
            courseid = data.course;
            coursename = Moodbile.enroledCourses[courseid].fullname;
            date = Moodbile.time.getDate(data.timeavailable);
            timeavailable = Moodbile.time.getDateTime(data.timeavailable);
            timedue = Moodbile.time.getDateTime(data.timedue);
            timemodified = Moodbile.time.getDateTime(data.timemodified);
                
            //miramos si hay el titulo del curso creado
            currentItem = "#wrapper a.moodbile-event-date";
            if($(currentItem+":contains('"+ date +"')").length == 0) {
                Moodbile.cloneTemplate('event-date:last', '#wrapper');
                $(currentItem+':last').attr({'data-course-id': courseid, 'data-type': 'event-date'}).addClass('collapse').text(date);
                $(currentItem+':last').after('<div class="moodbile-events collapsible" data-type="data-group"/>');
    
                currentItem = "#wrapper div.moodbile-events[data-type='data-group']:last";
                $(currentItem).attr({'data-event-date': date,  'data-course-id': courseid});
            } else {
                currentItem = "#wrapper div.moodbile-events[data-event-date='"+ date +"']";
                courseids = $(currentItem).attr('data-course-id');
                courseids += ' '+courseid; //TODO: solucionar que no sean multiples
                        
                $(currentItem).prev().attr('data-course-id', courseids);
                $(currentItem).attr('data-course-id', courseids);
            }
                
            Moodbile.cloneTemplate('event', currentItem);
                
            //Seleccionamos el ultimo evento que se va a mostrar
            currentItem = $(currentItem).find('div.moodbile-event:last-child');
            currentItem.attr({ 'data-course-id': courseid , 'data-event-id': data.id , 'data-type': 'events'});
            currentItem.find('.moodbile-event-link .moodbile-event-title').text(data.name).parent().attr('title', data.name);
            currentItem.find('.moodbile-event-link .moodbile-event-course').text(coursename);
            currentItem.find('.moodbile-event-link .moodbile-icon').addClass('icon-assignment');
                
            //Generate DOM for insert in details
            domStr = '<div class="intro">'+ this.intro +'</div>';
            domStr += '<div class="timeavailable"><strong>'+Moodbile.t('timeavailable')+': </strong>';
            domStr += '<time datetime="'+data.timeavailable+'">' + timeavailable +'</time></div>';
            domStr += '<div class="timedue"><strong> '+ Moodbile.t('timedue') +': </strong>';
            domStr += '<time datetime="'+data.timedue+'">'+ timedue +'</time></div>';
            domStr += '<div class="timemodified"><strong>'+ Moodbile.t('timemodified') +': </strong>';
            domStr += '<time datetime="'+data.timemodified+'">'+ timemodified +'</time></div>';
                
            currentItem.find('details').append(domStr);
        }
    });
            
        //Ocultamos el contenido de event
    $('#wrapper').find('a.moodbile-event-date, div.moodbile-events').hide();
                
    Moodbile.modules.events.status.dataLoaded = true;
};