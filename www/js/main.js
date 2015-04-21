$(document).ready(function () {
    $(".ClickMe").click(function (e) {
        e.preventDefault();
        $a = $(this).find("a");
        window.location.href = $a.prop('href');
    });
});
