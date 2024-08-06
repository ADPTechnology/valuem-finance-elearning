$(function () {

    $.fn.filepond.registerPlugin(FilePondPluginImagePreview);
    $.fn.filepond.registerPlugin(FilePondPluginFileValidateType);

    Dropzone.prototype.defaultOptions.dictDefaultMessage =
        "<i class='fa-solid fa-upload'></i> &nbsp; Selecciona o arrastra y suelta un video";
    Dropzone.prototype.defaultOptions.dictFallbackMessage =
        "Your browser does not support drag'n'drop file uploads.";
    Dropzone.prototype.defaultOptions.dictFallbackText =
        "Please use the fallback form below to upload your files like in the olden days.";
    Dropzone.prototype.defaultOptions.dictFileTooBig =
        "El archivo es demasiado grande ({{filesize}}MiB). Tamaño máximo: {{maxFilesize}}MiB.";
    Dropzone.prototype.defaultOptions.dictInvalidFileType =
        "No puedes subir archivos de este tipo.";
    Dropzone.prototype.defaultOptions.dictResponseError =
        "Server responded with {{statusCode}} code.";
    Dropzone.prototype.defaultOptions.dictCancelUpload = "Cancelar carga";
    Dropzone.prototype.defaultOptions.dictCancelUploadConfirmation =
        "Are you sure you want to cancel this upload?";
    Dropzone.prototype.defaultOptions.dictRemoveFile = "Quitar archivo ";
    Dropzone.prototype.defaultOptions.dictMaxFilesExceeded =
        "No puedes subir más archivos.";

    jQuery.extend(jQuery.validator.messages, {
        required:
            '<i class="fa-solid fa-circle-exclamation"></i> &nbsp; Este campo es obligatorio',
        email: "Ingrese un email válido",
        number: "Por favor, ingresa un número válido",
        url: "Por favor, ingresa una URL válida",
        max: jQuery.validator.format(
            "Por favor, ingrese un valor menor o igual a {0}"
        ),
        min: jQuery.validator.format(
            "Por favor, ingrese un valor mayor o igual a {0}"
        ),
        step: jQuery.validator.format("Ingrese un número múltiplo de {0}"),
        maxlength: jQuery.validator.format("Ingrese menos de {0} caracteres."),
        accept: "Por favor, selecciona un archivo con extensión válida.",
    });

    jQuery.validator.addMethod("extension", function (value, element, param) {
        param =
            typeof param === "string"
                ? param.replace(/,/g, "|")
                : "png|jpe?g|gif";
        return (
            this.optional(element) ||
            value.match(new RegExp(".(" + param + ")$", "i"))
        );
    });
})
