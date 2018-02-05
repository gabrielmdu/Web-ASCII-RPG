$(function () {
    $.get("logic.php").done(function(data) {
        $("#main-panel > pre").html(data);
    });
});