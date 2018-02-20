(function () {
    function renderScene(data) {
        $("#main-panel > pre").css("color", data.colors.color)
            .css("background", data.colors.background)
            .html(data.html)
            .addClass("animated fade-in")
            .one("webkitAnimationEnd oanimationend msAnimationEnd animationend", function (e) {

                $(this).removeClass("animated fade-in");
            });

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

        $("#main-panel > pre").on("click", ".scene-option", function () {
            let optionId = $(this).data("id");

            $(this).parent().addClass("animated fade-out")
                .one("webkitAnimationEnd oanimationend msAnimationEnd animationend", function (e) {
                    $(this).removeClass("animated fade-out")
                        .empty();

                    $.get("logic.php?", { scene_opt: optionId }, renderScene, "json");
                });

            //$.get("logic.php?", { scene_opt: optionId }, renderScene, "json");
        });
    });
})();