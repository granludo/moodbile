Moodbile.modules.user = {
    'status': {
        'dataLoaded': false
    },
    'menu': {
        'itemName': 'Users',
        'mainItem': false,
        'secondaryItem': false
    },
    'dependency' : 'courses',
    'initBehavior' : function(context) {
        var context = context || document;
    
        $('.user').live('click', function(){
            var userid = $(this).attr('class');
            userid = userid.split(' ');
            userid = userid[1];
        
            Moodbile.modules.user.auxFunc.userProfile(Moodbile.user);
        
            return false;
        });
    },
    'depBehavior' : function(context) {

    },
    'auxFunc' : {
        'userProfile' : function(data){
            
            var title = Moodbile.t('Profile'), profileTemplate = $('#templates .moodbile-profile').html(), profile = null;
            
            Moodbile.infoViewer(title, "user", profileTemplate);  
            
            profile = $('#info-viewer .content.user');
            if(profile) {
                profile.find('.avatar').css({'background-image' : 'url('+data.avatar+')'});
                profile.find('.user').append(data.firstname +' '+ data.lastname);
                profile.find('.email').find('a').attr('href', 'mailto:'+data.email).append(data.email);
                profile.find('.country').append(data.country);
                profile.find('.city').append(data.city);
            }
            /*

            var courses = json.courses;
            var content = '<dl>';
            $.each(courses, function() {
                content += '<dd>' + this + '</dd>';
            });
            content += '</dl>';
        
            profile.find('.courses-list').append(content);
            profile.find('.roles').append(json.roles);*/  
        }
    }        
}