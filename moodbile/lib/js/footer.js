Moodbile.behaviorsPatterns.footer = function(context){
    var context = context || document;
    
    var callback = function(){
        $('.moodbile-about').hide();
        $('#about').text(Moodbile.t('about'));
        
        $('#about').live('click', function(){
            Moodbile.infoViewer(Moodbile.t('about'), 'about', $('.moodbile-about').html());
            
            return false;
        });
    }
    
    Moodbile.loadTemplate('footer', 'footer', callback);
}