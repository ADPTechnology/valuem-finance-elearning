$(() => {

    $("html").on(
        {
            mouseenter: function () {
                var ele = $(this);
                let main_stripper = ele.closest("#sidebar-wrapper");

                ele.css('width', '270px')
                main_stripper.css('width', '270px')
            },
            mouseleave: function () {
                var ele = $(this);
                let main_stripper = ele.closest("#sidebar-wrapper");

                ele.css('width', '70px')
                main_stripper.css('width', '70px')
            },
        },
        "body.sidebar-mini .main-sidebar .sidebar-menu > li"
    );

})


