(function () {
    function renderScene(data) {
        let animationInEnded = false;

        $("#main-panel > pre").empty()
            .css("color", data.colors.color)
            .css("background", data.colors.background)
            .html(data.html);

        if (data.in_anim !== null)
            $("#main-panel > pre")
                .addClass("animated " + data.in_anim)
                .one("webkitAnimationEnd oanimationend msAnimationEnd animationend", function (e) {

                    animationInEnded = true;
                    $(this).removeClass("animated " + data.in_anim);
                });
        else
            animationInEnded = true;

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
            })
            .click(function () {
                if (!animationInEnded)
                    return;

                let optionId = $(this).data("id");

                if (data.out_anim)
                    $(this).parent().addClass("animated " + data.out_anim)
                        .one("webkitAnimationEnd oanimationend msAnimationEnd animationend", function (e) {
                            $(this).removeClass("animated " + data.out_anim)
                                .empty();

                            $.get("logic.php?", { scene_opt: optionId }, renderScene, "json");
                        });
                else
                    $.get("logic.php?", { scene_opt: optionId }, renderScene, "json");
            });
    }

    // start
    $(function () {
        $.get("logic.php", renderScene, "json");
    });
})();