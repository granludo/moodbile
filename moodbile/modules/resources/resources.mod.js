Moodbile.behaviorsPatterns.resources = function(){
    //una vez pulsamos el curso
    $('nav#toolbar li#resources a').live('click', function(){
        var id = $(this).attr('id');
        //alert(id);
        
        //$(this).removeClass('loaded');
        $('section:visible').hide();
        
        //Toca comprobar si existe o no la seccion de recursos de un id dado
        if($('#wrapper').find('.resources-'+id).is('.resources-'+id)) {
        
            $('#wrapper .resources-'+id).show();
        
        } else {
        
            //$(this).addClass('loaded');
            $('#wrapper').append('<section class="resources-'+id+'"></section>');
            $.getJSON("dummie/ws.dum.php?jsoncallback=resources", {op: 1}, resources = function(json){
                $.each(json, function(i, json){
                    $('#wrapper .resources-'+id).append('<div class="' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a></div>');
                });        
            });
            
        }
        
        return false;
    });
    
    //boton del navegador
    
}