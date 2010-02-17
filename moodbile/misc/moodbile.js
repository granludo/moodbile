var Moodbile = {'user': {}, 'behaviorsPatterns': {}, 'aux': {}, 'templates': {}};
//Moodbile.behaviorsPatterns.helloword = function (){ alert('hello word'); };
//Moodbile.wsurl = "http://basketpc.com/ind3x/ws.dum.php";
Moodbile.wsurl = "dummie/ws.dum.php";
Moodbile.lang = "es_ES";
Moodbile.tStrings = null;
Moodbile.currentJson = null;
Moodbile.requestJson = [];
Moodbile.queueJson = [];
Moodbile.enroledCoursesid = []; //Array donde dentro se guardan los ids de los cursos del cual el usuario esta enrolado
Moodbile.intervalDelay = 50;

//PROVISIONAL
Moodbile.user = {
    'id' : 50,
    'lastlogin' : '1265815958806',
    'name' : 'Imanol',
    'lastname' : 'Urra Ruiz',
    'email0' : 'index02@gmail.com',
    'avatar' : 'http://terrassatsc.upc.edu/user/pix.php/1809/f1.jpg'
}

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
    if(Moodbile.i18n[stringToTranslate] != null) {
        var string = Moodbile.i18n[stringToTranslate];
    } else {
        var string = "STRING!"
    }
    return string;
}

Moodbile.behaviorsPatterns.activeSection = function(context){
    $('nav#toolbar li a').live('click', function(){
        $('nav#toolbar').find('.active').removeClass('active');
        $(this).parent().addClass('active'); 
    });
}

Moodbile.behaviorsPatterns.createLoadingBox = function(context){
    $('#container').after('<div id="loading"><div>'+Moodbile.t('Loading')+'...</div></div>');
    //$('#loading').hide();
    
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
Moodbile.behaviorsPatterns.infoViewer = function(){
    $('#container').append('<section id="info-viewer"><header class="title"><button class="back"><span class="icon-back">'+Moodbile.t('Back')+'</span></button><h1></h1></header><div class="content"></div></section>');
    $('#info-viewer').css({"min-height": $(window).height()-10+"px"}).hide();
    
    //Habilitamos el boton de regreso
    //TODO: Probar si los bottones son admitidos como clickables
    $('.back').live('click', function(){
        $('#info-viewer').hide();
        $('#content').show();
        
        if ($('#wrapper').find('section:visible').is('.courses') === false){
            $('#toolbar').show();
        }
    });
}

Moodbile.aux.infoViewer = function(title, type, info) {
    //Borramos clases anteriores
    $('#info-viewer').find('.content').removeClass().addClass('content');
    //Añadimos info
    $('#info-viewer').find('.title').find('h1').text(title);
    $('#info-viewer').find('.content').addClass(type).html(info);
    
    //Habilitamos el callback
    if (Moodbile.aux.infoViewer.arguments[3] != null){
        var callback = Moodbile.aux.infoViewer.arguments[3];
        callback();
    }
    
    //mostramos infoViewer
    $('#info-viewer').show();   
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

Moodbile.behaviorsPatterns.toolbar = function(context){ //CAMBIAR NOMBRE de ID. DE #toolbar -> #navbar
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
        $('div.courses-links section').show();
    });
}

Moodbile.behaviorsPatterns.toolbarAcomodation = function(context) {
    var width = $('body').width();
    var navWidth = $('nav#toolbar').width();
    var menu_items = $('nav#toolbar li').length-1;
    
    /*if ((menu_items > 4) && (width <= navWidth)) { //Si es mayor a 4 el menu mayor a la pantalla del dispositivo, quiere decir que los items no entran en pantalla
        var itemsReposition = $('nav#toolbar li:gt(3)').text();
        //$.each(itemsReposition, function(i, itemsReposition){
            //console.log(itemsReposition);
        //});
    }
    
    console.log(menu_items);*/
}

$(document).ready(function() { 
    Moodbile.attachBehaviors(this);
});