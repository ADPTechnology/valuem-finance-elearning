import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";

$(() => {
    /*-------------- INSTRUCTOR INFORMATION MODAL -----------------*/

    if ($("#viewInstructorInformationModal").length) {

        function getSummernoteConfig(summernoteElement, cardForm) {
            return {
                dialogsInBody: true,
                minHeight: 160,
                disableDragAndDrop: true,
                dialogsFade: true,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["fontname", ["fontname"]],
                    ["color", ["color"]],
                    ["insert", ["link"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["height", ["height"]],
                ],
                lang: "es-ES",
                lineHeights: ["1.2", "1.4", "1.6", "1.8", "2.0", "3.0"],
                callbacks: {
                    onChange: function (contents, $editable) {
                        summernoteElement.val(
                            summernoteElement.summernote("isEmpty")
                                ? ""
                                : contents
                        );
                        cardForm.element(summernoteElement);
                    },
                },
            };
        }

        $("html").on("click", ".showInformationInstructor-btn", function () {
            let button = $(this);
            let getDataUrl = button.data("send");
            let getUrlUpdate = button.data("url");
            let modal = $("#viewInstructorInformationModal");

            $("#UpdateInformationForm").trigger("reset");

            $.ajax({
                method: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let instructor = data.instructor;

                    let instructorName = instructor.name;

                    let html = data.html;

                    let instructorNameCont = modal.find(
                        ".modal_instructor_name"
                    );
                    instructorNameCont.html(instructorName);

                    let bodyInformationInstructor = modal.find(
                        ".informationInstructor"
                    );

                    bodyInformationInstructor.html(html);

                    modal
                        .find("textarea[name=content]")
                        .val(instructor.user_detail.content);

                    $("#UpdateInformationForm").attr("action", getUrlUpdate);

                    var UpdateInformationForm = $(
                        "#UpdateInformationForm"
                    ).validate({
                        rules: {
                            content: {
                                maxlength: 6000,
                                required: true,
                            },
                            courses_count: {
                                required: false,
                                number: true,
                                min: 0,
                                max: 999,
                            },
                            facebook_link: {
                                required: false,
                                url: true,
                            },
                            linkedin_link: {
                                required: false,
                                url: true,
                            },
                            instagram_link: {
                                required: false,
                                url: true,
                            },
                            pag_web_link: {
                                required: false,
                                url: true,
                            },
                            email: {
                                required: false,
                                email: true,
                            },
                        },
                        errorElement: "label",
                        errorClass: "is-invalid",
                        validClass: "is-valid",
                        ignore: ":hidden:not(.summernote-card-editor),.note-editable.card-block",
                        errorPlacement: function (error, element) {
                            error.addClass("error");
                            if (element.prop("type") === "checkbox") {
                                error.insertAfter(element.siblings("label"));
                            } else if (element.hasClass("summernote")) {
                                error.insertAfter(
                                    element.siblings(".note-editor")
                                );
                            } else {
                                error.insertAfter(element);
                            }
                        },
                        submitHandler: function (form, event) {
                            event.preventDefault();

                            var form = $(form);
                            var loadSpinner = form.find(".loadSpinner");
                            var modal = $("#viewInstructorInformationModal");

                            loadSpinner.toggleClass("active");
                            form.find(".btn-save").attr("disabled", "disabled");

                            var formData = new FormData(form[0]);

                            $.ajax({
                                method: form.attr("method"),
                                url: form.attr("action"),
                                data: formData,
                                processData: false,
                                contentType: false,
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.success) {
                                        // destroy summerNote
                                        $("#card-content-register").summernote(
                                            "destroy"
                                        );
                                        modal
                                            .find("textarea[name=content]")
                                            .empty();

                                        let html = data.html;

                                        modal
                                            .find(".informationInstructor")
                                            .html(html);

                                        modal
                                            .find("textarea[name=content]")
                                            .val(data.content);

                                        // create summerNote
                                        $("#card-content-register").summernote(
                                            getSummernoteConfig(
                                                $("#card-content-register"),
                                                UpdateInformationForm
                                            )
                                        );
                                        modal.find("textarea[name=content]").empty();

                                        Toast.fire({
                                            icon: "success",
                                            text: data.message,
                                        });
                                    } else {
                                        Toast.fire({
                                            icon: "error",
                                            text: data.message,
                                        });
                                    }
                                },
                                complete: function (data) {
                                    form.find(".btn-save").removeAttr(
                                        "disabled"
                                    );
                                    loadSpinner.toggleClass("active");
                                },
                                error: function (data) {
                                    ToastError.fire();
                                },
                            });
                        },
                    });

                    // * ------------ DESTROY SUMMERNOTE ---------------- *

                    $("#viewInstructorInformationModal").on(
                        "show.bs.modal",
                        function () {
                            $("#card-content-register").summernote(
                                getSummernoteConfig(
                                    $("#card-content-register"),
                                    UpdateInformationForm
                                )
                            );
                            $(this).find("textarea[name=content]").empty();
                        }
                    );
                    $("#viewInstructorInformationModal").on(
                        "hidden.bs.modal",
                        function () {
                            $("#card-content-register").summernote("destroy");
                            $(this).find("textarea[name=content]").empty();
                        }
                    );
                },
                complete: function (data) {
                    modal.modal("show");
                },
                error: function (data) {
                    ToastError.fire();
                },
            });
        });
    }
});
