$(function(){
    $('#wrapper').append('<section class="courses"></section>');
    
    $.getJSON("dummie/ws.dum.php?jsoncallback=courses", {op: 0}, courses = function(json){
        $.each(json, function(i, json){
            $('#wrapper .courses').append('<div class="course"><a href="#" id="' + json.id + '">' + json.title + '</a></div>');
        });
                    
                    //$("#resultado").html("Nombre:" + json[1].dates.name);
    });
    
    //funcion para el caso de pulsar el icono de navegacion
    $('nav li#courses a').live('click', function(){
        //$('section:visible').hide();
        $('section.courses').show();
        
        return false;
    });
});