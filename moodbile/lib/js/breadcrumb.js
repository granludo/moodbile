//TODO: Terminar de pulir
Moodbile.behaviorsPatterns.breadcrumb = function(context){
    //Preparando breadcrumb
    $('nav#breadcrumb').hide();
    
    //Acciones cuando se pulsa un link permitido
    //level-1
    $('.moodbile-courses-links a').live('click', function(){
        var courseid = $(this).parent().attr('id');
        var item = $(this).text();
        
        if($('nav#breadcrumb li:eq(1)').is('#level-1') == false) {
            $('nav#breadcrumb ul').append('<li id="level-1"><span><a href="#" class="'+courseid+'">'+item+'</a></span></li>');
            $('nav#breadcrumb li a').show();
        } else {
            $('#level-1 a').text(item).removeClass().addClass(courseid).show();
        }
        
        $('nav#breadcrumb').show();
    });
    
    //Acciones cuando se pulsa un link del breadcrumb
    $('nav#breadcrumb li a').live('click', function(){
        if($(this).parent().is('#home') == false){
            var courseid = $(this).attr('class');
                
            $('nav#toolbar').find('.active').removeClass('active');
            $('nav#breadcrumb li:eq(2)').remove();
            $('.frontpage-'+courseid).children().show();
        } else {
            $('nav#breadcrumb').hide();
            $('nav#toolbar').hide();
            $('#wrapper').children().hide();
            $('div.moodbile-courses-links, div.moodbile-courses-links section').show();
        }
        
        return false;
    });
}