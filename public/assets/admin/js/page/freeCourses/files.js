import DataTableEs from "../../../../common/js/datatable_es.js";
import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";

$(() => {

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

    FilePond.registerPlugin(FilePondPluginFileValidateSize);

    $(".store-free-courses-files-input").filepond(FILEPOND_CONFIG_FILES);

    $(".store-webinar-files-input").filepond(FILEPOND_CONFIG_FILES);

    let filesDataTableEle = $("#files-free-courses-table");
    let getDataUrl = filesDataTableEle.data("url");
    let filesTable = filesDataTableEle.DataTable({
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

    // STORE

    var registerFreeCoursesFilesForm = $(
        "#store-free-courses-files-form"
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

            var filesInput = form.find(".store-free-courses-files-input");

            $.ajax({
                method: form.attr("method"),
                url: form.attr("action"),
                data: new FormData(form[0]),
                processData: false,
                contentType: false,
                dataType: "JSON",
                success: function (data) {
                    if (data.success) {
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

    // DELETE

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

                                filesTable.draw();

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
