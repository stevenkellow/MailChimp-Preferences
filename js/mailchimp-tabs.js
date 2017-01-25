jQuery(document).ready(function($) {
    $(".mailchimp-tabs-menu a").click(function(event) {
        event.preventDefault();
        $(this).parent().addClass("current");
        $(this).parent().siblings().removeClass("current");
        var tab = $(this).attr("href");
        $(".mailchimp-tab-content").not(tab).css("display", "none");
        $(tab).fadeIn();
    });
});