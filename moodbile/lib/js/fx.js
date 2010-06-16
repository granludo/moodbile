Moodbile.fx.SlideUpDown = function(selector){
    if (Moodbile.enableFx) {
        if ($(selector).is(':hidden')) {
            $(selector).css({top: $(document).height()+'px'});
            $(selector).show().stop().animate({'top': "0px"}, Moodbile.intervalDelay*15);
        } else {
            $(selector).stop().animate({'top': $(document).height()+'px'}, Moodbile.intervalDelay*10, function() {
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
            $(selector).show().stop().animate({'left': "0px"}, Moodbile.intervalDelay*15);
        } else {
            $(selector).stop().animate({'left': $(document).width()+'px'}, Moodbile.intervalDelay*10, function() {
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