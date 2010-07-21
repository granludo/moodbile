Moodbile.infoViewer = {};

Moodbile.infoViewer.show = function(opts, type, info, extraButtons, callback) {
    var selector = '#container .moodbile-info-viewer';
    var lastWrapper = selector +' .moodbile-info-viewer-wrapper:last';
    var firstWrapper = selector +' .moodbile-info-viewer-wrapper:first';
    var dom = '', eventName = null, event = null, buttonName = null, buttonStr = null; _aux = null;
    var infoViewerButtons = lastWrapper +" header span.opts";
    
    Moodbile.cloneTemplate('info-viewer .moodbile-info-viewer-wrapper', selector);
    
    _aux = $(lastWrapper);
    _aux.hide();
    _aux.find('button.back span').text(Moodbile.t('Back')).parent().hide();
    _aux.find('button.close span').text(Moodbile.t('Close'));
        
    _aux.attr({'data-infoviewer-id' : opts.id, 'data-type' : type});
    _aux.find('header hgroup h1').text(opts.title);
    _aux.find('header hgroup h2').text(opts.subtitle);
    _aux.find('.moodbile-info-view-content .content:last-child').html(info);
    
    if(extraButtons) {
        $.each(extraButtons, function(){
            buttonName = this.buttonName;
            buttonStr = this.buttonStr;
            eventName = this.eventName;
            event = this.event;
            
            //registramos el evento si no existe
            if (Moodbile.events[eventName] == null) {
                alert(Moodbile.t('Undeclared event')+': '+eventName);
            }
            
            dom += '<button class="custom-button '+ buttonName +'" type="button">';
            dom += '<span class="moodbile-icon icon-'+ buttonName +'">'+ buttonStr +'</span>';
            dom += '</button>';
            
        });
        
        $(infoViewerButtons).append(dom);
    }
    
    //Habilitamos el callback
    if (callback) {
        if (typeof callback == 'function') {
            callback();
        } else {
            alert(Moodbile.t('Invalid callback type'));
        }
    }
    
    //Subimos el scroll hasta arriba y mostramos infoViewer
    _aux.css({"min-height": $(document).height()-10+"px"});
    
    if ($(firstWrapper).is(':hidden')) {
        $(selector).show();
        $(document).scrollTop(0);
        Moodbile.fx.SlideUpDown(firstWrapper);
    } else {
        _aux.find('button.back').show();
        Moodbile.fx.SlideRigthLeft(lastWrapper);
    }
};

//Info-viewer behaviors
Moodbile.behaviors.infoViewer = function() {
    var selector = '#container #info-viewer';
    Moodbile.cloneTemplate('info-viewer', '#container', function () {
        $(selector).hide();
        $(selector +' div.moodbile-info-viewer-wrapper').remove();
    });
};
//Info-viewer events
Moodbile.events.back = $('button.back').live('click', function(){
    var selector = '#container .moodbile-info-viewer div.moodbile-info-viewer-wrapper:last';
    
    Moodbile.fx.SlideRigthLeft(selector, function() {
        $(selector).remove();
    });
});
    
Moodbile.events.close = $('button.close').live('click', function(){
    var _aux = '#container .moodbile-info-viewer';
    
    Moodbile.fx.SlideUpDown(_aux +' div.moodbile-info-viewer-wrapper', function() {
        _aux  = $(_aux);
        _aux.find('div.moodbile-info-viewer-wrapper').remove();
        _aux.hide();
    });
});