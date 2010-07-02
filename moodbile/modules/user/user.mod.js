Moodbile.modules.user = {};

Moodbile.modules.user.status = {
    'dataLoaded': false
};

Moodbile.modules.user.menu = {
    'itemName': 'Users',
    'mainItem': false,
    'secondaryItem': false
};

Moodbile.modules.user.dependency = 'courses';

Moodbile.modules.user.initBehavior = null;

Moodbile.modules.user.depBehavior = null;

Moodbile.modules.user.auxFunc = {};
Moodbile.modules.user.auxFunc.userProfile = function(data){       
    var profileTemplate = $('#templates .moodbile-profile').html(), profile = null;
    
    var title = {
        'title':  Moodbile.t('Profile'),
        'subtitle' : Moodbile.user.firstname +' '+Moodbile.user.lastname,
        'id' : Moodbile.user.id
    };
    
    Moodbile.infoViewer.show(title, "user", profileTemplate, null, null);  
            
    profile = $('#container section.moodbile-info-viewer div.moodbile-info-viewer-wrapper:last');
    if(profile) {
        profile.find('.moodbile-avatar').css({'background-image' : 'url('+data.avatar+')'});
        profile.find('.user').append(data.firstname +' '+ data.lastname);
        profile.find('.email').find('a').attr('href', 'mailto:'+data.email).append(data.email);
        profile.find('.country').append(data.country);
        profile.find('.city').append(data.city);
    }
}

//Events
Moodbile.events.userProfile = $('.user').live('click', function() {
    
    
    Moodbile.modules.user.auxFunc.userProfile(Moodbile.user);
        
    return false;
});