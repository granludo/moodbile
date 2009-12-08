//Moodbile.behaviorsPatterns.helloword();

Moodbile.behaviorsPatterns.toolbarposition = function(context) {
 
    //Prevenimos que el contenedor sea mas grande del tamaño de la pantalla-toolbar, asi evitamos el scrolling
    var windowHeight = $(window).height();
    $('#content, #container').height(windowHeight);
    
    //Ajustamos el info-viewer a las necesidades del tema
    $('.user').live('click', function(){
        //$('#content').css({"display": "block"});
        alert('hola');
    });

    
    $('.content').flick();
}