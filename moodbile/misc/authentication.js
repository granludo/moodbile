//Function for general purpouse
//funcion que enviara la peticion ajax al servidor
Moodbile.login = function (user, pass) {
    //Existe cookie? se envia peticion ajax en caso afirmativo...
        //Enviamos ajax GET (o POST?)
            //Comprobar el json que nos devuelve:
                //Si existe mensage, error, usuario no existe o mal introducido
                //Si no existe mensaje, significa que se ha logeado
                    //Construye variable user
    //..en caso negativo,
        //Crea cookie y redirecciona
    
    var cookie = $.readCookie('Moodbile');
    if(cookie) {
        
        var userTemplate = function() {
            //Cargamos DOM
            $('#content header').append('<button id="user-profile"></button>');
            $('#user-profile').css({'background-image':'url('+Moodbile.user.avatar+')'});
            $('#content header').append('<nav id="user-options"><ul><li id="user-options-profile"><a href="#">'+Moodbile.t('Profile')+'</a></li><li id="user-options-logout"><a href="#">'+Moodbile.t('Logout')+'</a></li></ul></nav>');
            $('#user-options').hide();
            
             //Acciones para las opciones de menu del usuario
            $('#user-profile').toggle(
                function(){
                    $('#user-options').show();
                },
                function () {
                    $('#user-options').hide();
                }
            );
            
            $("#user-options-profile a").addClass('user').addClass(Moodbile.user.id.toString()).addClass('fx').live('click', function() {
                $('#user-options').hide();
            });
            
            $("#user-options-logout a").live('click', function() {
                Moodbile.logout();
        
                return false;
            });
        }
        
        //AJAX
        Moodbile.ajaxLogin(user, pass, userTemplate);
        
    } else {
        //Misma peticion ajax que en if, esta vez para chequear si la contraseña es valida
        var callback = function () {
            var userInfo = $.toJSON({'user': user,'pass': pass, 'lastDataLoaded': Date.parse(Moodbile.actualDate)});
        
            cookie = $.setCookie('Moodbile', userInfo, {
                duration: 1 // in days
            });
        
            window.location = Moodbile.location;
        }
        
        Moodbile.ajaxLogin(user, pass, callback);
    }
}

Moodbile.ajaxLogin = function(user, pass){
    var callback = Moodbile.ajaxLogin.arguments[2];

    $.ajax({
        type: "POST",
        url: 'dummie/auth.dum.php',
        data: {'request':$.toJSON({wsusername: user, wspassword: pass})},
        dataType: 'jsonp',
        success: function(userData) {
            if(!userData.msg) {
                Moodbile.user = {
                    'id' : userData.id,
                    'lastlogin' : userData.lastlogin,
                    'name' : userData.name,
                    'lastname' : userData.lastname,
                    'email0' : userData.email0,
                    'avatar' : userData.avatar
                };
            
                if(callback) {
                    callback();
                }
            } else {
                $('#login-form-input-user').addClass('invalid-input');
                $('#login-form-input-pass').addClass('invalid-input');
                
                if($('em:visible').is('.msg-error')){
                    $('.authentication').find('.login-info').find('.msg-error').text(userData.msg);
                } else {
                    $('.authentication').find('.login-info').append('<em class="msg msg-error">'+userData.msg+'</em>');
                }
            }
        },
        error: function() {
            alert('oops!');
        } 
    });
}

Moodbile.logout = function () {
    //Destruye cookie y redirecciona
    var delCookie = $.delCookie('Moodbile');
    window.location = Moodbile.location;
}

Moodbile.isLoged = function () {
    var cookie = $.readCookie('Moodbile');
    
    if(cookie) {
        return true;
    } else {
        return false;   
    }
}

//Comportamiento frente con moodbile
Moodbile.behaviorsPatterns.authentication = function(context){
    //Comprobamos si esta logeado
        //Si lo esta, llama a Moodbile.login(user, pass) con los datos de la cookie
    //Si no esta logeado
        //Mostramos la pantalla de logeo.
        
    if(Moodbile.isLoged()) {
        var cookie = $.readCookie('Moodbile');
        var user = $.evalJSON(cookie).user;
        var pass = $.evalJSON(cookie).pass;
        
        if(user && pass) {
            Moodbile.login(user, pass);
        }
    } else {
        Moodbile.aux.authentication();
    }
}

//Funcion encargada de generar un login-box
Moodbile.aux.authentication = function () {
    $('#wrapper').append('<section class="authentication"></section>');
    $('.authentication').append('<div class="login-form"></div>');
    $('.authentication').find('.login-form').append('<input type="text" id="login-form-input-user"/>');
    $('.authentication').find('#login-form-input-user').val(Moodbile.t('Username'));
    $('.authentication').find('.login-form').append('<input type="password" id="login-form-input-pass"/>');
    $('.authentication').find('#login-form-input-pass').val(Moodbile.t('Password'));
    $('.authentication').find('.login-form').hide();
    $('.authentication').append('<div class="login-info"><button id="login-button">'+Moodbile.t('Login')+'</button></div>');
    $('.authentication').append('<div class="site-summary"></div>');
    $('.site-summary').load('misc/sitesummary.tpl.html');
    
    //PROVISIONAL
    $('.authentication').find('.login-info').append('<em class="msg msg-alert">Demo user:<br />User: demo, Password: 123456</em>');
    
    //Acciones
    $('#login-form-input-user, #login-form-input-pass').focus(function() {
        if($(this).val() == "" || $(this).val() == Moodbile.t('Username') || $(this).val() == Moodbile.t('Password')) {
            $(this).val('');
        }
    });
    
    $("#login-button").live('click', function(){
        if($('.authentication .login-form').is(':visible') == false){
            $('.authentication').find('.login-form').show();
        } else {
            var user = $('#login-form-input-user').val();
            var pass = $('#login-form-input-pass').val();
            var formCheck = true;
            
            //checking user
            if (user == "" || user == Moodbile.t('Username')) {
                var formCheck = false;
                $('#login-form-input-user').addClass('invalid-input');
            }
            
            //checking pass
            if (pass == "" || pass == Moodbile.t('Password')) {
                var formCheck = false;
                $('#login-form-input-pass').addClass('invalid-input');
            }
            
            //if check is true, login
            if(formCheck) {
                Moodbile.login(user, pass);
            }
        }
        return false; 
    });
}