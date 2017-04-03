var $ = jQuery;
var scrptz_tdl_select2 = $.fn.select2;

$(document).ready(function () {
    scrptz_tdl_select2.call($(".scrptz-tdl-select2"), {
        allowClear: true,
    });
});