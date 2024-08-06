// Information instructor

$(() => {
    $("html").on("click", ".get-information-btn", function (e) {
        let btn = $(this);
        let getUrl = btn.data("url");
        var modal = $("#informationInstructorModal");

        $.ajax({
            type: "GET",
            url: getUrl,
            dataType: "JSON",
            success: function (data) {
                let instructor = data.instructor;
                let html = data.html;

                $("#nameInstructor").text(
                    instructor.name +
                        " " +
                        instructor.paternal +
                        " " +
                        instructor.maternal
                );

                $("#container-information-instructor").html(html);
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
