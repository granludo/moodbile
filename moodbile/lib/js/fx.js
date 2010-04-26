Moodbile.fx.SlideUpDown = function(selector){
    if (Moodbile.enableFx == true) {
        if ($(selector).is(':hidden')) {
            $(selector).css({top: $(document).height()+'px'});
            $(selector).show().animate({'top': "0px"}, 500);
        } else {
            $(selector).animate({'top': $(document).height()+'px'}, 500, function() {
                $(this).hide();
            });
        }
    }
}

Moodbile.fx.SlideRigthLeft = function(selector){
    if (Moodbile.enableFx == true) {
        if ($(selector).is(':hidden')) {
            $(selector).css({left: $(document).width()+'px'});
            $(selector).show().animate({'left': "0px"}, 500);
        } else {
            $(selector).animate({'left': $(document).width()+'px'}, 500, function() {
                $(this).hide();
            });
        }
    }
}