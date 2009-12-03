$(function(){
    $('nav li#events').live('click', function(){
        var id = $(this).attr('class');
        id = id.split(' ');
        id = id[0];
        
        //alert(id);
        
        $('section:visible').hide();
        
        //Toca comprobar si existe o no la seccion de recursos de un id dado
        if($('#wrapper').find('.events-'+id).is('.events-'+id)) {
        
            $('#wrapper .events-'+id).show();
        
        } else {
            
            //$('#wrapper .events-'+id).remove();
            //$(this).addClass('loaded');
            $('#wrapper').append('<section class="events-'+id+'"></section>');
            $.getJSON("dummie/ws.dum.php?jsoncallback=events", {op: 6}, events = function(json){
                $.each(json, function(i, json){
                    $('#wrapper .events-'+id).append('<div class="event ' + json.id + '"><a href="#">' + json.title + '<em>'+ json.enddata +'</em></a></div>');
                });        
            });
            
        }
    return false;   
    });
});