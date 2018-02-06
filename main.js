(function () {
    function renderScene(data) {
        $("#main-panel > pre").css("color", data.default_colors.color)
            .css("background", data.default_colors.background)
            .html(data.html);
    }

    $(function () {
        $.get("logic.php", renderScene, "json");

        $("#main-panel").on("click", ".scene-option", function () {
            let optionId = $(this).data("id");

            $.get("logic.php?", { opt: optionId }, renderScene, "json");
        });
    });
})();