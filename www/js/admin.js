$(document).ready(function () {
    $(".ClickMe td").click(function (e) {
        if (!$(this).hasClass("noClick")) {
            e.preventDefault();
            $a = $(this).parent(".ClickMe").find("a");
            window.location.href = $a.prop('href');
        }
    });
    setTimeout(function ()
    {
        $(".flash").hide(100);
    }, 5000);

    var int = self.setInterval(function () {
        var count = 0;
        $("#submit .trigger").each(function () {
            if ($(this).val() !== "") {
                count++;
            }
        });
        if ($("#submit").length > 0 && count === $("#submit .trigger").length) {
            autosave();
        }
    }, 20000);


});
function autosave() {
    if ($("#submit").has($(".status"))) {
        var d = new Date();
        var t = d.toLocaleTimeString();
        $("#submit > .status").text('Automaticky uloženo: ' + t);
    }
    saveForm();
}

function saveForm(action) {
    if (action === "done") {
        $("#submit > input[name='_action']").val("done");
    } else if (action === "save" && $("#submit").has($(".status"))) {
        $("#submit > input[name='_action']").val("save");
        $("#submit > .status").text('Uloženo');
    } else {
        $("#submit > input[name='_action']").val("");
    }
    var int = self.setInterval(function () {
        $("#submit").submit();
    }, 1000);
}
