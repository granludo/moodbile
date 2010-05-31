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
            var callback = function() {
                $('#user-welcome').text(Moodbile.t('Welcome'));
                $('#user-profile').css({'background-image':'url('+Moodbile.user.avatar+')'});
                $('#user-options-profile a').text(Moodbile.t('Profile'));
                $('#user-options-logout a').text(Moodbile.t('Logout'));
                $('#user-nav').hide();
            
            
                //Acciones para las opciones de menu del usuario
                $('#user-profile').toggle(
                    function(){
                        $('#user-nav').show();
                    },
                    function () {
                        $('#user-nav').hide();
                    }
                );
            
                $("#user-options-profile a").addClass('user').addClass(Moodbile.user.id.toString()).addClass('fx').live('click', function() {
                    $('#user-nav').hide();
                });
            
                $("#user-options-logout a").live('click', function() {
                    Moodbile.logout();
        
                    return false;
                });
            }
            
            Moodbile.cloneTemplate('user-options', '#content header', callback);
        }
        
        //AJAX
        Moodbile.ajaxLogin(user, pass, userTemplate);
        
    } else {
        //Misma peticion ajax que en if, esta vez para chequear si la contraseña es valida
        var callback = function () {
            var userInfo = $.toJSON({'user': user,'pass': pass, 'lastLogin': Moodbile.user.lastlogin});
        
            cookie = $.setCookie('Moodbile', userInfo, {
                duration: 14 // in days
            });
        
            window.location = Moodbile.location;
        }
        
        Moodbile.ajaxLogin(user, pass, callback);
    }
}

Moodbile.ajaxLogin = function(user, pass, callback){
    var usernames = [user];
    var userdata = {'wsusername': user, 'wspassword': pass, 'wsfunction': 'moodle_user_get_users_by_username', 'usernames': usernames}

    $.ajax({
        type: "POST",
        cache: false,
        url: Moodbile.wsurl,
        data: ({request: encodeURIComponent($.toJSON(userdata))}),
        dataType: 'jsonp',
        beforeSend: function(){
            Moodbile.showMask(true);
        },
        success: function(userData) {
            userData = userData[userData.length-1];
            
            if(!userData.msg) {
                Moodbile.user = {
                    'id'        : userData.id,
                    'lastlogin' : userData.lastlogin,
                    'firstname' : userData.firstname,
                    'lastname'  : userData.lastname,
                    'email'     : userData.email,
                    'avatar'    : userData.avatar,
                    'country'   : userData.country,
                    'city'      : userData.city,
                    'avatar'    : Moodbile.serverLocation+'/user/pix.php/'+userData.id+'/f2.jpg'
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
            
            Moodbile.showMask(false);
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest + textStatus + errorThrown);
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
Moodbile.behaviors.authentication = function(context){
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
    var callback = function() {
        var auth = $('.moodbile-authentication');
        
        auth.find('#login-form-input-user').val(Moodbile.t('Username'));
        auth.find('#login-form-input-pass').val(Moodbile.t('Password'));
        auth.find('.login-form').find('form');
        auth.find('#login-button').text(Moodbile.t('Login'));
        auth.find('.login-info').load('misc/templates/sitesummary.tpl.html', function(){
            auth.find('.site-summary').before('<em class="msg msg-alert">Demo user:<br />User: demo, Password: 123456</em>'); //PROVISIONAL
        });
    
        //Acciones
        $('#login-form-input-user, #login-form-input-pass').focus(function() {
            if($(this).val() == "" || $(this).val() == Moodbile.t('Username') || $(this).val() == Moodbile.t('Password')) {
                $(this).val('');
            }
        });
    
        $("#login-button").live('click', function(){
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
            
            return false; 
        });
    }
    
    Moodbile.cloneTemplate('authentication', '#wrapper', callback);
}