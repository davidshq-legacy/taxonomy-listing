let $ = jQuery;
let pctdl_select2 = $.fn.select2;

$(document).ready(function () {
    pctdl_select2.call($(".pctdl-select2"), {
        allowClear: true
    });

    $(document).on('shortcode_button:close', function(e){
        pctdl_select2.call($(".pctdl-select2").val(""));
    });

    $(document).on('shortcode_button:insert', function(e){
        pctdl_select2.call($(".pctdl-select2").val(""));
    });

});