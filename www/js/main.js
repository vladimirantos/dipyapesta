$(document).ready(function () {
    $(".ClickMe").click(function (e) {
        e.preventDefault();
        $a = $(this).find("a");
        window.location.href = $a.prop('href');
    });
    $("#menuBtn").click(function (e) {
        ul = $("#menuUl");
        nav = $("nav");
        if (ul.hasClass("active")) {
            ul.removeClass("active");
            ul.css("display", "none");
            nav.css("height", "50px");
            $(this).css("padding-bottom", "0");
        } else {
            ul.addClass("active");
            ul.css("display", "block");
            nav.css("height", "260px");
            $(this).css("padding-bottom", "10px");
        }
    });

    setTimeout(function ()
    {
        $(".flash").hide(100);
    }, 5000);
});
