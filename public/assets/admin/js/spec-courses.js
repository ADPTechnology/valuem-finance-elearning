import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    initImageChange,
    setActiveSubmitButton,
} from "../../common/js/utils.js";

$(function () {
    const registerSpecCourseRules = {
        title: {
            required: true,
            maxlength: 100,
        },
        subtitle: {
            maxlength: 100,
        },
        date: {
            required: true,
        },
        time_start: {
            required: true,
        },
        time_end: {
            required: true,
        },
    };

    var specCourseTable;

    setActiveCheckbox(
        ".specCourse-status-checkbox",
        ".txt-specCourse-description-status"
    );

    // STORE FILES

    const FILEPOND_CONFIG_FILES = {
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

    if ($("#store-spec-courses-files-form").length) {
        FilePond.registerPlugin(FilePondPluginFileValidateSize);

        $(".store-spec-courses-files-input").filepond(FILEPOND_CONFIG_FILES);

        var filesTableEle = $("#files-specCourses-table");
        var getDataUrl = filesTableEle.data("url");
        var filesTable = filesTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                {
                    data: "filename",
                    name: "filename",
                    orderable: false,
                },
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

        $(".main-content").on("click", ".deleteFile", function () {
            var url = $(this).data("url");

            SwalDelete.fire().then(
                function (e) {
                    if (e.value === true) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (result) {
                                if (result.success) {
                                    let specCourseContainer = $(
                                        "#specCourse-box-container"
                                    );
                                    specCourseContainer.html(result.html);

                                    filesTable.draw();
                                    Toast.fire({
                                        icon: "success",
                                        text: "¡Archivo eliminado!",
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

        var registerSpecCourseFilesForm = $(
            "#store-spec-courses-files-form"
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

                var filesInput = form.find(".store-spec-courses-files-input");

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: new FormData(form[0]),
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            let specCourseContainer = $(
                                "#specCourse-box-container"
                            );
                            specCourseContainer.html(data.html);

                            filesTable.draw();

                            form.trigger("reset");

                            filesInput.filepond("removeFiles");

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
    }

    // -------- INDEX ----------
    if ($("#spec-courses-table").length) {
        var specCoursesTableEle = $("#spec-courses-table");
        var getDataUrl = specCoursesTableEle.data("url");
        specCourseTable = specCoursesTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "title", name: "title" },
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

        var formRegister = $("#registerSpecCourseForm");

        //----------- CREATE ---------

        $("#RegisterSpecCourseModal").on("show.bs.modal", function () {
            var dateInput = formRegister.find("input[name=date]");

            formRegister
                .find(".specCourse-status-checkbox")
                .prop("checked", true);
            formRegister
                .find(".txt-specCourse-description-status")
                .html("Activo");
            dateInput.val(moment().format("YYYY-MM-DD"));
        });

        // ----------- STORE ----------------

        var courseImageRegister = formRegister.find(".course-image-input");
        initImageChange(courseImageRegister, formRegister, Toast);

        var registerSubmitButton = formRegister.find("button[type=submit]");
        setActiveSubmitButton(registerSubmitButton);

        var registerSpecCourseForm = $("#registerSpecCourseForm").validate({
            rules: registerSpecCourseRules,
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var button = form.find("button[type=submit][clicked=true]");
                var loadSpinner = button.find(".loadSpinner");
                var modal = $("#RegisterSpecCourseModal");
                var img_holder = form.find(".img-holder");

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
                            if (data.show) {
                                window.location.href = data.route;
                            } else {
                                if ($("#spec-courses-table").length) {
                                    specCourseTable.draw();
                                }
                            }

                            registerSpecCourseForm.resetForm();
                            form.trigger("reset");
                            $(img_holder).empty();

                            $.each($(".timepicker"), function (key, value) {
                                $(this).timepicker("setTime", new Date());
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

    // ------------ EDIT -------------

    if ($("#editSpecCourseModal").length) {
        var formEdit = $("#editSpecCourseForm");
        var modalEdit = $("#editSpecCourseModal");
        var courseImageEdit = formEdit.find(".course-image-input");
        initImageChange(courseImageEdit, formEdit, Toast);

        $("html").on("click", ".editSpecCourse", function () {
            var button = $(this);
            var url = button.data("url");
            var getDataUrl = button.data("send");

            formEdit.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    var specCourse = data.specCourse;

                    formEdit.find("input[name=title]").val(specCourse.title);
                    formEdit
                        .find("input[name=subtitle]")
                        .val(specCourse.subtitle);
                    formEdit.find("input[name=date]").val(specCourse.date);
                    formEdit
                        .find("input[name=time_start]")
                        .val(specCourse.time_start);
                    formEdit
                        .find("input[name=time_end]")
                        .val(specCourse.time_end);
                    formEdit
                        .find(".img-holder")
                        .html(
                            '<img class="img-fluid" src="' +
                                data.url_image +
                                '"></img>'
                        );
                    formEdit
                        .find(".course-image-input")
                        .attr(
                            "data-value",
                            '<img scr="' +
                                data.url_image +
                                '" class="img-fluid"></img>'
                        );
                    formEdit.find(".course-image-input").val("");

                    if (specCourse.active == "S") {
                        formEdit
                            .find(".specCourse-status-checkbox")
                            .prop("checked", true);
                        formEdit
                            .find(".txt-specCourse-description-status")
                            .html("Activo");
                    } else {
                        formEdit
                            .find(".specCourse-status-checkbox")
                            .prop("checked", false);
                        formEdit
                            .find(".txt-specCourse-description-status")
                            .html("Inactivo");
                    }
                },
                complete: function (data) {
                    modalEdit.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });

        var editSpecCourseForm = formEdit.validate({
            rules: registerSpecCourseRules,
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
                                var specCourseContainer = $(
                                    "#specCourse-box-container"
                                );
                                var titleContainer = $(
                                    "#specCourse-description-text-principal"
                                );
                                specCourseContainer.html(data.html);
                                titleContainer.html(data.title);
                            } else {
                                specCourseTable.ajax.reload(null, false);
                            }

                            editSpecCourseForm.resetForm();
                            $(img_holder).empty();

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

                        modalEdit.modal("hide");
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
    }

    // ---------- DELETE ------------

    $("html").on("click", ".deleteSpecCourse", function () {
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
                                    if ($("#spec-courses-table").length) {
                                        specCourseTable.ajax.reload(
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

    // ---------- COURSE MODULES -----------------

    setActiveCheckbox(
        ".module-status-checkbox",
        ".txt-module-description-status"
    );

    $(".editOrderSelect").select2({
        minimumResultsForSearch: -1,
    });

    // ---------- REGISTER -----------

    if ($("#registerModuleForm").length) {
        var registerCourseModuleForm = $("#registerModuleForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
                subtitle: {
                    maxlength: 100,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#registerModuleModal");

                var moduleActive = $("#modules-list-container")
                    .find(".course-section-box.active")
                    .data("id");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = form.serializeArray();
                formData.push({ name: "id", value: moduleActive });

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: $.param(formData),
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            registerCourseModuleForm.resetForm();

                            var boxSpecCourse = $("#specCourse-box-container");
                            var boxModules = $("#modules-list-container");
                            boxSpecCourse.html(data.htmlSecCourse);
                            boxModules.html(data.htmlModules);

                            boxModules.animate(
                                {
                                    scrollTop: boxModules.prop("scrollHeight"),
                                },
                                900
                            );

                            $(".editOrderSelect").select2({
                                minimumResultsForSearch: -1,
                            });

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

    // --------- UPDATE ORDER ----------

    $("html").on(
        "change",
        "#modules-list-container .editOrderSelect",
        function () {
            var url = $(this).data("url");
            var value = $(this).val();

            var sectionActive = $("#modules-list-container")
                .find(".course-section-box.active")
                .data("id");

            $.ajax({
                method: "POST",
                url: url,
                data: {
                    value: value,
                    id: sectionActive,
                },
                dataType: "JSON",
                success: function (data) {
                    if (data.success) {
                        var modulesList = $("#modules-list-container");
                        modulesList.html(data.html);
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
                error: function (data) {
                    ToastError.fire();
                },
            });
        }
    );

    // ---------- UPDATE ------------

    if ($("#editModuleForm").length) {
        var editModuleForm = $("#editModuleForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
                subtitle: {
                    maxlength: 100,
                },
                order: {
                    required: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editModuleModal");

                var moduleActive = $("#modules-list-container")
                    .find(".course-section-box.active")
                    .data("id");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = form.serializeArray();
                formData.push({ name: "id", value: moduleActive });

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: $.param(formData),
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            editModuleForm.resetForm();

                            var boxModules = $("#modules-list-container");
                            boxModules.html(data.htmlModules);

                            if (data.active == data.id) {
                                var topTableInfo = $(
                                    "#top-event-table-title-info"
                                );
                                topTableInfo.html(
                                    '<span class="text-bold"> de: </span> \
                                                    <span class="title-chapter-top-table">' +
                                        data.title +
                                        "</span>"
                                );
                            }

                            $(".editOrderSelect").select2({
                                minimumResultsForSearch: -1,
                            });

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

        $("html").on("click", ".module-edit-btn", function () {
            var button = $(this);
            var getDataUrl = button.data("send");
            var url = button.data("url");
            var modal = $("#editModuleModal");
            var form = modal.find("#editModuleForm");
            var select = form.find(".editOrderSelect");
            select.html("");

            editModuleForm.resetForm();
            form.trigger("reset");

            form.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    var moduleModel = data.module;

                    $.each(moduleModel, function (key, value) {
                        var input = form.find("[name=" + key + "]");
                        if (input) {
                            input.val(value);
                        }
                    });

                    $.each(data.modules, function (key, values) {
                        select.append(
                            '<option value="' +
                                values.order +
                                '">' +
                                values.order +
                                "</option>"
                        );
                    });

                    select.val(moduleModel.order).change();

                    if (moduleModel.active == "S") {
                        form.find(".module-status-checkbox").prop(
                            "checked",
                            true
                        );
                        form.find(".txt-module-description-status").html(
                            "Activo"
                        );
                    } else {
                        form.find(".module-status-checkbox").prop(
                            "checked",
                            false
                        );
                        form.find(".txt-module-description-status").html(
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

    // ----------- DELETE -------------

    $("html").on("click", ".delete-module-btn", function () {
        var button = $(this);
        var url = button.data("url");
        var active = button.closest(".course-section-box").data("active");

        var moduleActive = $("#modules-list-container")
            .find(".course-section-box.active")
            .data("id");

        SwalDelete.fire().then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: {
                            active: active,
                            id: moduleActive,
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                var boxSpecCourse = $(
                                    "#specCourse-box-container"
                                );
                                var boxModules = $("#modules-list-container");
                                boxSpecCourse.html(data.htmlSecCourse);
                                boxModules.html(data.htmlModules);

                                if (data.is_active == 1) {
                                    var eventsBox = $("#events-list-container");
                                    var topTableInfo = $(
                                        "#top-event-table-title-info"
                                    );
                                    eventsBox.html(data.htmlEvents);
                                    topTableInfo.html("");
                                }

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

    // ----------- files --------------

    if ($("#storeModuleFileForm").length) {
        var storeModuleFileForm = $("#storeModuleFileForm").validate({
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
                var modal = $("#moduleFilesModal");
                var files_cont = modal.find("#module-files-list-cont");

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
                            files_cont.html(data.html);

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });

                            storeModuleFileForm.resetForm();
                            form.trigger("reset");
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

        $("html").on("click", ".btn-modules-file", function () {
            var getDataUrl = $(this).data("send");
            var url = $(this).data("url");
            var modal = $("#moduleFilesModal");
            var form = modal.find("#storeModuleFileForm");
            var files_cont = modal.find("#module-files-list-cont");

            form.attr("action", url);

            $.ajax({
                url: getDataUrl,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    files_cont.html(data.html);
                },
                complete: function (data) {
                    modal.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });

        // ------ ELIMINAR ---------

        $("html").on("click", ".deleteModuleFile", function () {
            var url = $(this).data("url");
            var modal = $("#moduleFilesModal");
            var files_cont = modal.find("#module-files-list-cont");

            SwalDelete.fire().then(
                function (e) {
                    if (e.value === true) {
                        $.ajax({
                            url: url,
                            type: "DELETE",
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    files_cont.html(data.html);

                                    Toast.fire({
                                        icon: "success",
                                        text: data.message,
                                    });
                                } else {
                                    Toast.fire({
                                        icon: "error",
                                        message: data.message,
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
    }

    // ------------ EVENTS TABLE --------------

    function initEventsTable(ele, lang, url) {
        var eventsTable = ele.DataTable({
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
            order: [[3, "asc"]],
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
                { data: "responsable.name", name: "responsable.name" },
                { data: "flg_asist", name: "flg_asist" },
                { data: "active", name: "active" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "action-with",
                },
            ],
            dom: "rtip",
        });

        return eventsTable;
    }

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
        record_url: {
            url: true
        }
    };

    var eventsTable;

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

    $("html").on("click", ".course-section-box .title-container", function () {
        var moduleBox = $(this).closest(".course-section-box");

        if (!moduleBox.hasClass("active")) {
            moduleBox.addClass("active").attr("data-active", "active");
            moduleBox.siblings().removeClass("active").attr("data-active", "");

            var url = moduleBox.data("table");

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    type: "html",
                },
                dataType: "JSON",
                success: function (data) {
                    var EventsBox = $("#events-list-container");
                    var topTableInfo = $("#top-event-table-title-info");

                    topTableInfo.html(
                        '<span class="text-bold"> de: </span> \
                                        <span class="title-chapter-top-table">' +
                            data.title +
                            "</span>"
                    );
                    EventsBox.html(data.html);

                    var eventsTableEle = $("#specCourses-events-table");
                    eventsTable = initEventsTable(
                        eventsTableEle,
                        DataTableEs,
                        url
                    );
                },
                error: function (data) {
                    console.log(data);
                    ToastError.fire();
                },
            });
        }
    });

    // --------- GENERAL -------------

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

    $("html").on("keyup", ".input-qty-questions", function () {
        var input = $(this);
        var form = input.closest("form");
        var examSelect = form.find(".examSelect");
        var inputScore = form.find("input[name=min_score]");
        var infoScoreBox = form.find(".info-min-score");
        var value = input.val();
        var url = input.data("url");

        if (value) {
            input.attr("disabled", "disabled");

            $.ajax({
                type: "GET",
                url: url,
                data: {
                    type: "qtyQuestions",
                    value: examSelect.val(),
                    qty: value,
                },
                dataType: "JSON",
                success: function (data) {
                    if (value <= data.qty && value >= 2) {
                        infoScoreBox.html(infoScoreText(data.maxScore));
                        inputScore
                            .removeAttr("disabled")
                            .removeClass("input-disabled");

                        let minScore = Math.round(data.maxScore * 0.7);

                        form.validate();
                        inputScore.rules("add", { max: data.maxScore });
                        inputScore.val(minScore);
                    } else {
                        inputScore.val("");
                        infoScoreBox.empty();
                        inputScore
                            .attr("disabled", "disabled")
                            .addClass("input-disabled");
                    }
                },
                complete: function (data) {
                    input.removeAttr("disabled");
                    input.focus();
                },
                error: function (data) {
                    console.log(data);
                    ToastError.fire();
                },
            });
        } else {
            inputScore.val("");
            infoScoreBox.empty();
            inputScore.attr("disabled", "disabled").addClass("input-disabled");
        }
    });

    // ---------- REGISTER -------------

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

    // ------------- EDITAR ----------------

    var event_modal_edit = $("#editSpecEventModal");
    var event_form_edit = $("#editSpecEventForm");

    if ($("#editSpecEventForm").length) {
        var editEventForm = $("#editSpecEventForm").validate({
            rules: eventFormRules,
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: form.serialize(),
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            eventsTable.ajax.reload(null, false);

                            editEventForm.resetForm();
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
                        form.find(".btn-save").removeAttr("disabled");
                        loadSpinner.toggleClass("active");
                        event_modal_edit.modal("hide");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            },
        });

        $("html").on("click", ".editSpecEvent-btn", function () {
            var getDataUrl = $(this).data("send");
            var url = $(this).data("url");

            event_form_edit.find("select").each(function (key, value) {
                $(this).empty();
            });

            var typeSelect = event_form_edit.find(".typeSelect");
            var instructorSelect = event_form_edit.find(".instructorSelect");
            var responsableSelect = event_form_edit.find(".responsableSelect");
            var roomSelect = event_form_edit.find(".roomSelect");
            var ownerCompanySelect = event_form_edit.find(
                ".ownerCompanySelect"
            );
            var examSelect = event_form_edit.find(".examSelect");

            var activeChk = event_form_edit.find(".event-status-checkbox");
            var flgAssist = event_form_edit.find(".flg-assist-checkbox");
            var flgSurveyCourse = event_form_edit.find(".flg-survey-course");
            var flgSurveyEvaluation = event_form_edit.find(
                ".flg-survey-evaluation"
            );

            event_form_edit.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let all = data.all;
                    let event = data.event;

                    if (all != null) {
                        typeSelect.append("<option></option>");
                        $.each(all.types, function (key, values) {
                            typeSelect.append(
                                '<option value="' +
                                    key +
                                    '">' +
                                    values +
                                    "</option>"
                            );
                        });
                        typeSelect.val(event.type).change();

                        instructorSelect.append("<option></option>");
                        $.each(all.instructors, function (key, values) {
                            instructorSelect.append(
                                '<option value="' +
                                    values.id +
                                    '">' +
                                    values.name +
                                    " " +
                                    values.paternal +
                                    "</option>"
                            );
                        });
                        instructorSelect.val(event.user_id).change();

                        responsableSelect.append("<option></option>");
                        $.each(all.responsables, function (key, values) {
                            responsableSelect.append(
                                '<option value="' +
                                    values.id +
                                    '">' +
                                    values.name +
                                    " " +
                                    values.paternal +
                                    "</option>"
                            );
                        });
                        responsableSelect.val(event.responsable_id).change();

                        roomSelect.append("<option></option>");
                        $.each(all.rooms, function (key, values) {
                            roomSelect.append(
                                '<option value="' +
                                    values.id +
                                    '">' +
                                    values.description +
                                    "</option>"
                            );
                        });
                        roomSelect.val(event.room_id).change();

                        ownerCompanySelect.append("<option></option>");
                        $.each(all.ownerCompanies, function (key, values) {
                            ownerCompanySelect.append(
                                '<option value="' +
                                    values.id +
                                    '">' +
                                    values.name +
                                    "</option>"
                            );
                        });
                        ownerCompanySelect
                            .val(event.owner_companies_id)
                            .change();

                        examSelect.append("<option></option>");
                        $.each(all.exams, function (key, values) {
                            examSelect.append(
                                '<option value="' +
                                    values.id +
                                    '">' +
                                    values.title +
                                    "</option>"
                            );
                        });
                        examSelect.val(event.exam_id).change();
                    }

                    if (event.active == "S") {
                        activeChk.prop("checked", true);
                        $("#txt-edit-status").html("Activo");
                    } else {
                        activeChk.prop("checked", false);
                        $("#txt-edit-status").html("Inactivo");
                    }

                    if (event.flg_asist == "S") {
                        flgAssist.prop("checked", true);
                    } else {
                        flgAssist.prop("checked", false);
                    }

                    if (event.flg_survey_course == "S") {
                        flgSurveyCourse.prop("checked", true);
                    } else {
                        flgSurveyCourse.prop("checked", false);
                    }

                    if (event.flg_survey_evaluation == "S") {
                        flgSurveyEvaluation.prop("checked", true);
                    } else {
                        flgSurveyEvaluation.prop("checked", false);
                    }

                    event_form_edit
                        .find("input[name=description]")
                        .val(event.description);

                    event_form_edit
                        .find("input[name=date]")
                        .data("daterangepicker")
                        .setStartDate(event.date);
                    event_form_edit
                        .find("input[name=date]")
                        .data("daterangepicker")
                        .setEndDate(event.date);

                    let inputQuestionQty = event_form_edit.find(
                        "input[name=questions_qty]"
                    );
                    let inputMinScore = event_form_edit.find(
                        "input[name=min_score]"
                    );

                    inputQuestionQty.val(event.questions_qty);
                    inputMinScore.val(event.min_score);

                    let questQty = event["exam"].questions_count;
                    let maxScore = Math.round(
                        event.questions_qty * event["exam"].questions_avg_points
                    );

                    event_form_edit
                        .find(".info-qty-questions")
                        .html(infoQtyText(questQty));
                    event_form_edit
                        .find(".info-min-score")
                        .html(infoScoreText(maxScore));

                    event_form_edit.validate();
                    inputQuestionQty.rules("add", { max: questQty });
                    inputMinScore.rules("add", { max: maxScore });

                    if (event.finished_certifications_count != 0) {
                        examSelect.attr("readonly", "true");
                        inputQuestionQty
                            .attr("readonly", "true")
                            .addClass("not-user-allowed");
                        inputMinScore
                            .attr("readonly", "true")
                            .addClass("not-user-allowed");
                    } else {
                        examSelect.removeAttr("readonly");
                        inputQuestionQty
                            .removeAttr("readonly")
                            .removeAttr("disabled")
                            .removeClass("not-user-allowed");
                        inputMinScore
                            .removeAttr("readonly")
                            .removeAttr("disabled")
                            .removeClass("not-user-allowed");
                    }
                },
                complete: function (data) {
                    event_modal_edit.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });
    }

    // ------------ ELIMINAR --------------

    $("html").on("click", ".deleteSpecEvent-btn", function () {
        var button = $(this);
        var url = button.data("url");

        var moduleActive = $("#modules-list-container")
            .find(".course-section-box.active")
            .data("id");

        SwalDelete.fire().then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: {
                            id: moduleActive,
                            place: button.data("place"),
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                if (data.show) {
                                    window.location.href = data.route;
                                } else {
                                    eventsTable.ajax.reload(null, false);

                                    var modulesBox = $(
                                        "#modules-list-container"
                                    );
                                    var specCourseBox = $(
                                        "#specCourse-box-container"
                                    );

                                    modulesBox.html(data.htmlModule);
                                    specCourseBox.html(data.htmlCourse);
                                }

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
                        error: function (result) {
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

    // GROUP EVENTS

    if ($("#groupEvents-list-container").length) {
        var groupEventTable = $("#specCourses-GroupEvents-table");

        var getDataUrl = groupEventTable.data("url");

        var groupEvents = groupEventTable.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "title", name: "title" },
                { data: "description", name: "description" },
                { data: "active", name: "active" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
        });
    }
    // ---------- REGISTER -------------

    if ($("#registerGroupEventForm").length) {
        var registerCourseModuleForm = $("#registerGroupEventForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
                description: {
                    maxlength: 100,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#registerGroupEventModal");

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
                            registerCourseModuleForm.resetForm();
                            groupEvents.draw();
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

    // ---------- UPDATE ------------
    // TODO::: HACER OTRO SOLAMENTE PARA LA VIEW DE CURSOS DE ESPC

    if ($("#editGroupEventForm").length) {
        var editGroupEventForm = $("#editGroupEventForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
                description: {
                    maxlength: 100,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editGroupEventModal");

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
                            editGroupEventForm.resetForm();

                            form.trigger("reset");

                            if ($("#container-btn-edit").length) {
                                groupEvents.draw();
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

        $("html").on("click", ".editGroupEvent-btn", function () {
            var button = $(this);
            var getDataUrl = button.data("send");
            var url = button.data("url");
            var modal = $("#editGroupEventModal");
            var form = modal.find("#editGroupEventForm");

            editGroupEventForm.resetForm();

            form.trigger("reset");
            form.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let groupEvent = data.groupEvent;

                    form.find("input[name=title]").val(groupEvent.title);
                    form.find("input[name=description]").val(
                        groupEvent.description
                    );

                    if (groupEvent.active == "S") {
                        form.find(".groupEvent-status-checkbox").prop(
                            "checked",
                            true
                        );
                        form.find(".txt-group-event-status").html("Activo");
                    } else {
                        form.find(".groupEvent-status-checkbox").prop(
                            "checked",
                            false
                        );
                        form.find(".txt-group-event-status").html("Inactivo");
                    }

                    modal.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });
    }

    // ----------- DELETE -------------

    $("html").on("click", ".deleteGroupEvent-btn", function () {
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
                                groupEvents.draw();

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
});
