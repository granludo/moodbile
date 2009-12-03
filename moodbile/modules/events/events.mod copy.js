Moodbile.behaviorsPatterns.events = function(context){
    var context = context || document;

    $('nav#toolbar li#events').live('click', function(){
        var id = $(this).attr('class');
        id = id.split(' ');
        id = id[0];
        
        //alert(id);
        
        $('section:visible').hide();
        
        //Toca comprobar si existe o no la seccion de recursos de un id dado
        if($('#wrapper').find('.events-'+id).is('.events-'+id)) {
        
            $('#wrapper .events-'+id).show();
        
        } else {
            
            $('#wrapper').append('<section class="events-'+id+'"></section>');
            $('.events-'+id).hide();
            Moodbile.aux.loading(true);
                
            $.getJSON("dummie/ws.dum.php?jsoncallback=events", {op: 6}, events = function(json){
                $.each(json, function(i, json){
                    $('#wrapper .events-'+id).append('<div class="event ' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a><div class="info collapsed"></div></div>');
                    $('#wrapper .events-'+id).find('.'+json.id).find('.info').append('<span class="more visible"><a href="#" class="collapsible"><span class="icon-info"></span></a></span>');
                    $('#wrapper .events-'+id).find('.'+json.id).find('.info').append('<div class="data">Fecha de entrega: <em>'+ json.enddata +'</em></div>');
                    $('#wrapper .events-'+id).find('.'+json.id).find('.info').append('<div class="description">'+json.description+'</div>');
                });
                
                $('.events-'+id).show();
                Moodbile.aux.loading(false);       
            });
            
        }
    return false;   
    });
}