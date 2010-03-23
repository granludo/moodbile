Moodbile.behaviorsPatterns.grade = function(context){
    var context = context || document;
    
    var petitionOpts = {'wsfunction':'grades'};
    var loadInterval = setInterval(function(){
        if(Moodbile.enroledCoursesid.length != 0) {
            Moodbile.json(context, petitionOpts, Moodbile.jsonCallbacks.grade, true); 
            clearInterval(loadInterval);
        }
    }, Moodbile.intervalDelay);    
}

Moodbile.behaviorsPatterns.gradeViewMoreInfo = function(context){
    var context = context || document;
    
    $('.grade a').live('click', function(){
        var id = $(this).parent().attr('class');
        id = id.split(' ');
        id = id[1];
        
        var title = $(this).text();
        
        var petitionOpts = {'wsfunction':'grade', 'gradeid':id};
        
        Moodbile.json(context, petitionOpts, function(json){
            var content = json.title;
            Moodbile.aux.infoViewer(title, petitionOpts.op, content);
        }, false);
    });
}

Moodbile.jsonCallbacks.grade = function(json){
    var callback = function(){
        var itemInnerHTML = null;
        var itemHTML = null;
        
        $.each(Moodbile.enroledCoursesid, function(){
            var id = this.toString();
            
            itemInnerHTML = $('.moodbile-grades:eq(0) .moodbile-grade').html();
            itemHTML = $('.moodbile-grades:eq(0)').html();
            
            $('.moodbile-grades:eq(0)').clone().appendTo('#wrapper');
            
            var sectionsLength = $('.moodbile-grades').length-1;
            $('.moodbile-grades:eq('+sectionsLength+')').addClass('grade-'+ id);
            $('.grade-'+ id).hide();
            
        });
        
        $.each(json, function(i, json) {
            var courseid = json.courseid;
            var grades = json.grades;

            $('#wrapper .grade-'+courseid).append(itemHTML);
            $('#wrapper .grade-'+courseid).find('.moodbile-user-grades:last-child').addClass('user-grades-'+json.id);
        
            var currentItem = $('#wrapper .grade-'+courseid).find('.user-grades-'+json.id);
        
            currentItem.find('.avatar').css({'background-image' : 'url('+json.avatar+')'});
            currentItem.find('.user').addClass('fx').append(json.name+' '+json.lastname);
        
            $.each(grades, function(i, grades){
            
                var currentUser = $('#wrapper .grade-'+courseid).find('.user-grades-'+json.id).find('.moodbile-grade');
                currentUser.append(itemInnerHTML);
                var currentItemGrades = currentUser.find('.grade:last-child');
            
                currentItemGrades.addClass(grades.id);
                currentItemGrades.find('a:first-child').append(grades.title);
                /*currentItemGrades.find('.moodbile-icon:first-child').addClass(grade.type);
                currentItemGrades.find('.info').find('.moodbile-icon').addClass('icon-info');
                currentItemGrades.find('.info').find('.data').append('Calificación: <em>'+ grades.grade +'</em>');
                currentItem.find('.info').find('.description').append(grades.description); */
            });
        });
        
        $('.grade:first-child').remove();
        $('.moodbile-user-grades:first-child').remove();
        $('.moodbile-grades:visible').remove();

    }
    
    Moodbile.loadTemplate('grade', '#wrapper', callback);
}