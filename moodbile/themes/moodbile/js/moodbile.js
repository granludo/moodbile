Moodbile.behaviorsPatterns.toolbarposition = function(context) {
    //Prevenimos que el contenedor sea mas grande del tamaño de la pantalla-toolbar, asi evitamos el scrolling
    var windowHeight = $(window).height();
    $('#container').height(windowHeight);
    
    $('.user, .event a, .grade a').live('click', function(){
        Moodbile.aux.animation(true);
        return false;
    });
    $('.back').live('click', function(){
        Moodbile.aux.animation(false);
        return false;
    });
    
    //Ajustamos el info-viewer a las necesidades del tema    
    $('.content').flick();
}

//TODO: Generalizar la funcion de animacion
Moodbile.aux.animation = function(op){
    if(Moodbile.currentJson === null) {
        if(op == true) {
    
            $('#toolbar').show().animate({ marginBottom:"-50px"}, 250);
            $('#content').show().animate({ marginLeft:"-320px"}, 250, function(){
                $(this).hide();
                $('#info-viewer').show().animate({ marginLeft:"5px"}, 250);
            });
        
        } else {
    
            $('#info-viewer').show().animate( { marginLeft:"320px"}, 250, function(){
                $(this).remove();
                $('#content').show().animate( { marginLeft:"0"}, 250);
                $('#toolbar').show().animate({ marginBottom:"0"}, 250);
            });
        }
    } else {
        var checkJson = setInterval(function() {
            if (Moodbile.currentJson == null) {
                Moodbile.aux.animation(true);
                clearInterval(checkJson);
            }
        }, Moodbile.intervalDelay);
    }
}