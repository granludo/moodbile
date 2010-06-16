Moodbile.modules.resource = {
    'status': {
        'dataLoaded': false
    },
    'menu': {
        'itemName' : 'Resources',
        'mainItem' : true,
        'mainItemOpts' : function () {
            $('a.moodbile-event-date').hide();
            $('a.moodbile-course-name').show();
            Moodbile.filter.hideFilter();
        },
        'secondaryItem' : true,
        'secondaryItemOpts': function (courseid) {
            $(".moodbile-event-date").hide();
        }
    },
    'dependency' : 'courses',
    'initBehavior' : null,
    'depBehavior' : function(context) {
        var context = context || document, data = Moodbile.processedData['resource'];
        
        if (Moodbile.processedData['url']) {
            data = data.concat(Moodbile.processedData['url']);
        }
        
        if (data) {
            Moodbile.modules.resource.auxFunc.loadResources(data);
        }
    },
    'auxFunc' : {
        'loadResources' : function(data) {
            var courseid = null, coursename = null, currentItem = null, typeIcon = null;
            $.each(data, function(){
                courseid = this.course;
                coursename = Moodbile.enroledCourses[courseid].fullname;
                
                currentItem = "#wrapper div.moodbile-course-data[data-course-id='"+courseid+"']";
                Moodbile.cloneTemplate('resource', currentItem);
                
                //seleccionamos el ultimo template que se va añadir.
                currentItem = $(currentItem).find('.moodbile-resource:last-child');
                currentItem.attr({'data-resource-id' : this.id, 'data-course-id' : courseid, 'data-type': 'resources'});
                currentItem.find('a.moodbile-resource-link').attr('title', this.name).addClass('arrow');
                currentItem.find('a.moodbile-resource-link div.moodbile-resource-title').append(this.name);
                
                if(this.url) {
                    currentItem.find('.moodbile-resource-link').attr({'href': this.url, 'target': '_blank'});
                }
                
                typeIcon = this.modname;
                if (this.resourceformat) {
                    typeIcon = this.resourceformat;
                }
        
                currentItem.find('.moodbile-resource-link').find('.moodbile-icon').addClass('icon-'+typeIcon);
                
                if (this.intro != "") {
                    currentItem.find('details .intro').append(this.intro);
                } else {
                    currentItem.find('a.collapse').hide();
                }
            });
            
            //Ocultamos el contenido de recursos
            $('#wrapper').find('a.moodbile-course-name, .moodbile-resources').hide();
                
            Moodbile.modules.resource.status.dataLoaded = true;
        }
    }        
}