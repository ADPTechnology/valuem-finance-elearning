import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import { setActiveCheckbox, InitAjaxSelect2 } from "../../common/js/utils.js";

$(() => {
    setActiveCheckbox(
        ".curve-status-checkbox",
        ".txt-curve-description-status"
    );

    const FORGETTING_CURVE_RULES = {
        title: {
            required: true,
            maxlength: 100,
        },
        description: {
            maxlength: 100,
            required: false,
        },
        min_score: {
            required: true,
            number: true,
            max: 20,
            min: 1,
        },
        type_course: {
            required: true,
        },
        "courses_id[]": {
            required: true,
        },
        image: {
            required: true,
        },
    };

    const TYPE_COURSE_SELECT_CONFIG = {
        placeholder: "Selecciona un tipo de curso",
    };
    const COURSE_SELECT_CONFIG = {
        placeholder: "Selecciona uno o más cursos",
        multiple: true,
        closeOnSelect: false,
    };

    const IMAGE_FILEPOND_CONFIG = {
        allowMultiple: false,
        name: "image",
        dropValidation: true,
        storeAsFile: true,
        allowReplace: true,
        labelIdle:
            '<i class="fa-solid fa-images me-2"></i> \
                        Arrastra y suelta una imagen o \
                        <span class="filepond--label-action"> Explora </span>',
        checkValidity: true,
        acceptedFileTypes: ["image/*"],
        labelFileTypeNotAllowed: "Este tipo de archivo no es válido",
        fileValidateTypeLabelExpectedTypes: "Se espera {lastType}",
        credits: false,
    };

    $(".forgetting-curve-image-input").filepond(IMAGE_FILEPOND_CONFIG);

    InitAjaxSelect2("#typeCourseSelect", TYPE_COURSE_SELECT_CONFIG, "name", {
        column: "type",
    });
    InitAjaxSelect2("#courseSelect", COURSE_SELECT_CONFIG, "description", {
        column: "course",
    });

    $("html").on("change", "#typeCourseSelect", function () {
        var ID = $(this).val();
        var course_select = $("#courseSelect");
        course_select.val([]).change();
        InitAjaxSelect2("#courseSelect", COURSE_SELECT_CONFIG, "description", {
            column: "course",
            type_id: ID,
        });
    });

    // Table forgetting Curve

    var forgettingCurve = $("#forgetting-curve-table");
    var getDataUrl = forgettingCurve.data("url");
    var forgettingCurveTable = forgettingCurve.DataTable({
        responsive: true,
        language: DataTableEs,
        serverSide: true,
        processing: true,
        ajax: getDataUrl,
        columns: [
            { data: "id", name: "id" },
            { data: "title", name: "title" },
            { data: "description", name: "description" },
            { data: "min_score", name: "min_score" },
            { data: "course_type", name: "course_type" },
            { data: "course_name", name: "course_name" },
            {
                data: "active",
                name: "active",
                orderable: false,
                searchable: false,
            },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
        order: [[0, "desc"]],
    });

    // Create forgetting curve

    if ($("#registerForgettingCurveForm").length) {
        var registerForgettingCurveForm = $(
            "#registerForgettingCurveForm"
        ).validate({
            rules: FORGETTING_CURVE_RULES,
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#RegisterForgettingCurveModal");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: new FormData(form[0]),
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        console.log(data);

                        if (data.success) {
                            registerForgettingCurveForm.resetForm();
                            forgettingCurveTable.draw();
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
                        // COURSE_SELECT.val(null).trigger("change");
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

    // Edit Group Participant

    if ($("#editForgettingCurveForm").length) {
        var editForgettingCurveForm = $("#editForgettingCurveForm").validate({
            rules: FORGETTING_CURVE_RULES,
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editForgettingCurveModal");

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
                            editForgettingCurveForm.resetForm();

                            form.trigger("reset");
                            forgettingCurveTable.draw();
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

        $("html").on("click", ".editForgettingCurve-btn", function () {
            var button = $(this);
            var getDataUrl = button.data("send");
            var url = button.data("url");
            var modal = $("#editForgettingCurveModal");
            var form = modal.find("#editForgettingCurveForm");

            editForgettingCurveForm.resetForm();

            var imageInput = form.find("#forgetting-curve-image-input");
            imageInput.filepond("removeFiles");

            form.trigger("reset");
            form.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    $("#courseDescription").empty();

                    let forgettingCurve = data.data;
                    let image = data.image;

                    imageInput.filepond("addFile", decodeURI(image));

                    $.each(forgettingCurve, function (key, value) {
                        let input = form.find("[name=" + key + "]");
                        if (input) {
                            input.val(value);
                        }
                    });

                    var courses_ul = $("<ul/>");
                    $.each(forgettingCurve.courses, function (key, value) {
                        var sub_li = $("<li/>").html(value["description"]);
                        courses_ul.append(sub_li);
                    });
                    $("#courseDescription").append(courses_ul);

                    if (forgettingCurve.active == "S") {
                        form.find(".curve-status-checkbox").prop(
                            "checked",
                            true
                        );
                        form.find(".txt-curve-description-status").html(
                            "Activo"
                        );
                    } else {
                        form.find(".curve-status-checkbox").prop(
                            "checked",
                            false
                        );
                        form.find(".txt-curve-description-status").html(
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

    // Delete forgetting curve

    $("html").on("click", ".deleteForgettingCurve-btn", function () {
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
                                forgettingCurveTable.draw();

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
                            // COURSE_SELECT.val(null).trigger("change");
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
});
