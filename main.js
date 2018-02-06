(function () {
    $(function () {
        $.get("logic.php")
            .done(function (data) {
                $("#main-panel > pre").html(data);
            });

        $("#main-panel").on("click", ".scene-option", function () {
            let optionId = $(this).data("id");

            $.get("logic.php?opt=" + optionId)
                .done(function (data) {
                    $("#main-panel > pre").html(data);
                });
        });
    });
})();