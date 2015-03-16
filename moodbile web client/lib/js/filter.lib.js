Moodbile.behaviors.filter = function() {
    //Unicamente insertara el dom para posteriormente manejarlo
    Moodbile.filter.insertFilter();
}

Moodbile.filter = {};

Moodbile.filter.Opts = {};

Moodbile.filter.insertFilter = function(){
    //Cargar template
    Moodbile.cloneTemplate('filter', '#wrapper');
    $('#moodbile-filter').hide();
        
    $('#moodbile-filter-toggle').text(Moodbile.t('Filter'));
        
    $('#moodbile-filter-box').hide();
};

Moodbile.filter.reloadFilter = function(opts){
    var optionsDOM = "";
        
    if(opts) {
        Moodbile.filter.Opts = opts;
            
        //insert DOM
        $('#moodbile-filter-opts select option').remove();
            
        for (optName in opts) {
            optionsDOM += '<option value="'+optName+'">'+Moodbile.t(optName)+'</option>';
        }
            
        $('#moodbile-filter-opts select').append(optionsDOM);
        $('#moodbile-filter').show();
    } else {
            //Error al expecificar las opciones
    }
};

Moodbile.filter.hideFilter = function(){
    $('#moodbile-filter').hide();
};

//Events
Moodbile.events.filterToggle = $('#moodbile-filter-toggle').live('click', function() {
    if ( $('#moodbile-filter-box').is(':hidden') ) {
        $('#moodbile-filter-box').show().children().show();
    } else {
        $('#moodbile-filter-box').hide();
    }
});
        
Moodbile.events.filterChange = $('#moodbile-filter-opts select').live('change', function() {
    var value = $(this).val(), callback = Moodbile.filter.Opts[value];
                
    if (callback) {
        callback();
    }
                
    $('#moodbile-filter-box').hide();
});