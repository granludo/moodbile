$(function(){
    $('nav li a').live('click', function(){
        $('nav').find('.active').removeClass('active');
        $(this).parent().addClass('active');
        
        
    });
});