Moodbile.fx.SlideUpDown = function(selector){
    if (Moodbile.enableFx) {
        if ($(selector).is(':hidden')) {
            $(selector).css({top: $(document).height()+'px'});
            $(selector).show().stop().animate({'top': "0px"}, 500);
        } else {
            $(selector).stop().animate({'top': $(document).height()+'px'}, 500, function() {
                $(this).hide();
            });
        }
    } else {
        if ($(selector).is(':hidden')) {
            $(selector).show();
        } else {
            $(selector).hide();
        }
    }
}

Moodbile.fx.SlideRigthLeft = function(selector){
    if (Moodbile.enableFx) {
        if ($(selector).is(':hidden')) {
            $(selector).css({left: $(document).width()+'px'});
            $(selector).show().stop().animate({'left': "0px"}, 500);
        } else {
            $(selector).stop().animate({'left': $(document).width()+'px'}, 500, function() {
                $(this).hide();
            });
        }
    } else {
        if ($(selector).is(':hidden')) {
            $(selector).show();
        } else {
            $(selector).hide();
        }
    }
}