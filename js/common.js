$(document).ready(function() {
    var toggleDel = 0;
    var navCount = 0;

    $(window).resize(function() {
        if ($(document).width() > 999) {
            $("#nav-links").css("display", "block");
        } else {
            $("#nav-links").css("display", "none");
        }
    });

    $(".btn-del").css("pointer-events", "none");
    $(".btn-del").css("pointer", "default");

    $("#enable-delete").click(function() {
        if (toggleDel == 0) {
            $(".btn-del").css("pointer-events", "all");
            $(".btn-del").css("cursor", "pointer");
            toggleDel = 1;
            $("#enable-delete").text("Disable Delete");
        } else {
            $(".btn-del").css("pointer-events", "none");
            $(".btn-del").css("cursor", "default");
            toggleDel = 0;
            $("#enable-delete").text("Enable Delete");
        }
    });

    $("#menu").click(function() {
        if (navCount == 0) {
            $("#nav-links").css("display", "block");
            navCount = 1;
        } else {
            $("#nav-links").css("display", "none");
            navCount = 0;
        }
    });

    $(".btn-close").click(function() {
        $(".signup-modal").css("display", "none");
        $(".login-modal").css("display", "none");
        $(".modal-container").css("display", "none");
    });

    $("#modal-btn-signup").click(function() {
        $(".modal-container").css("display", "block");
        $(".signup-modal").css("display", "block");
        $(".signup-modal").css("animation", "modal-form-anim .5s ease-in-out");
    });

    $("#modal-btn-login").click(function() {
        $(".modal-container").css("display", "block");
        $(".login-modal").css("display", "block");
        $(".login-modal").css("animation", "modal-form-anim .5s ease-in-out");
    });
});