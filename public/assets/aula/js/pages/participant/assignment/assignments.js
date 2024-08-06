import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../../common/js/sweet-alerts.js";

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
                        summernoteElement.summernote("isEmpty") ? "" : contents
                    );
                    cardForm.element(summernoteElement);
                },
            },
        };
    }

    $("html").on(
        "click",
        ".table .view_part_assignment-bnt .view_part_assignment",
        function () {
            let button = $(this);
            let sendUrl = button.data("send");
            let url = button.data("url");
            let modal = $("#ViewAssignmentModal");

            $.ajax({
                type: "GET",
                url: sendUrl,
                dataType: "JSON",
                success: function (data) {
                    let title = data.title;
                    let html = data.html;

                    modal.find(".title-assignment").text(title);
                    modal.find("#body-assignment").html(html);

                    //  * ------------ REGISTER ---------------- *

                    // register

                    let summernoteElement = $("#card-content-register");

                    $("#storageFileAssignmentForm").attr("action", url);

                    let registerFileAssignment = $(
                        "#storageFileAssignmentForm"
                    ).validate({
                        rules: {
                            content: {
                                maxlength: 6000,
                                required: false,
                            },
                            "files[]": {
                                required: true,
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
                            var loadSpinner = form.find(".loadSpinnerClean");
                            var modal = $("#ViewAssignmentModal");

                            loadSpinner.toggleClass("active");
                            form.find(".btn-save").attr("disabled", "disabled");

                            let formdata = new FormData(form[0]);

                            $.ajax({
                                method: form.attr("method"),
                                url: form.attr("action"),
                                data: formdata,
                                processData: false,
                                contentType: false,
                                dataType: "JSON",
                                success: function (data) {
                                    if (data.success) {
                                        registerFileAssignment.resetForm();

                                        form.trigger("reset");

                                        // destroy summerNote
                                        $("#card-content-register").summernote(
                                            "destroy"
                                        );
                                        modal
                                            .find("textarea[name=content]")
                                            .empty();

                                        Toast.fire({
                                            icon: "success",
                                            text: data.message,
                                        });

                                        let html = data.html;

                                        modal
                                            .find("#body-assignment")
                                            .html(html);

                                        // create summerNote
                                        $("#card-content-register").summernote(
                                            getSummernoteConfig(
                                                $("#card-content-register"),
                                                registerFileAssignment
                                            )
                                        );
                                        modal
                                            .find("textarea[name=content]")
                                            .empty();
                                    } else {
                                        Toast.fire({
                                            icon: "error",
                                            text: data.message,
                                        });
                                    }
                                },
                                complete: function (data) {
                                    loadSpinner.toggleClass("active");
                                    form.find(".btn-save").removeAttr(
                                        "disabled"
                                    );
                                },
                                error: function (data) {
                                    console.log(data);
                                    ToastError.fire();
                                },
                            });
                        },
                    });

                    // * ------------ DELETE ---------------- *

                    $("#ViewAssignmentModal").on(
                        "click",
                        ".delete-file-participant-btn",
                        function () {
                            let button = $(this);

                            let a = button.parent().find("a");

                            let url = button.data("url");
                            let loadSpinner = $(this).find(".loadSpinner");

                            SwalDelete.fire().then(
                                function (e) {
                                    if (e.value === true) {
                                        loadSpinner.toggleClass("active");
                                        button.attr("disabled", "disabled");

                                        a.removeAttr("href", "href");
                                        a.addClass(
                                            "link-download-file-disabled"
                                        );

                                        $.ajax({
                                            type: "DELETE",
                                            url: url,
                                            dataType: "JSON",
                                            success: function (data) {
                                                if (data.success) {
                                                    let html = data.html;

                                                    // destroy summerNote
                                                    $(
                                                        "#card-content-register"
                                                    ).summernote("destroy");
                                                    modal
                                                        .find(
                                                            "textarea[name=content]"
                                                        )
                                                        .empty();

                                                    modal
                                                        .find(
                                                            "#body-assignment"
                                                        )
                                                        .html(html);

                                                    // create summerNote
                                                    $(
                                                        "#card-content-register"
                                                    ).summernote(
                                                        getSummernoteConfig(
                                                            $(
                                                                "#card-content-register"
                                                            ),
                                                            registerFileAssignment
                                                        )
                                                    );
                                                    modal
                                                        .find(
                                                            "textarea[name=content]"
                                                        )
                                                        .empty();

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
                                            complete: function () {
                                                $(".btn-save").removeAttr(
                                                    "disabled"
                                                );
                                                loadSpinner.toggleClass(
                                                    "active"
                                                );
                                                button.removeAttr(
                                                    "disabled",
                                                    "disabled"
                                                );
                                            },
                                            error: function (data) {
                                                ToastError.fire();
                                            },
                                        });
                                    } else {
                                        e.dismiss;
                                    }
                                },
                                function (dismiss) {
                                    return false;
                                }
                            );
                        }
                    );

                    // * ------------ DESTROY SUMMERNOTE ---------------- *

                    $("#ViewAssignmentModal").on("show.bs.modal", function () {
                        $("#card-content-register").summernote(
                            getSummernoteConfig(
                                $("#card-content-register"),
                                registerFileAssignment
                            )
                        );
                        $(this).find("textarea[name=content]").empty();
                    });

                    $("#ViewAssignmentModal").on(
                        "hidden.bs.modal",
                        function () {
                            $("#card-content-register").summernote("destroy");
                            $(this).find("textarea[name=content]").empty();
                        }
                    );
                },
                complete: function () {
                    modal.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }
    );
});
