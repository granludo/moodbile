var Moodbile = {'user': {}, 'behaviorsPatterns': {}, 'aux': {}, 'templates': {}};

//Moodbile.wsurl = "http://basketpc.com/ind3x/ws.dum.php";
Moodbile.wsurl = "dummie/ws.dum.php";
Moodbile.location =  location.href;
Moodbile.lang = "es_ES";
Moodbile.tStrings = null;
Moodbile.currentJson = null;
Moodbile.requestJson = [];
Moodbile.queueJson = [];
Moodbile.enroledCoursesid = []; //Array donde dentro se guardan los ids de los cursos del cual el usuario esta enrolado
Moodbile.intervalDelay = 50;
Moodbile.actualDate = new Date();
Moodbile.requestJsonExpireTime = 2;//in minutes
Moodbile.needReload = true;
Moodbile.user = null;

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
Moodbile.json = function(context, op, callbackFunction) {
    var context = context || document;
    //Prevencion, hasta que no se cree la variable user, no se haran las peticiones
    var checkUserVariable = setInterval(function() {
        if(Moodbile.user != null) {
            clearInterval(checkUserVariable);
            //init variables
            //Definimos null, la variable donde se almacenara la peticion.
            Moodbile.requestJson[op.wsfunction] = null;
            //Creamos una tabla para las peticiones
            Moodbile.webdb.createTable('requestJSON', {'requestName':'TEXT','JSON':'TEXT', 'date':'DATETIME', 'userid':'INTEGER' });
    
            //Añadimos user y password a la opcion de peticion
            var cookie = $.readCookie('Moodbile');
            if (cookie) {
                op.wsusername = $.evalJSON(cookie).user;
                op.wspassword = $.evalJSON(cookie).pass;
            }
    
            //Añadimos peticion en cola.
            var currentQueueKey = Moodbile.queueJson.length;
            Moodbile.queueJson[currentQueueKey] = op;
    
            //Llamamos a la funcion que chequea si hace falta hacer un reload
            if(Moodbile.needReload) { //Moodbile.webdb.needReload('requestJSON')
                //Iniciamos intervalo que permitira evitar, el colapso de las peticiones.
                var initRequest = setInterval(function() {
                    if(currentQueueKey == 0 || Moodbile.queueJson[currentQueueKey-1] == null) {
                        //console.log('toRequestKey -> '+currentKey);
                        Moodbile.currentJson = $.ajax({
                            type: "POST",
                            url: Moodbile.wsurl,
                            data: ({request: encodeURIComponent($.toJSON(Moodbile.queueJson[currentQueueKey]))}),
                            dataType: 'jsonp',
                            success: function(json) {
                                //TODO: Añadir un if para comprobar si hay o no respuesta
                    
                                Moodbile.requestJson[Moodbile.queueJson[currentQueueKey].wsfunction] = json;
                    
                                Moodbile.webdb.deleteValues('requestJSON', {'userid': Moodbile.user.id, 'requestName': Moodbile.queueJson[currentQueueKey].wsfunction});
                                Moodbile.webdb.addValues('requestJSON', {'requestName': Moodbile.queueJson[currentQueueKey].wsfunction, 'JSON': $.toJSON(json), 'date': Date.parse(Moodbile.actualDate), 'userid': Moodbile.user.id });
                   
                                Moodbile.currentJson = null;
                                Moodbile.queueJson[currentQueueKey] = null;
                
                                callbackFunction(json);
                            },
                            error: function() {
                                alert('oops!');
                            } 
                        });
            
                        clearInterval(initRequest);//comprobar si la cola es 0, en tal caso, detener el intervalo
                    }
                }, Moodbile.intervalDelay);
            } else {
                var requestName = Moodbile.queueJson[currentQueueKey].wsfunction;
                var userid = Moodbile.user.id;
                var loadData = function(tx, rs) {
                    for (var i=0; i < rs.rows.length; i++) {
                        Moodbile.requestJson[requestName] = $.evalJSON(rs.rows.item(i).JSON);
                        callbackFunction($.evalJSON(rs.rows.item(i).JSON));
                    }
                }
                Moodbile.webdb.getRequestJSONbyUserID('requestJSON', requestName, userid, loadData);
            }
        }
    }, Moodbile.intervalDelay);
}

