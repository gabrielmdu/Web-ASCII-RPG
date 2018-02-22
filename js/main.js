(function () {
    // jQuery plugin to add animation to the element
    $.fn.addAnimation = function (time, name) {
        $("#main-panel").css("overflow", "hidden");
        
        this.css({
            "animation-duration": time,
            "animation-name": name
        });
        return this;
    };

    // jQuery plugin to remove animation to the element
    $.fn.delAnimation = function () {
        $("#main-panel").css("overflow", "");

        this.css({
            "animation-duration": "",
            "animation-name": ""
        });
        return this;
    };

    // shows the scene and adds its colors and animations
    function renderScene(data) {
        let animationInEnded = false;

        $("#main-panel pre").empty()
            .css("color", data.colors.color)
            .css("background", data.colors.background)
            .html(data.html);

        if (data.in_anim !== null)
            $("#main-panel pre")
                .addAnimation("2s", data.in_anim)
                .on("webkitAnimationEnd oanimationend msAnimationEnd animationend", function (e) {

                    animationInEnded = true;
                    $(this).delAnimation()
                        .off("webkitAnimationEnd oanimationend msAnimationEnd animationend");
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
                    $(this).parent().addAnimation("2s", data.out_anim)
                        .on("webkitAnimationEnd oanimationend msAnimationEnd animationend", function (e) {
                            $(this).delAnimation()
                                .off("webkitAnimationEnd oanimationend msAnimationEnd animationend")
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