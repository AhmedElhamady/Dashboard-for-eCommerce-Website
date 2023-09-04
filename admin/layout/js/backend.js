$(function () {
    "use strict";
    // hide placeholder on focus
    $("[placeholder]").focus(function () {
        $(this).attr("data-text", $(this).attr("placeholder"));
        $(this).attr("placeholder", "");
    }).blur(function () {
        $(this).attr("placeholder", $(this).attr("data-text"));
    })

    // add astrisks on required inputs
    $("input").each(function () {
        if ($(this).attr("required") == "required") {
            $(this).after("<span class='astrisk'>*</span>");
        }
    })

    // show password
    var passField = $(".password");
    var show = true;
    $(".show-pass").click(function () {
        if (show == true) {
            passField.attr("type", "text");
            $(".show-pass").removeClass('fa-eye');
            $(".show-pass").addClass('fa-eye-slash');
            show = false;
        } else {
            passField.attr("type", "password");
            $(".show-pass").removeClass('fa-eye-slash');
            $(".show-pass").addClass('fa-eye');
            show = true;
        }
    })

    // confirm on click
    $(".confirm").click(function(){
        return confirm("Are you sure to delete it");
    })

    // categoty custom view
    $(".cat h3").click(function(){
        $(this).next(".full-view").fadeToggle(200);
    });
    $(".options span").click(function(){
        $(this).addClass("active").siblings("span").removeClass("active");
        if($(this).data("view")=="full") {
            $(".cat .full-view").fadeIn(200);
        } else {
            $(".cat .full-view").fadeOut(200);
        }
    });

    // hide and show latest
    $(".show-info").click(function(){
        $(this).toggleClass("selected").parent().next(".panel-body").fadeToggle(100);
        if($(this).hasClass("selected")) {
            $(this).html("<i class='fa fa-minus'></i>");
        } else {
            $(this).html("<i class='fa fa-plus'></i>");
        }
    })
    
});