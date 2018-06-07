let $ = jQuery;
let scrptz_tdl_select2 = $.fn.select2;

$(document).ready(function () {
    scrptz_tdl_select2.call($(".scrptz-tdl-select2"), {
        allowClear: true
    });

    $(document).on('shortcode_button:close', function(e){
        scrptz_tdl_select2.call($(".scrptz-tdl-select2").val(""));
    });

    $(document).on('shortcode_button:insert', function(e){
        scrptz_tdl_select2.call($(".scrptz-tdl-select2").val(""));
    });

});