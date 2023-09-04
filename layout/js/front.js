$(function () {
    "use strict";

    // switch between login and signup
    $("span.login").click(function () {
        $(this).addClass("selected-b");
        $("span.signup").removeClass("selected-g");
        $("form.login").fadeIn(500);
        $("form.signup").fadeOut(0);
    });
    $("span.signup").click(function () {
        $(this).addClass("selected-g");
        $("span.login").removeClass("selected-b");
        $("form.signup").fadeIn(500);
        $("form.login").fadeOut(0);
    });

    // hide placeholder on focus
    $("[placeholder]").focus(function () {
        $(this).attr("data-text", $(this).attr("placeholder"));
        $(this).attr("placeholder", "");
    }).blur(function () {
        $(this).attr("placeholder", $(this).attr("data-text"));
    })

    // add astrisks on required inputs
    // $("input").each(function () {
    //     if ($(this).attr("required") == "required") {
    //         $(this).after("<span class='astrisk'>*</span>");
    //     }
    // })

    // confirm on click
    $(".confirm").click(function() {
        return confirm("Are you sure to delete it");
    })

    // live preview in add item
    $(".live").keyup(function() {
        let input = $(this).data("live");
        $(".live-preview ."+input).text($(this).val());
        if ($(this).data("live")== "price") {
            $(".live-preview ."+input).text("$" + $(this).val());
        }
        if($(".live-preview ."+input).text() == "") {
            $(".live-preview ."+input).text($(".live-preview ."+input).data("name"));
        }
    })
    
});