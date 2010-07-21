Moodbile.modules.user = {};

Moodbile.modules.user.status = {
    'dataLoaded': false
};

Moodbile.modules.user.menu = {
    'itemName': 'Users',
    'mainItem': false,
    'secondaryItem': false
};

Moodbile.modules.user.dependency = 'course';

Moodbile.modules.user.initBehavior = null;

Moodbile.modules.user.depBehavior = null;

Moodbile.modules.user.auxFunc = {};
Moodbile.modules.user.auxFunc.userProfile = function(data){
    var data = data[0], _dom = '', profile = null;
    
    var title = {
        'title':  Moodbile.t('Profile'),
        'subtitle' : data.firstname +' '+ data.lastname,
        'id' : data.id
    };  
            
    _profile = $('#container section.moodbile-info-viewer div.moodbile-info-viewer-wrapper:last div.content');
    _dom = Moodbile.modules.user.auxFunc.generateProfileDOM(data);
    
    if(_dom) {
        Moodbile.infoViewer.show(title, "user", _dom, null, null);
        
        _profile.addClass('moodbile-profile');
        _profile.find('.moodbile-avatar').css({'background-image' : 'url('+ Moodbile.userAvatarUrl(data.id) +')'});
    }
}

Moodbile.modules.user.auxFunc.generateProfileDOM = function(data) {
    var _data = data;
    var _profileData = [];
    var _exceptions = ['id', 'firstname', 'lastname', 'username', 'confirmed', 'timezone', 'emailstop', 'mailformat', 'lastlogin'];
    var _aux = null;
    var _str = "";
    
    _profileData.push({'user': _data.firstname +' '+ _data.lastname});
    
    //filter data
    $.each(_data, function(field, value) {
        if (value != "" && $.inArray(field, _exceptions) < 0) {
            _aux = {};
            _aux[field] = value;
            _profileData.push(_aux);
        }
    });
    
    $.each(_profileData, function(i, value) {
        var _this = value;
        
        $.each(_this, function(field, info) {
            _str += '<div class="'+ field +'">';
            
            _aux = 'moodbile-icon icon-'+ field;
            if (field == 'user') _aux = 'moodbile-avatar';
            if (field == 'email') info = '<a href="mailto:'+ info +'">'+ info +'</a>';
            
            _str += '<span class="'+ _aux +'"></span>';
            _str += info +'</div>';
        });
    });
    
    return _str;
}

//Events
Moodbile.events.userProfile = $('.user').live('click', function() {
    var _this = $(this);
    
    if( _this.is('.me') ) {
        var _user = Moodbile.user;
        Moodbile.modules.user.auxFunc.userProfile([_user]);
    } else {
        var userids = [_this.attr('data-user-id')];
        var petitionOpts = {
            'name'       : 'getUser', 
            'wsfunction' : 'moodle_user_get_users_by_id',
            'context'    : { 'userids': userids },
            'callback'   : Moodbile.modules.user.auxFunc.userProfile,
            'cache'      : false
        }
        
        Moodbile.json(petitionOpts);
    }
        
    return false;
});