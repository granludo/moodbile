Moodbile.behaviorsPatterns.grade = function(context){
    setTimeout(function(){
        Moodbile.aux.grade(context, Moodbile.enroledCoursesid);
    }, 700);
    
    $('nav#toolbar li#grade').live('click', function(){
        var id = $(this).attr('class');
        id = id.split(' ');
        id = id[0];
        
        //alert(id);
        
        $('section:visible').hide();
        
        //Toca comprobar si existe o no la seccion de recursos de un id dado
        /*if($('#wrapper').find('.grade-'+id).is('.grade-'+id)) {
        
            $('#wrapper .grade-'+id).show();
        
        } else {
        
            //$(this).addClass('loaded');
            $('#wrapper').append('<section class="grade-'+id+'"></section>');
            $('.grade-'+id).hide();
            Moodbile.aux.loading(true);
            
            $.getJSON("dummie/ws.dum.php?jsoncallback=grade", {op: 2}, grade = function(json){
                $.each(json, function(i, json){
                    //$('#wrapper .resources-'+id).append('<div class="' + json.id + '">' + json.title + '</div>');

                    var grades = json.grades;
                    $.each(grades, function(i, grades){
                        $('#wrapper .grade-'+id).append('<div class="' + grades.id + '"><a href="#"><span class="icon-'+grades.type+'"></span>' + grades.title + '</a><div class="info collapsed"></div></div>');
                        $('#wrapper .grade-'+id).find('.' + grades.id).find('.info').append('<span class="more visible"><a href="#" class="collapsible"><span class="icon-info"></span></a></span>');
                        $('#wrapper .grade-'+id).find('.' + grades.id).find('.info').append('<div class="data">Calificación: <em>'+ grades.grade +'</em></div>');
                        $('#wrapper .grade-'+id).find('.' + grades.id).find('.info').append('<div class="description">'+ grades.description +'</div>');
                    });
                });
                $('.grade-'+id).show();
                Moodbile.aux.loading(false);        
            });   
        }*/
    return false;
    });
}

Moodbile.aux.grade = function(context, courseids) {
    $.each(courseids, function(){
        $('#wrapper').append('<section class="grade-'+ this +'"></section>');
        $('.grade-'+ this).hide();
    });
    
    var op = 6;
    Moodbile.jsonRequest(context, op, Moodbile.templates.grade);
}

Moodbile.templates.grade = function(json){
    $.each(json, function(i, json){
    //$('#wrapper .resources-'+id).append('<div class="' + json.id + '">' + json.title + '</div>');
        var grades = json.grades;
        $.each(grades, function(i, grades){
            $('#wrapper .grade-'+id).append('<div class="' + grades.id + '"><a href="#"><span class="icon-'+grades.type+'"></span>' + grades.title + '</a><div class="info collapsed"></div></div>');
            $('#wrapper .grade-'+id).find('.' + grades.id).find('.info').append('<span class="more visible"><a href="#" class="collapsible"><span class="icon-info"></span></a></span>');
            $('#wrapper .grade-'+id).find('.' + grades.id).find('.info').append('<div class="data">Calificación: <em>'+ grades.grade +'</em></div>');
            $('#wrapper .grade-'+id).find('.' + grades.id).find('.info').append('<div class="description">'+ grades.description +'</div>');
        });
    });
}