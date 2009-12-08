Moodbile.behaviorsPatterns.grade = function(context){
    var context = context || document;
    
    setTimeout(function(){
        Moodbile.aux.grade(context, Moodbile.enroledCoursesid);
    }, 700);
    
    $('nav#toolbar li#grade').live('click', function(){
        var id = $(this).attr('class');
        id = id.split(' ');
        id = id[0];
        
        $('section:visible').hide();
        $('.grade-'+id).show();
        
        return false;
    });
}

Moodbile.aux.grade = function(context, courseids) {
    $.each(courseids, function(){
        $('#wrapper').append('<section class="grade-'+ this +'"></section>');
        $('.grade-'+ this).hide();
    });
    
    var op = 2;
    Moodbile.jsonRequest(context, op, Moodbile.templates.grade);
}

Moodbile.templates.grade = function(json){
    $.each(json, function(i, json){
        var courseid = json.courseid;
        var grades = json.grades;
        
        $('#wrapper .grade-'+ courseid).append('<h6><a href="#" class="user '+json.id+'">'+json.name+' '+json.lastname+'</a></h6>');
        
        $.each(grades, function(i, grades){
            $('#wrapper .grade-'+ courseid).append('<div class="' + grades.id + '"><a href="#"><span class="icon-'+grades.type+'"></span>' + grades.title + '</a><div class="info collapsed"></div></div>');
            $('#wrapper .grade-'+ courseid).find('.' + grades.id).find('.info').append('<span class="more visible"><a href="#" class="collapsible"><span class="icon-info"></span></a></span>');
            $('#wrapper .grade-'+ courseid).find('.' + grades.id).find('.info').append('<div class="data">Calificación: <em>'+ grades.grade +'</em></div>');
            $('#wrapper .grade-'+ courseid).find('.' + grades.id).find('.info').append('<div class="description">'+ grades.description +'</div>');
        });
    });
}