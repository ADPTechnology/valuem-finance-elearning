$(function () {
    $("html").on("click", ".assignment-box .inner-btn-view", function () {
        var button = $(this);
        var parent = button.closest(".assignment-box");

        if (parent.hasClass("active")) {
            return;
        }

        var container_assign = $(".participants_assign_container");

        var url = button.data("url");

        $.ajax({
            type: "GET",
            url: url,
            dataType: "JSON",
            success: function (data) {
                container_assign.html(data.html);

                parent.siblings().removeClass("active");
                parent.addClass("active");
            },
            error: function (data) {
                console.log(data);
            },
        });
    });


    
});
