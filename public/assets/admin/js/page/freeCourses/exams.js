import DataTableEs from "../../../../common/js/datatable_es.js";
import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";

import {
    setActiveCheckbox,
    setActiveCheckBoxForResult,
} from "../../../../common/js/utils.js";

$(() => {
    setActiveCheckbox(
        "#register-exam-status-checkbox",
        "#txt-register-description-exam"
    );
    setActiveCheckbox(
        "#edit-exam-free-course-status-checkbox",
        "#txt-edit-description-exam-free-course"
    );

    // DATA TABLE EXAMS

    let examsDataTableEle = $("#exams-free-courses-table");
    let getDataUrl = examsDataTableEle.data("url");
    let examsTable = examsDataTableEle.DataTable({
        responsive: true,
        language: DataTableEs,
        serverSide: true,
        processing: true,
        ajax: getDataUrl,
        columns: [
            { data: "id", name: "id" },
            { data: "title", name: "title" },
            { data: "duration", name: "duration" },
            { data: "active", name: "active" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
    });

    // STORE EXAM

    let registerFreeCoursesExamsForm = $(
        "#store-free-courses-exams-form"
    ).validate({
        rules: {
            title: {
                required: true,
            },
            exam_time: {
                required: true,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();

            var form = $(form);
            let button = form.find("button[type=submit]");
            let loadSpinner = button.find(".loadSpinner");
            let modal = $("#RegisterExamModal");

            loadSpinner.toggleClass("active");
            form.find(".btn-save").attr("disabled", "disabled");

            let formData = form.serializeArray();

            $.ajax({
                method: form.attr("method"),
                url: form.attr("action"),
                data: formData,
                success: function (response) {
                    if (response.success) {
                        examsTable.draw();
                        form.trigger("reset");
                        Toast.fire({
                            icon: "success",
                            text: response.message,
                        });
                    } else {
                        Toast.fire({
                            icon: "error",
                            text: response.message,
                        });
                    }
                },
                complete: function () {
                    loadSpinner.toggleClass("active");
                    form.find(".btn-save").removeAttr("disabled");
                    modal.modal("hide");
                    registerFreeCoursesExamsForm.resetForm();
                },
                error: function (error) {
                    ToastError.fire();
                },
            });
        },
    });

    // UPDATE EXAM

    let editExamForFreeCoursesForm = $(
        "#edit-free-courses-exams-form"
    ).validate({
        rules: {
            title: {
                required: true,
                maxlength: 100,
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
            let loadSpinner = form.find(".loadSpinner");
            let modal = $("#EditExamModal");

            loadSpinner.toggleClass("active");
            form.find(".btn-save").attr("disabled", "disabled");

            let formData = form.serializeArray();

            $.ajax({
                method: form.attr("method"),
                url: form.attr("action"),
                data: $.param(formData),
                dataType: "JSON",
                success: function (data) {
                    if (data.success) {

                        form.trigger("reset");
                        examsTable.draw();
                        editExamForFreeCoursesForm.resetForm();

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

    // EDIT EXAM

    $("html").on("click", ".editExam-btn", function () {
        let button = $(this);
        let getDataUrl = button.data("send");
        let url = button.data("url");
        var modal = $("#EditExamModal");
        var form = modal.find("#edit-free-courses-exams-form");

        form.trigger("reset");
        form.attr("action", url);

        $.ajax({
            type: "GET",
            url: getDataUrl,
            dataType: "JSON",
            success: function (data) {
                let exam = data.exam;

                modal.find("#titleExam").text(exam.title);
                form.find("input[name=title]").val(exam.title);
                form.find("input[name=exam_time]").val(exam.exam_time);

                setActiveCheckBoxForResult(
                    form,
                    "#edit-exam-free-course-status-checkbox",
                    "#txt-edit-description-exam-free-course",
                    exam.active
                );

                modal.modal("show");
            },
            error: function (data) {
                console.log(data);
            },
        });
    });

    // DELETE EXAM

    $("html").on("click", ".deleteExam-btn", function () {
        let url = $(this).data("url");

        SwalDelete.fire().then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                examsTable.draw();
                                Toast.fire({
                                    icon: "success",
                                    text: data.message,
                                });
                            }
                        },
                        error: function (data) {
                            Toast.fire({
                                icon: "error",
                                title: data.message,
                            });
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
});
