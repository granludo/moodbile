//Moodbile.behaviorsPatterns.helloword();

Moodbile.behaviorsPatterns.toolbarposition = function(context) {
 
    //Prevenimos que el contenedor sea mas grande del tamaño de la pantalla-toolbar, asi evitamos el scrolling
    var windowHeight = $(window).height();
    $('#container').height(windowHeight-50);

    var yStart;
    var yCurrent;
    var topN = 0;
    
    document.body.ontouchstart = function(e) {
        
        yStart = e.touches[0].pageY; 
    }

    document.body.ontouchmove = function(e) {
        // prevent window scrolling!
        e.preventDefault();
        //e.targetTouches[0].preventDefault();
        
        //TODO. Mirar manera para anular accion mientras se mueve el contenido.
        yCurrent = e.touches[0].pageY;
        yPixelsMoved = yCurrent-yStart;
        //$('body').append("<div>current Y = " + yCurrent + " and YPixelsMoved = " + yPixelsMoved +"</div>");
        
        currentPosition = $('#container').scrollTop();
        
        topN = currentPosition - Math.round(yPixelsMoved/4);
        
        
        $('#container').scrollTop(topN);
        //alert(currentPosition);
        $('.box').text(""+topN+"");
    }
    
    document.body.ontouchend = function(e) {
        //alert("Dejaste de pulsar");
        e.preventDefault();
        //yStart = e.touches[0].pageY; 
    }
}