var Moodbile = {'behaviorsPatterns': {}, 'aux': {}, 'templates': {}};
//Moodbile.behaviorsPatterns.helloword = function (){ alert('hello word'); };
Moodbile.wsurl = "dummie/ws.dum.php";
Moodbile.lang = "es_ES";
Moodbile.tStrings = null;
Moodbile.currentJson = null;
Moodbile.requestJson = [];
Moodbile.queueJson = [];
Moodbile.enroledCoursesid = []; //Array donde dentro se guardan los ids de los cursos del cual el usuario esta enrolado
Moodbile.intervalDelay = 50;

//Funcion que ejecuta los comportamientos de los js de cada modulo
Moodbile.attachBehaviors = function(context) {
    var context = context || document;
    jQuery.each(Moodbile.behaviorsPatterns, function() {
      this(context);
    });
}

/*
Como funcionan las peticiones JSON en Moodbile:
    1. Se inicializa la variable donde se guardara la info. y se meten las opciones en una cola
    2. Se inicializa un intervalo la cual no parara hasta que las siguientes comprobaciones se cumplan:
        2.1. La peticion actual haya terminado (currentJson = null) y... <- Imprescindible?????
        2.2. La peticion anterior haya terminado
    3. Una vez cumplidas condiciones, se realiza la peticion y una vez recibida la info:
        3.1. Se guardara la info recibida en la variable correspondiente
        3.2. Se ejecutara la funcion callbackFunction para procesar la info.
        3.3. Se indica que la peticion actual ha terminado <- Indispensable??????
        3.4. Se indica que la peticion de la cola realizada ha terminado.
*/
Moodbile.json = function(context, requestName, op, callbackFunction) {
    Moodbile.requestJson[requestName] = null; //Definimos que es null
    
    //Ahora, comprobare si la variable op, tiene consigo mas de una opcion, si es asi, montamos variables a pedir
    if($.isArray(op) == true){
        var newOp = "";
        for(key in op) {
            newOp += key+"="+op[key]+"&";
        }
    } else {
        var newOp = "op="+op+"&";
    }
    console.log(newOp);
    op = newOp;
    
    //console.log(Moodbile.json.arguments);
    var currentQueueKey = Moodbile.queueJson.length;
    Moodbile.queueJson[currentQueueKey] = op;
    
    Moodbile.aux.loading(true);
    var initRequest = setInterval(function() {
        if(currentQueueKey == 0 || Moodbile.queueJson[currentQueueKey-1] == null) {
            //console.log('toRequestKey -> '+currentKey);
            Moodbile.currentJson = $.getJSON(Moodbile.wsurl +'?'+Moodbile.queueJson[currentQueueKey]+'jsoncallback=?', function(json){
                Moodbile.requestJson[requestName] = json;
                Moodbile.currentJson = null;
                Moodbile.queueJson[currentQueueKey] = null;
                Moodbile.aux.loading(false);
                
                callbackFunction(json);
                //console.log('currentKeyRequest -> '+ Moodbile.queueJson[currentKey]);
            });
            //Moodbile.currentJson.onerror = function() {alert('ok')}
            clearInterval(initRequest);//comprobar si la cola es 0, en tal caso, detener el intervalo
        }
    }, Moodbile.intervalDelay);
}

//Internacionalization
//MEJORAR
Moodbile.t = function(stringToTranslate) {
    var string = Moodbile.i18n[stringToTranslate];
    
    return string;
}

Moodbile.behaviorsPatterns.activeSection = function(context){
    $('nav#toolbar li a').live('click', function(){
        $('nav#toolbar').find('.active').removeClass('active');
        $(this).parent().addClass('active'); 
    });
}

Moodbile.behaviorsPatterns.createLoadingBox = function(context){
    $('#container').after('<div id="loading">'+Moodbile.t('Loading')+'...</div>');
    $('#loading').hide();
    
    //TODO: Si en el iPhone surge el mismo efecto, seguir utilizando la funcion vieja
    /*$('#loading').ajaxSend(function() {
        $(this).show();
    });
    $('#loading').ajaxSuccess(function() {
        $(this).hide();
    });*/
}

Moodbile.aux.loading = function(op) {
    if(op == true) {
        $('#loading').show();
    } else {
        $('#loading').hide();
    }
}

Moodbile.aux.infoViewer = function(title, type, info) {
    $('#container').append('<section id="info-viewer"><header class="title"><button class="back fx"><span class="icon-back">back</span></button><h1>'+title+'</h1></header><div class="content '+ type+ '">'+ info +'</div></section>');
    $('#info-viewer').height($(window).height()-10);
    
    //Habilitamos el boton de regreso
    //TODO: Probar si los bottones son admitidos como clickables
    $('.back').live('click', function(){
        $('#info-viewer').remove();
        $('#content, #toolbar').show();
    });
}

Moodbile.behaviorsPatterns.collapsible = function() {
    //Prevent CSS
    $(".collapsible").live('click', function(){
        //TODO: Mejorarlo, aprender a crear eventos.
        if($(this).parent().parent().is('.expanded')) {
            $(this).parent().parent().removeClass('expanded');
            $(this).parent().parent().addClass('collapsed');
        } else {
            $(this).parent().parent().removeClass('collapsed');
            $(this).parent().parent().addClass('expanded');
        }
        
        return false;
    });
}

Moodbile.behaviorsPatterns.toolbar = function(context){
    //hacemos desaparecer los menus indecesaios
    var menu_items = $('nav li').length-1;
    //alert(menu_items);
    
    $('nav#toolbar').css('display', 'none'); //ocultamos todas barra de navegacion
    //$('nav li:eq(0)').show();

    //una vez pulsamos el curso
    $('.course a').live('click', function(){
        var id = $(this).parent().attr('id');
        var nav = $('nav#toolbar li');
        
        $.each(nav, function(i, nav){
               var item = $('nav#toolbar li:eq('+i+')').attr('id');
               
               $('nav#toolbar li:eq('+i+')').removeClass(); //borramos las clases que hagan referencia al contenido que queremos ver
               $('nav#toolbar li:eq('+i+')').addClass(id);
               $('nav#toolbar').show();
        });
    });
    
    $('nav#toolbar li#courses a, #sitename').live('click', function(){
        $('nav#toolbar').hide(); //ocultamos todas las opciones excepto el curso, que es el home
        $('section:visible').hide();
    });
}

$(document).ready(function() { 
    Moodbile.attachBehaviors(this);
});