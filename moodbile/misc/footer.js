Moodbile.behaviorsPatterns.footer = function(context){
    var context = context || document;
    
    var callback = function(){
        $('.moodbile-about').hide();
        $('#about').text(Moodbile.t('about'));
        
        $('#about').live('click', function(){
            Moodbile.aux.infoViewer(Moodbile.t('about'), 'about', $('.moodbile-about').html());
            Moodbile.aux.animation(true);
            
            return false;
        });
    }
    
    Moodbile.loadTemplate('footer', 'footer', callback);
}