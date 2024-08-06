import { Toast, ToastError, SwalDelete } from "../../../../../common/js/sweet-alerts.js";

import { setActiveCheckbox } from "../../../../../common/js/utils.js";

export const EXAM = () => {

    setActiveCheckbox(
        "#register-exam-status-checkbox",
        "#txt-register-description-exam"
    );

    $("#registerOwnerCompanySelect").select2({
        placeholder: "Seleccione una empresa",
    });

    if ($("#registerExamForm").length) {
        var registerExamForm = $("#registerExamForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
                owner_company_id: {
                    required: true,
                },
                exam_time: {
                    required: true,
                    number: true,
                    min: 1,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#RegisterExamModal");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = form.serializeArray();

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: $.param(formData),
                    dataType: "JSON",
                    success: function (data) {

                        if (data.success) {
                            registerExamForm.resetForm();

                            $("#registerOwnerCompanySelect")
                                .val(null)
                                .trigger("change");

                            $("#exams-list-container").html(data.html);

                            form.trigger("reset");

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
                        modal.modal("hide");
                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").removeAttr("disabled");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            },
        });
    }

    // Edit exam

    if ($("#editExamForm").length) {
        var editExamForm = $("#editExamForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
                owner_company_id: {
                    required: true,
                },
                exam_time: {
                    required: true,
                    number: true,
                    min: 1,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editExamModal");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = form.serializeArray();

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: $.param(formData),
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            editExamForm.resetForm();

                            form.trigger("reset");

                            $("#exams-list-container").html(data.html);

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
                        modal.modal("hide");
                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").removeAttr("disabled");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            },
        });

        $("html").on("click", ".editExam-btn", function () {
            var button = $(this);
            var getDataUrl = button.data("send");
            var url = button.data("url");
            var modal = $("#editExamModal");
            var form = modal.find("#editExamForm");

            editExamForm.resetForm();

            form.trigger("reset");
            form.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let exam = data.exam;

                    form.find("input[name=title]").val(exam.title);
                    form.find("input[name=exam_time]").val(exam.exam_time);

                    if (exam.active == "S") {
                        form.find(".edit-exam-status-checkbox").prop(
                            "checked",
                            true
                        );
                        form.find(".txt-edit-description-exam").html("Activo");
                    } else {
                        form.find(".edit-exam-status-checkbox").prop(
                            "checked",
                            false
                        );
                        form.find(".txt-edit-description-exam").html(
                            "Inactivo"
                        );
                    }

                    modal.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });
    }

    // delete exam

    $("html").on("click", ".deleteExam-btn", function () {
        var button = $(this);
        var url = button.data("url");

        SwalDelete.fire().then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                $("#exams-list-container").html(data.html);

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
                        error: function (result) {
                            console.log(result);
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
    });
};
