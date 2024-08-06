// Information Free Courses

$(() => {
    $("html").on("click", ".get-information-btn", function (e) {
        let btn = $(this);
        let getUrl = btn.data("url");
        var modal = $("#informationFreeCoursesModal");

        $.ajax({
            type: "GET",
            url: getUrl,
            dataType: "JSON",
            success: function (data) {
                let html = data.html;

                $("#container-information-free-courses").html(html);
            },
            complete: function (data) {
                modal.modal("show");
            },
            error: function (data) {
                console.log(data);
                ToastError.fire();
            },
        });
    });
});
