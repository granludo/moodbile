Moodbile.modules.resource = {
    'status': {
        'dataLoaded': false
    },
    'menu': {
        'itemName': 'Resources',
        'mainItem': true,
        'secondaryItem': true
    },
    'dependency' : 'courses',
    'initBehavior' : null,
    'depBehavior' : function(context) {
        var context = context || document;
        var data = Moodbile.processedData['resource'];
        
        if(data) {
            Moodbile.modules.resource.auxFunc.loadResources(data);
        }
    },
    'auxFunc' : {
        'loadResources' : function(data){
            var callback = function() {
                $.each(data, function(){
                    var courseid = this.course;
            
                    if($('.frontpage-'+courseid).find('.moodbile-resources').length == 0) {
                        $('#templates .moodbile-resources').clone().appendTo('.frontpage-'+courseid);
                    } else {
                        $('#templates .moodbile-resources').find('.moodbile-resource').clone().appendTo('.frontpage-'+courseid+' .moodbile-resources');
                    }
                    
                    $('.frontpage-'+courseid).children().hide();
            
                    var currentItem = $('.frontpage-'+courseid).find('.moodbile-resource:last-child');
        
                    currentItem.addClass(this.id);
                    currentItem.find('.moodbile-resource-title').append(this.name).addClass('arrow');
                    //currentItem.find('.moodbile-resource-title').find('.moodbile-icon').addClass('icon-'+resource.type);
                    currentItem.find('.info').find('.moodbile-course-shortname').text(Moodbile.enroledCourses[courseid].shortname);
                    currentItem.find('.info').find('.description').append(this.intro);
                });
                
                Moodbile.modules.resource.status.dataLoaded = true;
            }
            Moodbile.getTemplate('resources', '#templates', callback);
        }
    }        
}