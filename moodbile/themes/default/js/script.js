Moodbile.behaviorsPatterns.theme = function(context) {
    //Prevenimos que el contenedor sea mas grande del tamaño de la pantalla-toolbar, asi evitamos el scrolling
    var windowHeight = $(window).height();
    $('#container').height(windowHeight);
    
    $('.fx').live('click', function(){
        Moodbile.aux.animation(true);
        $('#user-options').hide();
        return false;
    });
    $('.back').live('click', function(){
        Moodbile.aux.animation(false);
        return false;
    });
}

//TODO: Generalizar la funcion de animacion
Moodbile.aux.animation = function(op){
    if(op == true) {
        $('#info-viewer').css({top: $(document).height()+'px'});
        $('#info-viewer').show().animate({'top': "0px"}, 500);
    } else {
        $('#info-viewer').show().animate({'top': $(document).height()+"px"}, 500, function() {
            $(this).hide();
        });
    }
}