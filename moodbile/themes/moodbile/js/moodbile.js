//Moodbile.behaviorsPatterns.helloword();

Moodbile.behaviorsPatterns.toolbarposition = function(context) {
 
    //Prevenimos que el contenedor sea mas grande del tamaño de la pantalla-toolbar, asi evitamos el scrolling
    var windowHeight = $(window).height();
    $('#content, #container').height(windowHeight-50);
    
    $('#content').flick();
}