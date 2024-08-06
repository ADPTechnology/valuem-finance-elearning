import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import { setActiveCheckbox } from "../../common/js/utils.js";

$(() => {
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

    function stepsTableM(ele, lang, url) {
        var stepsTable = ele.DataTable({
            responsive: true,
            language: lang,
            serverSide: true,
            processing: true,
            ajax: {
                url: url,
                data: {
                    type: "table",
                },
            },
            columns: [
                { data: "id", name: "id" },
                { data: "title", name: "title" },
                { data: "description", name: "description" },
                { data: "type", name: "type" },
                {
                    data: "order",
                    name: "order",
                },
                { data: "active", name: "active" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "action-with text-center",
                },
            ],
            order: [4, "asc"],
            dom: "rtip",
        });

        return stepsTable;
    }

    setActiveCheckbox(".step-status-checkbox", ".txt-step-description-status");
    var stepsTable;

    $("html").on(
        "click",
        ".instances-section-box .title-container",
        function () {
            var instanceBox = $(this).closest(".instances-section-box");

            if (!instanceBox.hasClass("active")) {
                instanceBox.addClass("active").attr("data-active", "active");
                instanceBox
                    .siblings()
                    .removeClass("active")
                    .attr("data-active", "");
                var url = instanceBox.data("table");

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        type: "html",
                    },
                    dataType: "JSON",
                    success: function (data) {
                        var StepsBox = $("#steps-list-container");
                        var topTableStep = $("#top-steps-table-title-info");

                        topTableStep.html(
                            '<span class="text-bold"> de: </span> \
                                        <span class="title-chapter-top-table">' +
                                data.title +
                                "</span>"
                        );

                        StepsBox.html(data.html);

                        var eventsTableEle = $("#instances-table");

                        stepsTable = stepsTableM(
                            eventsTableEle,
                            DataTableEs,
                            url
                        );

                        $("#editOrderSelect").select2({
                            dropdownParent: "#editFcStepForm",
                            minimumResultsForSearch: -1,
                        });
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            }
        }
    );

    // edit forgetting in view

    if ($("#editForgettingCurveForm").length) {
        var editForgettingCurveForm = $("#editForgettingCurveForm").validate({
            rules: {
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
                image: {
                    required: true,
                },
            },
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
                            if (data.show) {
                                editForgettingCurveForm.resetForm();
                                form.trigger("reset");

                                $("#forgettingCurve-box-container").html(
                                    data.html
                                );
                                $(
                                    "#forgettingCurve-description-text-principal"
                                ).html(data.title);

                                Toast.fire({
                                    icon: "success",
                                    text: data.message,
                                });
                            }
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

    /* ----------- DELETE FORGETTING CURVE ---------------*/

    $(".main-content").on("click", ".deleteForgettingCurve", function () {
        var url = $(this).data("url");

        SwalDelete.fire().then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        method: "DELETE",
                        url: url,
                        data: {
                            place: "show",
                        },
                        dataType: "JSON",
                        success: function (result) {
                            if (result.success === true) {
                                window.location.href = result.route;
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    text: result.message,
                                });
                            }
                        },
                        error: function (result) {
                            Toast.fire({
                                icon: "error",
                                title: "¡Ocurrió un error inesperado!",
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

    /* --------------- EDIT STEP --------------- */

    if ($("#editFcStepForm").length) {
        var inputStepEdit = $("#step-image-register");
        inputStepEdit.on("change", function () {
            var img_path = $(this)[0].value;
            var img_holder = $(this)
                .closest("#editFcStepForm")
                .find(".img-holder");
            var currentImagePath = $(this).data("value");
            var img_extension = img_path
                .substring(img_path.lastIndexOf(".") + 1)
                .toLowerCase();

            if (
                img_extension == "jpeg" ||
                img_extension == "jpg" ||
                img_extension == "png"
            ) {
                if (typeof FileReader != "undefined") {
                    img_holder.empty();
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("<img/>", {
                            src: e.target.result,
                            class: "img-fluid course_img",
                        }).appendTo(img_holder);
                    };
                    img_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    $(img_holder).html(
                        "Este navegador no soporta Lector de Archivos"
                    );
                }
            } else {
                $(img_holder).html(currentImagePath);
                Toast.fire({
                    icon: "warning",
                    title: "¡Selecciona una imagen!",
                });
            }
        });

        var editFcStepForm = $("#editFcStepForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
                description: {
                    maxlength: 100,
                    required: false,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editFcStepModal");

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
                            editFcStepForm.resetForm();
                            form.trigger("reset");

                            stepsTable.ajax.reload();

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

        $("html").on("click", ".editStep-btn", function () {
            var button = $(this);
            var getDataUrl = button.data("send");
            var url = button.data("url");
            var modal = $("#editFcStepModal");
            var form = modal.find("#editFcStepForm");
            let orderSelect = form.find("#editOrderSelect");

            orderSelect.empty();

            editFcStepForm.resetForm();

            form.trigger("reset");
            form.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let step = data.step;

                    form.find("input[name=title]").val(step.title);
                    form.find("input[name=description]").val(step.description);

                    let types = {
                        video: "Video",
                        reinforcement: "Reforzamiento",
                        evaluation: "Evaluación",
                    };

                    form.find("#type").text(types[step.type]);

                    $.each(data.orders_steps, function (key, value) {
                        orderSelect.append(
                            '<option value"' +
                                value.order +
                                '">' +
                                value.order +
                                "</option>"
                        );
                    });

                    orderSelect.val(step.order).change();

                    modal
                        .find(".img-holder")
                        .html(
                            '<img class="img-fluid course_img" id="image-course-edit" src="' +
                                data.url_img +
                                '"></img>'
                        );
                    modal
                        .find("#step-image-register")
                        .attr(
                            "data-value",
                            '<img scr="' +
                                data.url_img +
                                '" class="img-fluid course_img"'
                        );
                    modal.find("#step-image-register").val("");

                    if (step.active === "S") {
                        form.find(".step-status-checkbox").prop(
                            "checked",
                            true
                        );
                        form.find(".txt-step-description-status").html(
                            "Activo"
                        );
                    } else {
                        form.find(".step-status-checkbox").prop(
                            "checked",
                            false
                        );
                        form.find(".txt-step-description-status").html(
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
});
