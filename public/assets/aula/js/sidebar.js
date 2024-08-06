
$(() => {

    $("html").on(
        {
            mouseenter: function () {
                var ele = $(this);
                let main_stripper = ele.closest("#sidebar-wrapper");
                ele.addClass("sidebar-mini");

            },
            mouseleave: function () {
                var ele = $(this);
                let main_stripper = ele.closest("#sidebar-wrapper");
                ele.removeClass("sidebar-mini");

            },
        },
        "body.sidebar-mini .main-sidebar .sidebar-menu > li"
    );

})

