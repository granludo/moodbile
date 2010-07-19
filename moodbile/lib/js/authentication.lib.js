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
            //Solucionar si la cookie de acceso es de diferente dominio...
            //Cargamos DOM
            var callback = function() {
                $('#user-welcome').text(Moodbile.t('Welcome'));
                $('#user-profile').css({'background-image':'url('+Moodbile.user.avatar+')'});
                $('#user-options-profile a').text(Moodbile.t('Profile'));
                $('#user-options-logout a').text(Moodbile.t('Logout'));
                $('#user-nav').hide();
            
                $("#user-options-profile a").addClass('user fx '+ Moodbile.user.id.toString());
                //Acciones para las opciones de menu del usuario
            }
            
            Moodbile.cloneTemplate('user-options', '#content header', callback);
        }
        
        //AJAX
        Moodbile.ajaxLogin(user, pass, userTemplate);
        
    } else {
        //Misma peticion ajax que en if, esta vez para chequear si la contraseña es valida
        var callback = function () {
            var userInfo = $.toJSON({'user': user,'pass': pass, 'lang': Moodbile.user.lang,'lastLogin': Moodbile.user.lastlogin});
            
            cookie = $.setCookie('Moodbile', userInfo, {
                duration: 14 // in days
            });
        
            window.location = Moodbile.location;
        }
        
        Moodbile.ajaxLogin(user, pass, callback);
    }
}

Moodbile.ajaxLogin = function(user, pass, authenticationCallback){

    var callback = function(userData) {
        userData = userData[0]; 
        if(!userData.msg) {
            Moodbile.user = {};
            
            for(data in userData) {
                Moodbile.user[data] = userData[data];
            }
            
            if(authenticationCallback) {
                authenticationCallback();
            }
        } else {
            $('#login-form-input-user').addClass('invalid-input');
            $('#login-form-input-pass').addClass('invalid-input');
                
            if($('em:visible').is('.msg-error')){
                $('.authentication .login-info').find('.msg-error').text(userData.msg);
            } else {
                $('.authentication .login-info').append('<em class="msg msg-error">'+userData.msg+'</em>');
            }
        }
    };

    var petitionOpts = {
        'name'       : 'authentication', 
        'wsfunction' : 'moodle_user_get_users_by_username',
        'wsusername' : user,
        'wspassword' : pass,
        'context'    : { 'usernames': [user] },
        'callback'   : callback,
        'cache'      : false
    };
        
    Moodbile.ajax(petitionOpts);
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
Moodbile.behaviors.authentication = function(context){
    //Comprobamos si esta logeado
        //Si lo esta, llama a Moodbile.login(user, pass) con los datos de la cookie
    //Si no esta logeado
        //Mostramos la pantalla de logeo.
        
    if(Moodbile.isLoged()) {
        var cookie = $.readCookie('Moodbile'), user = $.evalJSON(cookie).user, pass = $.evalJSON(cookie).pass;
        
        if(user && pass) {
            Moodbile.login(user, pass);
        }
    } else {
        Moodbile.authenticationForm();
    }
}

//Funcion encargada de generar un login-box
Moodbile.authenticationForm = function () {
    var callback = function() {
        var auth = $('.moodbile-authentication');
        
        auth.find('#login-form-input-user').attr('placeholder', Moodbile.t('Username'));
        auth.find('#login-form-input-pass').attr('placeholder', Moodbile.t('Password'));
        auth.find('.login-form').find('form');
        auth.find('#login-button').text(Moodbile.t('Login'));
        
        Moodbile.cloneTemplate('site-summary', '#wrapper .moodbile-authentication .login-info');
    }
    
    Moodbile.cloneTemplate('authentication', '#wrapper', callback);
}

//Events
Moodbile.events.userOpts = $('#user-profile').live('click', function(){
    $('#user-nav').toggle();
});

Moodbile.events.userProfile = $("#user-options-profile a").live('click', function() {
    $('#user-nav').hide();
});
            
Moodbile.events.userLogout = $("#user-options-logout a").live('click', function() {
    Moodbile.logout();
        
    return false;
});

Moodbile.events.loginButton = $("#login-button").live('click', function(){
    var user = $('#login-form-input-user').val(), pass = $('#login-form-input-pass').val(), formCheck = true;
            
    //checking user
    if (user == "") {
        var formCheck = false;
        $('#login-form-input-user').addClass('invalid-input');
    }
            
    //checking pass
    if (pass == "") {
        var formCheck = false;
        $('#login-form-input-pass').addClass('invalid-input');
    }
            
    //if check is true, login
        if(formCheck) {
        Moodbile.login(user, pass);
    }
            
    return false; 
});