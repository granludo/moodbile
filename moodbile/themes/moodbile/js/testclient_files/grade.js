$(function(){
    $('#grade').live('click', function(){
        var id = $(this).attr('class');
        id = id.split(' ');
        id = id[0];
        
        //alert(id);
        
        $('section:visible').hide();
        
        //Toca comprobar si existe o no la seccion de recursos de un id dado
        if($('#wrapper').find('.grade-'+id).is('.grade-'+id)) {
        
            $('#wrapper .grade-'+id).show();
        
        } else {
        
            //$(this).addClass('loaded');
            $('#wrapper').append('<section class="grade-'+id+'"></section>');
            $.getJSON("dummie/ws.dum.php?jsoncallback=grade", {op: 2}, grade = function(json){
                $.each(json, function(i, json){
                    //$('#wrapper .resources-'+id).append('<div class="' + json.id + '">' + json.title + '</div>');

                    var grades = json.grades;
                    $.each(grades, function(i, grades){
                        $('#wrapper .grade-'+id).append('<div class="' + grades.title + '"><a href="#">' + grades.title + '<em>'+ grades.grade +'</em></a></div>');
                        //alert(grades.title);
                    });
                });        
            });   
        }
    return false;
    });
});