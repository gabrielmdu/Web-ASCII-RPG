(function () {
    function renderScene(data) {
        $("#main-panel > pre").css("color", data.colors.color)
            .css("background", data.colors.background)
            .html(data.html);

        $(".scene-title").css("color", data.colors.title_color)
            .css("background", data.colors.title_background);

        $(".scene-img").css("color", data.colors.image_color)
            .css("background", data.colors.image_background);

        $(".scene-text").css("color", data.colors.text_color)
            .css("background", data.colors.text_background);

        $(".scene-option").css("color", data.colors.option_color)
            .css("background", data.colors.option_background)
            .mouseover(function () {
                $(this).css("color", data.colors.option_hover_color)
                    .css("background", data.colors.option_hover_background);
            })
            .mouseleave(function () {
                $(this).css("color", data.colors.option_color)
                    .css("background", data.colors.option_background);
            });
    }

    $(function () {
        $.get("logic.php", renderScene, "json");

        $("#main-panel").on("click", ".scene-option", function () {
            let optionId = $(this).data("id");

            $.get("logic.php?", { opt: optionId }, renderScene, "json");
        });
    });
})();