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
        var data = Moodbile.processedData['resource'].concat(Moodbile.processedData['url']);
        
        if(data) {
            Moodbile.modules.resource.auxFunc.loadResources(data);
        }
    },
    'auxFunc' : {
        'loadResources' : function(data){
            $.each(data, function(){
                var courseid = this.course;
            
                if($('.frontpage-'+courseid).find('.moodbile-resources').length == 0) {
                    Moodbile.cloneTemplate('resources', '.frontpage-'+courseid);
                } else {
                    Moodbile.cloneTemplate('resource', '.frontpage-'+courseid+' .moodbile-resources');
                }
                   
                $('.frontpage-'+courseid).children().hide();
                $('.frontpage-'+courseid+' .moodbile-resources').find('a.moodbile-course-name').text(Moodbile.enroledCourses[courseid].fullname);
            
                var currentItem = $('.frontpage-'+courseid).find('.moodbile-resource:last-child');
        
                currentItem.addClass(this.id);
                currentItem.find('.moodbile-resource-title').append(this.name).attr('title', this.name).addClass('arrow');
                
                if(this.url) {
                    currentItem.find('.moodbile-resource-title').attr({'href': this.url, 'target': '_blank'});
                }
                
                if (this.resourceformat) {
                    var typeIcon = this.resourceformat;
                } else {
                    var typeIcon = this.modname;
                }
        
                currentItem.find('.moodbile-resource-title').find('.moodbile-icon').addClass('icon-'+typeIcon);
                
                if (this.intro != "") {
                    currentItem.find('.info').find('.description').append(this.intro);
                } else {
                    currentItem.find('.info').find('a, .description').hide();
                }
            });
                
            Moodbile.modules.resource.status.dataLoaded = true;
        }
    }        
}