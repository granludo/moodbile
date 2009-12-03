$(function(){
    //una vez pulsamos el curso
    $('.course a').live('click', function(){
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
                    $('#wrapper .resources-'+id).append('<div class="' + json.id + '"><a href="#">' + json.title + '</a></div>');
                });        
            });
            
        }
        
        $('nav li#resources a').live('click', function(){
            if($('#wrapper').find('.resources-'+id).is('.resources-'+id)) {
                $('section:visible').hide();
                $('#wrapper .resources-'+id).show();
            }
            
            return false;
        });
        
        return false;
    });
    
    //boton del navegador
    
});