Moodbile.fx = {};

Moodbile.fx.SlideUpDown = function(selector){
    var callback = Moodbile.fx.SlideUpDown.arguments[1];
    
    if (Moodbile.enableFx) {
        if ($(selector).is(':hidden')) {
            $(selector).css({top: $(document).height()+'px'});
            $(selector).show().stop().animate({'top': "0px"}, Moodbile.intervalDelay*15, function(){
                if(callback) {
                    callback();
                }
            });
        } else {
            $(selector).stop().animate({'top': $(document).height()+'px'}, Moodbile.intervalDelay*10, function() {
                $(this).hide();
                
                if(callback) {
                    callback();
                }
            });
        }
    } else {
        if ($(selector).is(':hidden')) {
            $(selector).show();
        } else {
            $(selector).hide();
        }
        
        if(callback) {
            callback();
        }
    }
}

Moodbile.fx.SlideRigthLeft = function(selector){
    var callback = Moodbile.fx.SlideRigthLeft.arguments[1];
    
    if (Moodbile.enableFx) {
        if ($(selector).is(':hidden')) {
            $(selector).css({left: $(document).width()+'px'});
            $(selector).show().stop().animate({'left': "0px"}, Moodbile.intervalDelay*15, function(){
                if(callback) {
                    callback();
                }
            });
        } else {
            $(selector).stop().animate({'left': $(document).width()+'px'}, Moodbile.intervalDelay*10, function() {
                $(this).hide();
                
                if(callback) {
                    callback();
                }
            });
        }
    } else {
        if ($(selector).is(':hidden')) {
            $(selector).show();
        } else {
            $(selector).hide();
        }
        
        if(callback) {
            callback();
        }
    }
}