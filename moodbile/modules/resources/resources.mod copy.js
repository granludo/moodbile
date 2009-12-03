Moodbile.behaviorsPatterns.resources = function(){
    //setTimeout(function(){ alert(Moodbile.enroledCoursesid[1]);}, 1000);
    setTimeout(function(){
        var ncourses = Moodbile.enroledCoursesid.length;
        var loadInterval = setInterval(function(){
            ncourses = ncourses-1;
            alert(ncourses);
            //Moodbile.aux.frontpage(Moodbile.enroledCoursesid[ncourses]);
            if(ncourses == 0) clearInterval(loadInterval);
        }, 500);
    }, 500);
    
    //una vez pulsamos el curso
    $('nav#toolbar li#resources a').live('click', function(){
        var id = $(this).parent().attr('class');
        id = id.split(' ');
        id = id[0];
        //alert(id);
        
        //$(this).removeClass('loaded');
        $('section:visible').hide();
        
        //Toca comprobar si existe o no la seccion de recursos de un id dado
        if($('#wrapper').find('.resources-'+id).is('.resources-'+id)) {
        
            $('#wrapper .resources-'+id).show();
        
        } else {
        
            //$(this).addClass('loaded');
            $('#wrapper').append('<section class="resources-'+id+'"></section>');
            $('.resources-'+id).hide();
            Moodbile.aux.loading(true);
            $.getJSON("dummie/ws.dum.php?jsoncallback=resources", {op: 1}, resources = function(json){
                $.each(json, function(i, json){
                    $('#wrapper .resources-'+id).append('<div class="' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a><div class="info collapsed"></div></div>');
                    $('#wrapper .resources-'+id).find('.'+json.id).find('.info').append('<div class="more visible"><a href="#" class="collapsible"><span class="icon-info"></span></a></div>');
                    $('#wrapper .resources-'+id).find('.'+json.id).find('.info').append('<div class="description">'+json.description+'</div>');
                    
                });
                $('.resources-'+id).show();
                Moodbile.aux.loading(false);        
            });
            
        }
        
        return false;
    });
    
    //boton del navegador
    
}
Moodbile.templates.resources = function(json){
    $.each(json, function(i, json){
        $('#wrapper .resources-'+id).append('<div class="' + json.id + '"><a href="#"><span class="icon-'+json.type+'"></span>' + json.title + '</a><div class="info collapsed"></div></div>');
        $('#wrapper .resources-'+id).find('.'+json.id).find('.info').append('<div class="more visible"><a href="#" class="collapsible"><span class="icon-info"></span></a></div>');
        $('#wrapper .resources-'+id).find('.'+json.id).find('.info').append('<div class="description">'+json.description+'</div>');
    });
}