import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    setActiveSubmitButton,
} from "../../common/js/utils.js";

$(function () {
    const WEBINAR_RULES = {
        title: {
            required: true,
            maxlength: 255,
        },
        description: {
            maxlength: 255,
        },
        date: {
            required: true,
        },
        image: {
            required: true,
        },
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

    $(".webinar-image-input").filepond(IMAGE_FILEPOND_CONFIG);

    setActiveCheckbox(
        ".webinar-status-checkbox",
        ".txt-description-status-webinar"
    );

    var webinarsTable;

    if ($("#webinars-table").length) {
        var webinarsTableEle = $("#webinars-table");
        var getDataUrl = webinarsTableEle.data("url");
        webinarsTable = webinarsTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "title", name: "title" },
                { data: "description", name: "description" },
                { data: "date", name: "date" },
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

        // ---------- STORE -----------

        var formRegister = $("#registerWebinarForm");
        var registerSubmitButton = formRegister.find("button[type=submit]");
        setActiveSubmitButton(registerSubmitButton);

        var registerWebinarForm = $("#registerWebinarForm").validate({
            rules: WEBINAR_RULES,
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var button = form.find("button[type=submit][clicked=true]");
                var loadSpinner = button.find(".loadSpinner");
                var modal = $("#RegisterWebinarModal");

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
                            let imageInput = form.find(".webinar-image-input");
                            var dateInput = form.find("input[name=date]");

                            if (data.show) {
                                window.location.href = data.route;
                            } else {
                                if ($("#webinars-table").length) {
                                    webinarsTable.draw();
                                }
                            }

                            registerWebinarForm.resetForm();
                            form.trigger("reset");

                            imageInput.filepond("removeFiles");

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

    // --------- EDIT ------------

    if ($("#editWebinarModal").length) {
        var formEdit = $("#editWebinarForm");
        var modalEdit = $("#editWebinarModal");

        var editWebinarForm = $("#editWebinarForm").validate({
            rules: WEBINAR_RULES,
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");

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
                                var webinarContainer = $(
                                    "#webinar-box-container"
                                );
                                var titleContainer = $(
                                    "#webinar-description-text-principal"
                                );
                                webinarContainer.html(data.html);
                                titleContainer.html(data.title);
                            } else {
                                webinarsTable.ajax.reload(null, false);
                            }

                            editWebinarForm.resetForm();
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

        $("html").on("click", ".editWebinar", function () {
            var button = $(this);
            var url = button.data("url");
            var getDataUrl = button.data("send");

            var imageInput = formEdit.find(".webinar-image-input");
            imageInput.filepond("removeFiles");
            formEdit.attr("action", url);

            $.ajax({
                url: getDataUrl,
                type: "GET",
                dataType: "JSON",
                success: function (data) {
                    let webinar = data.webinar;
                    let image = data.image;

                    imageInput.filepond("addFile", decodeURI(image));

                    $.each(webinar, function (key, value) {
                        let input = formEdit.find("[name=" + key + "]");
                        if (input) {
                            input.val(value);
                        }
                    });

                    if (webinar.active == "S") {
                        formEdit
                            .find(".webinar-status-checkbox")
                            .prop("checked", true);
                        formEdit
                            .find(".txt-description-status-webinar")
                            .html("Activo");
                    } else {
                        formEdit
                            .find(".webinar-status-checkbox")
                            .prop("checked", false);
                        formEdit
                            .find(".txt-description-status-webinar")
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

    $("html").on("click", ".deleteWebinar", function () {
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
                                    if ($("#webinars-table").length) {
                                        webinarsTable.ajax.reload(null, false);
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

    // ---------- FILES -----------------

    if ($("#files-webinar-table").length) {
        
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
            labelMaxTotalFileSizeExceeded: "El tamaño total de los archivos es demasiado grande",
            labelMaxTotalFileSize: "El tamaño total es {filesize}",
            labelMaxFileSize: "Tamaño máximo del archivo es {filesize}",
            labelFileTypeNotAllowed: "Este tipo de archivo no es válido",
            labelMaxFileSizeExceeded: "El archivo es demasiado grande",
            labelFileTypeNotAllowed: "Este tipo de archivo no es válido",
            fileValidateTypeLabelExpectedTypes: "Se espera {lastType}",
            credits: false,
        };

        FilePond.registerPlugin(FilePondPluginFileValidateSize);

        $(".store-webinar-files-input").filepond(FILEPOND_CONFIG_FILES);

        var filesDataTableEle = $("#files-webinar-table");
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

        var registerWebinarFilesForm = $("#store-webinar-files-form").validate({
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

                var filesInput = form.find(".store-webinar-files-input");

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: new FormData(form[0]),
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            var webinarContainer = $("#webinar-box-container");
                            webinarContainer.html(data.html);

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
                                    var webinarContainer = $(
                                        "#webinar-box-container"
                                    );
                                    webinarContainer.html(data.html);

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
});
