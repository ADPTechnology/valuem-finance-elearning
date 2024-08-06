import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    setActiveSubmitButton,
    InitSelect2,
} from "../../common/js/utils.js";

$(function () {
    const registerLiveFreeCourseRules = {
        description: {
            required: true,
            maxlength: 255,
        },
        subtitle: {
            maxlength: 255,
        },
        date: {
            required: true,
        },
        hours: {
            required: true,
            number: true,
            step: 0.1,
        },
        time_start: {
            required: true,
        },
        time_end: {
            required: true,
        },
        image: {
            required: true,
        },
    };

    var liveFreeCoursesTable;

    setActiveCheckbox(
        ".live-freecourse-course-status-checkbox",
        ".txt-description-status-live-freecourse"
    );

    const imageFilepondConfig = {
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

    if ($("#live-free-courses-table").length) {
        var liveFreeCoursesTableEle = $("#live-free-courses-table");
        var getDataUrl = liveFreeCoursesTableEle.data("url");
        liveFreeCoursesTable = liveFreeCoursesTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "course_type", name: "course_type" },
                { data: "description", name: "description" },
                { data: "subtitle", name: "subtitle" },
                { data: "date", name: "date" },
                { data: "time_start", name: "time_start" },
                { data: "time_end", name: "time_end" },
                { data: "active", name: "active" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
            order: [[0, "desc"]],
        });

        // ------------ STORE -------------

        var formRegister = $("#registerLiveFreeCourseForm");
        var registerSubmitButton = formRegister.find("button[type=submit]");
        setActiveSubmitButton(registerSubmitButton);

        var registerLiveFreeCourseForm = $(
            "#registerLiveFreeCourseForm"
        ).validate({
            rules: registerLiveFreeCourseRules,
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var button = form.find("button[type=submit][clicked=true]");
                var loadSpinner = button.find(".loadSpinner");
                var modal = $("#RegisterLiveFreeCourseModal");

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
                        if (data.success) {
                            let imageInput = form.find(
                                ".live-free-course-image-input"
                            );
                            var dateInput = form.find("input[name=date]");

                            if (data.show) {
                                window.location.href = data.route;
                            } else {
                                if ($("#live-free-courses-table").length) {
                                    liveFreeCoursesTable.draw();
                                }
                            }

                            registerLiveFreeCourseForm.resetForm();
                            form.trigger("reset");

                            imageInput.filepond("removeFiles");

                            $.each($(".timepicker"), function (key, value) {
                                $(this).timepicker("setTime", new Date());
                            });
                            dateInput.val(moment().format("YYYY-MM-DD"));

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

    $(".live-free-course-image-input").filepond(imageFilepondConfig);

    // --------- EDIT ------------

    if ($("#editLiveFreeCourseModal").length) {
        var formEdit = $("#editLiveFreeCourseForm");
        var modalEdit = $("#editLiveFreeCourseModal");

        var editLiveFreeCourseForm = $("#editLiveFreeCourseForm").validate({
            rules: registerLiveFreeCourseRules,
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var img_holder = form.find(".img-holder");

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
                                var liveFreeCourseContainer = $(
                                    "#liveFreeCourse-box-container"
                                );
                                var titleContainer = $(
                                    "#live-free-course-description-text-principal"
                                );
                                liveFreeCourseContainer.html(data.html);
                                titleContainer.html(data.title);
                            } else {
                                liveFreeCoursesTable.ajax.reload(null, false);
                            }

                            editLiveFreeCourseForm.resetForm();
                            form.trigger("reset");

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });

                            modalEdit.modal("hide");
                        } else {
                            Toast.fire({
                                icon: "error",
                                text: data.message,
                            });
                        }
                    },
                    complete: function (data) {
                        form.find(".btn-save").removeAttr("disabled");
                        loadSpinner.toggleClass("active");
                    },
                    error: function (data) {
                        ToastError.fire();
                    },
                });
            },
        });

        $("html").on("click", ".editLiveFreeCourse", function () {
            var button = $(this);
            var url = button.data("url");
            var getDataUrl = button.data("send");

            var imageInput = formEdit.find(".live-free-course-image-input");
            imageInput.filepond("removeFiles");
            formEdit.attr("action", url);

            $.ajax({
                url: getDataUrl,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    let course = data.course;
                    let image = data.image;

                    imageInput.filepond("addFile", decodeURI(image));

                    $.each(course, function (key, value) {
                        let input = formEdit.find("[name=" + key + "]");
                        if (input) {
                            input.val(value);
                        }
                    });

                    if (course.active == "S") {
                        formEdit
                            .find(".live-freecourse-course-status-checkbox")
                            .prop("checked", true);
                        formEdit
                            .find(".txt-description-status-live-freecourse")
                            .html("Activo");
                    } else {
                        formEdit
                            .find(".live-freecourse-course-status-checkbox")
                            .prop("checked", false);
                        formEdit
                            .find(".txt-description-status-live-freecourse")
                            .html("Inactivo");
                    }

                    modalEdit.modal("show");
                },
                error: function (data) {
                    console.log(data);
                    ToastError.fire();
                },
            });
        });
    }

    // -------- DELETE ------------

    $("html").on("click", ".deleteliveFreeCourse", function () {
        var button = $(this);
        var url = button.data("url");
        var place = button.data("place");

        SwalDelete.fire().then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: {
                            place: place,
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                if (data.show) {
                                    window.location.href = data.route;
                                } else {
                                    if ($("#live-free-courses-table").length) {
                                        liveFreeCoursesTable.ajax.reload(
                                            null,
                                            false
                                        );
                                    }
                                }

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
    });

    // ------------ FILES ---------------

    if ($("#files-live-free-courses-table").length) {
        const imageFilepondConfigFiles = {
            allowMultiple: true,
            name: "files[]",
            dropValidation: true,
            storeAsFile: true,
            labelIdle:
                '<i class="fa-solid fa-paste me-2"></i> \
                            Arrastra y suelta uno o más archivos o \
                            <span class="filepond--label-action"> Explora </span>',
            checkValidity: true,
            maxFileSize: "40MB",
            maxTotalFileSize: "40MB",
            allowFileSizeValidation: true,
            labelMaxTotalFileSizeExceeded:
                "El tamaño total de los archivos es demasiado grande",
            labelMaxTotalFileSize: "El tamaño total es {filesize}",
            labelMaxFileSize: "Tamaño máximo del archivo es {filesize}",
            labelFileTypeNotAllowed: "Este tipo de archivo no es válido",
            labelMaxFileSizeExceeded: "El archivo es demasiado grande",
            labelFileTypeNotAllowed: "Este tipo de archivo no es válido",
            fileValidateTypeLabelExpectedTypes: "Se espera {lastType}",
            credits: false,
        };

        FilePond.registerPlugin(FilePondPluginFileValidateSize);

        $(".store-live-free-course-files-input").filepond(
            imageFilepondConfigFiles
        );

        var filesDataTableEle = $("#files-live-free-courses-table");
        var getDataUrl = filesDataTableEle.data("url");
        var filesTable = filesDataTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "file_path", name: "file_path" },
                { data: "file_type", name: "file_type" },
                { data: "category", name: "category" },
                { data: "created_at", name: "created_at" },
                { data: "updated_at", name: "updated_at" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
        });

        // -------- STORE ---------

        var registerLiveFreeCourseFilesForm = $(
            "#store-live-free-course-files-form"
        ).validate({
            rules: {
                "files[]": {
                    required: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var button = form.find("button[type=submit]");
                var loadSpinner = button.find(".loadSpinner");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var imageInput = form.find(
                    ".store-live-free-course-files-input"
                );

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: new FormData(form[0]),
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            var liveFreeCourseContainer = $(
                                "#liveFreeCourse-box-container"
                            );
                            liveFreeCourseContainer.html(data.html);

                            filesTable.draw();

                            form.trigger("reset");
                            imageInput.filepond("removeFiles");

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

        // --------- DELETE ---------

        $("html").on("click", ".deleteFile", function () {
            var url = $(this).data("url");

            SwalDelete.fire().then(
                function (e) {
                    if (e.value === true) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    var liveFreeCourseContainer = $(
                                        "#liveFreeCourse-box-container"
                                    );
                                    liveFreeCourseContainer.html(data.html);

                                    filesTable.ajax.reload(null, false);
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
    }

    // * -------------- EVENTS IN LIVE FREE COURSES ---------------- * //

    let eventsDataTableEle = $("#events-live-free-courses-table");
    let getDataUrlEvents = eventsDataTableEle.data("url");
    let eventsTable = eventsDataTableEle.DataTable({
        responsive: true,
        language: DataTableEs,
        serverSide: true,
        processing: true,
        ajax: getDataUrlEvents,
        columns: [
            { data: "id", name: "id" },
            { data: "description", name: "description" },
            { data: "type", name: "type" },
            { data: "date", name: "date" },
            {
                data: "exam.course.description",
                name: "exam.course.description",
            },
            { data: "user.name", name: "user.name" },
            { data: "responsible.name", name: "responsible.name" },
            { data: "flg_assist", name: "flg_assist" },
            { data: "active", name: "active" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
                className: "action-with",
            },
        ],
    });

    function infoQtyText(qty) {
        return "( ! ) Este examen tiene <b>" + qty + "</b> enunciados";
    }

    function infoScoreText(score) {
        return "( ! ) La puntuación máxima es " + score;
    }

    function initSelectEle(elements, placeholderTxt, itsOptional) {
        elements.each(function (key, value) {
            $(this).select2({
                dropdownParent: $(this).closest("form"),
                placeholder: placeholderTxt,
                allowClear: itsOptional,
            });
        });
    }

    const eventFormRules = {
        description: {
            required: true,
            maxlength: 255,
        },
        type: {
            required: true,
        },
        date: {
            required: true,
        },
        user_id: {
            required: true,
        },
        responsable_id: {
            required: true,
        },
        room_id: {
            required: true,
        },
        exam_id: {
            required: true,
        },
        questions_qty: {
            required: true,
            step: 1,
            min: 2,
        },
        min_score: {
            required: true,
            step: 1,
        },
    };

    setActiveCheckbox(".event-status-checkbox", ".txt-event-status");

    initSelectEle($(".typeSelect"), "Selecciona un tipo de evento", false);
    initSelectEle($(".instructorSelect"), "Selecciona un instructor", false);
    initSelectEle($(".responsableSelect"), "Selecciona un responsable", false);
    initSelectEle($(".roomSelect"), "Selecciona un sala", false);
    initSelectEle(
        $(".ownerCompanySelect"),
        "Selecciona una empresa titular",
        true
    );
    initSelectEle($(".examSelect"), "Selecciona un examen", false);

    // * -------------- REGISTER EVENT -------------- * //

    var registerSpecEventForm = $("#registerSpecEventForm").validate({
        rules: eventFormRules,
        submitHandler: function (form, event) {
            event.preventDefault();

            var form = $(form);
            var loadSpinner = form.find(".loadSpinner");
            var modal = $("#registerSpecEventModal");

            loadSpinner.toggleClass("active");
            form.find(".btn-save").attr("disabled", "disabled");

            var moduleActive = $("#modules-list-container")
                .find(".course-section-box.active")
                .data("id");

            var formData = form.serializeArray();
            formData.push({ name: "id", value: moduleActive });

            $.ajax({
                method: form.attr("method"),
                url: form.attr("action"),
                data: formData,
                dataType: "JSON",
                success: function (data) {
                    if (data.success) {
                        eventsTable.draw();
                        registerSpecEventForm.resetForm();
                        form.trigger("reset");

                        var modulesBox = $("#modules-list-container");
                        var specCourseBox = $("#specCourse-box-container");

                        modulesBox.html(data.htmlModule);
                        specCourseBox.html(data.htmlCourse);

                        $(".editOrderSelect").select2({
                            minimumResultsForSearch: -1,
                        });

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
                    form.find(".btn-save").removeAttr("disabled");
                    loadSpinner.toggleClass("active");
                    modal.modal("hide");
                },
                error: function (data) {
                    ToastError.fire();
                },
            });
        },
    });

    // * -------------- MODAL EVENT REGISTER -------------- * //

    var event_form_register = $("#registerSpecEventForm");
    var event_modal_register = $("#registerSpecEventModal");

    $("html").on("click", "#btn-register-spec-event-modal", function () {
        var button = $(this);
        var url = button.data("url");
        var storeUrl = button.data("store");
        var loadSpinner = button.find(".loadSpinner");

        registerSpecEventForm.resetForm();
        event_form_register.trigger("reset");

        event_form_register.find("select").each(function (key, value) {
            $(this).empty();
        });

        event_form_register
            .find("input[name=date]")
            .val(moment().format("YYYY-MM-DD"));
        event_form_register.attr("action", storeUrl);

        loadSpinner.toggleClass("active");
        button.attr("disabled", "disabled");

        $.ajax({
            type: "GET",
            url: url,
            dataType: "JSON",
            success: function (data) {
                $.each(data, function (key, value) {
                    var select = $("select[name=" + key + "]");
                    if (select) {
                        select.append("<option></option>");
                        $.each(value, function (index, val) {
                            select.append(
                                '<option value="' +
                                    Object.keys(val) +
                                    '">' +
                                    Object.values(val) +
                                    "</option>"
                            );
                        });
                    }
                });

                event_form_register
                    .find("input[name=questions_qty]")
                    .val("")
                    .attr("disabled", "disabled")
                    .addClass("input-disabled");
                event_form_register
                    .find("input[name=min_score]")
                    .val("")
                    .attr("disabled", "disabled")
                    .addClass("input-disabled");
                event_form_register.find(".info-qty-questions").html("");
                event_form_register.find(".info-min-score").html("");
            },
            complete: function (data) {
                loadSpinner.toggleClass("active");
                button.removeAttr("disabled");
                event_modal_register.modal("show");
            },
            error: function (data) {
                console.log(data);
            },
        });
    });

    $("html").on("change", ".examSelect", function () {
        var modal = $(this).closest("div.modal.fade");

        if (modal.hasClass("show")) {
            var form = $(this).closest("form");
            var qttyInput = form.find("input[name=questions_qty]");
            var minScoreInput = form.find("input[name=min_score]");
            var url = $(this).data("url");

            let infoQtyBox = form.find(".info-qty-questions");
            let infoScoreBox = form.find(".info-min-score");
            infoQtyBox.empty();
            infoScoreBox.empty();

            qttyInput.val("");
            qttyInput.attr("disabled", "disabled").addClass("input-disabled");
            minScoreInput.val("");
            minScoreInput
                .attr("disabled", "disabled")
                .addClass("input-disabled");

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    type: "qtyQuestions",
                    value: $(this).val(),
                },
                dataType: "JSON",
                success: function (data) {
                    let qty = data.qty > 10 ? 10 : data.qty;

                    infoQtyBox.html(infoQtyText(data.qty));
                    qttyInput
                        .removeAttr("disabled")
                        .removeClass("input-disabled");

                    form.validate();
                    qttyInput.rules("add", { max: data.qty });
                    qttyInput.val(qty);

                    let avg = data.avg;
                    let maxScore = Math.round(avg * qty);
                    let minScore = Math.round(maxScore * 0.7);

                    infoScoreBox.html(infoScoreText(maxScore));
                    minScoreInput
                        .removeAttr("disabled")
                        .removeClass("input-disabled");
                    minScoreInput.rules("add", { max: maxScore });
                    minScoreInput.val(minScore);
                },
                error: function (data) {
                    console.log(data);
                    ToastError.fire();
                },
            });
        }
    });
});
