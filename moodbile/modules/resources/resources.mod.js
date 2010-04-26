Moodbile.behaviorsPatterns.resources = function(context){
    var context = context || document;
    
    var loadInterval = setInterval(function(){
        var data = Moodbile.processedData['resource'];
        
        if(data && Moodbile.frontpageLoaded) {
            clearInterval(loadInterval);
            Moodbile.aux.resources(data);
        }
    }, Moodbile.intervalDelay);
}

Moodbile.aux.resources = function(data){
    var callback = function() {
        $.each(data, function(){
            var courseid = this.course;
            
            if($('.frontpage-'+courseid).find('.moodbile-resources').length == 0) {
                $('#templates .moodbile-resources').clone().appendTo('.frontpage-'+courseid);
            } else {
                $('#templates .moodbile-resources').find('.moodbile-resource').clone().appendTo('.frontpage-'+courseid+' .moodbile-resources');
            }
            
            var currentItem = $('.frontpage-'+courseid).find('.moodbile-resource:last-child');
        
            currentItem.addClass(this.id);
            currentItem.find('.moodbile-resource-title').append(this.name).addClass('arrow');
            //currentItem.find('.moodbile-resource-title').find('.moodbile-icon').addClass('icon-'+resource.type);
            currentItem.find('.info').find('.description').append(this.intro);
        });
    }
    Moodbile.loadTemplate('resources', '#templates', callback);
}