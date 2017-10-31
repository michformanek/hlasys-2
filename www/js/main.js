$(document).ready(function() {
    $('[data-toggle=offcanvas]').click(function() {
        $('.row-offcanvas').toggleClass('active');
    });
});

$(function () {
    $.nette.init();
});

$("body").on("submit", "form.ajax", function () {
    $(this).ajaxSubmit();
    return false;
});
