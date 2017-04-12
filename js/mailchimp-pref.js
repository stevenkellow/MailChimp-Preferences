jQuery(document).ready(function($) {
    // Call in parsley
    $('.mailchimp-pref-form').parsley();
    
    // Run the tabs
    $(".mailchimp-tabs-menu a").click(function(event) {
        event.preventDefault();
        $(this).parent().addClass("current");
        $(this).parent().siblings().removeClass("current");
        var tab = $(this).attr("href");
        $(".mailchimp-tab-content").not(tab).css("display", "none");
        $(tab).fadeIn();
    });
});