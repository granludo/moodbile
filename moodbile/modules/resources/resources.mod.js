//TODO: Cambiar estructura de datos
Moodbile.behaviorsPatterns.resources = function(context){
    var context = context || document;
    
    var petitionOpts = {'wsfunction':'resources'};
    var loadInterval = setInterval(function(){
        if(Moodbile.enroledCoursesid.length != 0) {
            Moodbile.json(context, petitionOpts, Moodbile.jsonCallbacks.resources, true);
            clearInterval(loadInterval);
        }
    }, Moodbile.intervalDelay);
}

Moodbile.jsonCallbacks.resources = function(json){
    var callback = function(){
        var itemHTML = $('.moodbile-resources:eq(0)').html();
        
        $.each(Moodbile.enroledCoursesid, function(){
            var id = this.toString();
            
            //itemHTML = $('.moodbile-resources:eq(0)').html();
            $('.moodbile-resources:eq(0)').clone().appendTo('#wrapper');
            
            var sectionsLength = $('.moodbile-resources').length-1;
            $('.moodbile-resources:eq('+sectionsLength+')').addClass('resources-'+ id);
            $('.resources-'+ id).hide();
        });

        $.each(json, function(i, json) {
            var courseid = json.courseid;
            var resource = json.resource;
            
            $('#wrapper .resources-'+courseid).append(itemHTML);
            
            var currentItem = $('#wrapper .resources-'+courseid).find('.moodbile-resource:last-child');
        
            currentItem.addClass(resource.id.toString());
            currentItem.find('.moodbile-resource-title').append(resource.title).addClass('arrow');
            currentItem.find('.moodbile-resource-title').find('.moodbile-icon').addClass('icon-'+resource.type);
            currentItem.find('.info').find('.description').append(resource.description);
        });
        
        $('.moodbile-resource:first-child').remove();
        $('.moodbile-resources:visible').remove();
    }
    
    Moodbile.loadTemplate('resources', '#wrapper', callback);
}