//Internacionalization
//MEJORAR
Moodbile.t = function(stringToTranslate) {
    if(Moodbile.i18n[stringToTranslate] != null) {
        var string = Moodbile.i18n[stringToTranslate];
    } else {
        var string = "STRING!";
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
    
    //habilitar acciones para su uso.
    $('#loading').ajaxSend(function() {
        $(this).show();
    });
    $('#loading').ajaxSuccess(function() {
        $(this).hide();
    });
}

Moodbile.behaviorsPatterns.infoViewer = function(){
    $('#container').append('<section id="info-viewer"><header class="title"><button class="back"><span class="icon-back">'+Moodbile.t('Back')+'</span></button><h1></h1></header><div class="content"></div></section>');
    $('#info-viewer').css({"min-height": $(window).height()-10+"px"}).hide();
    
    //Habilitamos el boton de regreso
    $('.back').live('click', function(){
        $('#info-viewer').hide();
        $('#content').show();
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
    
    $('nav#toolbar').css('display', 'none'); //ocultamos todas barra de navegacion

    //una vez pulsamos el curso
    $('.course a').live('click', function(){
        var id = $(this).parent().attr('id');
        var nav = $('nav#toolbar li');
        
        $.each(nav, function(){
               //var item = $('nav#toolbar li:eq('+i+')').attr('id');
               
               $(this).removeClass(); //borramos las clases que hagan referencia al contenido que queremos ver
               $(this).addClass(id);
               $('nav#toolbar').show();
        });
        
        if($('nav#toolbar li:last-child').is('#more')){
            $('section.toolbar-more div').removeClass(); //borramos las clases que hagan referencia al contenido que queremos ver
            $('section.toolbar-more div').addClass(id);
        }
    });
    
    $('nav#toolbar li#courses a').live('click', function(){
        $('nav#toolbar').hide(); //ocultamos todas las opciones excepto el curso, que es el home
        $('div.courses-links section').show();
    });
}

Moodbile.behaviorsPatterns.toolbarEvents = function(context){
    var context = context || document;
    
    $('nav#toolbar li:not(:first-child) a, .toolbar-more div a').live('click', function(){
        var menuitem = $(this).parent().attr('id');
        var courseid = $(this).parent().attr('class');
        courseid = courseid.split(' ');
        courseid = courseid[0];
        
        $('#wrapper').children().hide();
        if(menuitem != "more"){
            $('.'+ menuitem +'-'+courseid).show().children().show();
        } else {
            $('.toolbar-more').show();
        }
        
        return false;
    });
    
    $('nav#toolbar li#courses a').live('click', function(){
        $('#wrapper').children().hide();
        $('div.courses-links').show();
        $('nav#breadcrumb').hide();
        
        return false;
    });
}

Moodbile.behaviorsPatterns.toolbarAcomodation = function(context) {
    var width = $('body').width();
    var menuItems = $('nav#toolbar li').length-1;
    
    //Mejorar estos calculos
    var itemsWidth = $('nav#toolbar li').css('width').indexOf('px');
    var itemsMargin = $('nav#toolbar li').css('marginLeft').indexOf('px');
    var itemsPadding = $('nav#toolbar li').css('paddingLeft').indexOf('px');
    itemsWidth = $('nav#toolbar li').css('width').slice(0, itemsWidth);
    itemsMargin = $('nav#toolbar li').css('marginLeft').slice(0, itemsMargin);
    itemsPadding = $('nav#toolbar li').css('paddingLeft').slice(0, itemsPadding);
    var itemsWidth = eval(itemsWidth) + (eval(itemsMargin) + eval(itemsPadding))*2;
    var maxItems = (Math.round(width/itemsWidth))-1;
    
    if(menuItems > maxItems){
        $('#wrapper').append('<section class="toolbar-more"></section>').find('.toolbar-more').hide();
        
        maxItems -= 1;
        var itemsToChange = $('nav#toolbar li:gt('+ maxItems +')');
        
        $.each(itemsToChange, function(){
            var linkTitle = $(this).text();
            var itemid = $(this).attr('id');
            
            $('.toolbar-more').append('<div id="'+ itemid +'"><a href="#" class="arrow"><span></span>'+ linkTitle +'</a></div>');
        });
        
        maxItems += 1;
        $('nav#toolbar li:gt('+ maxItems +')').remove();
        $('nav#toolbar li:eq('+ maxItems +')').removeAttr('id').attr('id','more').find('a').text(Moodbile.t('More'));
    }
    
}

$(document).ready(function() { 
    Moodbile.attachBehaviors(this);
});