import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../../common/js/sweet-alerts.js";
import { QUESTION_VIDEO } from "./question.js";

export const UPLOAD_VIDEO = () => {
    if ($("#videoForm").length) {
        const videoFilePondConfig = {
            allowMultiple: false,
            name: "video",
            dropValidation: true,
            storeAsFile: true,
            allowReplace: true,
            labelIdle:
                '<i class="fa-solid fa-video"></i> \
                                Arrastra y suelta un video o \
                                <span class="filepond--label-action"> Explora </span>',
            checkValidity: true,
            acceptedFileTypes: ["video/*"],
            maxFileSize: "40MB",
            maxTotalFileSize: "40MB",
            labelMaxFileSize: "Tamaño máximo del archivo es {filesize}",
            labelFileTypeNotAllowed: "Este tipo de archivo no es válido",
            labelMaxFileSizeExceeded: "El archivo es demasiado grande",
            fileValidateTypeLabelExpectedTypes: "Se espera {lastType}",
            fileValidateTypeLabelExpectedTypesMap: {
                video: ".mp4, .webm, .ogg",
            },
            credits: false,
        };

        FilePond.registerPlugin(FilePondPluginFileValidateSize);
        $(".store-step-video-evaluation-input").filepond(videoFilePondConfig);

        let videoForm = $("#videoForm").validate({
            rules: {
                video: {
                    required: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");
                var videoInput = form.find(
                    ".store-step-video-evaluation-input"
                );

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: new FormData(form[0]),
                    dataType: "JSON",
                    processData: false,
                    contentType: false,
                    success: function (data) {
                        if (data.success) {
                            form.trigger("reset");
                            videoInput.filepond("removeFiles");

                            $("#container-video").html(data.html);

                            QUESTION_VIDEO();

                            // TODO: add

                            if ($("#question-type-container").length) {
                                // if ($("#dropdown-questions-create").length) {

                                    $("#registerQuestionTypeSelect").select2({
                                        placeholder: "Selecciona un tipo de enunciado",
                                    });

                                    $(".main-content").on(
                                        "change",
                                        "#registerQuestionTypeSelect",
                                        function () {
                                            var value = $(this).val();
                                            var url = $(this).data("url");

                                            $(
                                                "#registerQuestionTypeSelect"
                                            ).select2({
                                                placeholder:
                                                    "Selecciona un tipo de enunciado",
                                                disabled: true,
                                            });

                                            $.ajax({
                                                type: "GET",
                                                url: url,
                                                data: {
                                                    value: value,
                                                },
                                                dataType: "JSON",
                                                success: function (data) {
                                                    if (data.success) {
                                                        let questionBox = $(
                                                            "#question-type-container"
                                                        );
                                                        questionBox.html(
                                                            data.html
                                                        );
                                                    } else {
                                                        Toast.fire({
                                                            icon: "error",
                                                            text: data.message,
                                                        });
                                                    }
                                                },
                                                complete: function (data) {
                                                    $(
                                                        "#registerQuestionTypeSelect"
                                                    ).select2({
                                                        placeholder:
                                                            "Selecciona un tipo de enunciado",
                                                        disabled: false,
                                                    });
                                                },
                                                error: function (data) {
                                                    console.log(data);
                                                    ToastError.fire();
                                                },
                                            });
                                        }
                                    );
                                // }

                                function addAlternativeHtml(
                                    question_type,
                                    count
                                ) {
                                    if (question_type == 1) {
                                        return (
                                            '<tr class="alternative-row" data-index="' +
                                            count +
                                            '"> \
                            <td> \
                                <div class="input-group"> \
                                    <div class="input-group-prepend"> \
                                        <div class="input-group-text text-bold alternative-number"> \
                                            ' +
                                            (parseInt(count) + 1) +
                                            ' \
                                        </div> \
                                    </div> \
                                    <input type="text" name="alternative[]" class="form-control alternative no-label-error" \
                                        placeholder="Ingresa la alternativa"> \
                                </div> \
                            </td> \
                            <td class="text-center flex-center"> \
                                <label class="is_correct_button position-relative"> \
                                    <input type="radio" name="is_correct" value="' +
                                            count +
                                            '" class="selectgroup-input"> \
                                    <span class="selectgroup-button selectgroup-button-icon is_correct_btn"> \
                                        <i class="fa-solid fa-check"></i> \
                                    </span> \
                                </label> \
                            </td> \
                            <td class="text-center btn-action-container"> \
                                <span class="delete-btn delete-alternative-btn"> \
                                    <i class="fa-solid fa-trash-can"></i> \
                                </span> \
                            </td> \
                        </tr>'
                                        );
                                    } else if (question_type == 2) {
                                        return (
                                            '<tr class="alternative-row" data-index="' +
                                            count +
                                            '"> \
                            <td> \
                                <div class="input-group"> \
                                    <div class="input-group-prepend"> \
                                        <div class="input-group-text text-bold alternative-number"> \
                                            ' +
                                            (parseInt(count) + 1) +
                                            ' \
                                        </div> \
                                    </div> \
                                    <input type="text" name="alternative[]" class="form-control alternative no-label-error" \
                                        placeholder="Ingresa la alternativa"> \
                                </div> \
                            </td> \
                            <td class="text-center flex-center"> \
                                <div class="custom-checkbox custom-input-height flex-center"> \
                                    <input type="checkbox" name="is_correct_' +
                                            count +
                                            '" data-checkboxes="alternatives-multiple-checkbox" \
                                        class="custom-control-input" id="checkbox-' +
                                            count +
                                            '"> \
                                    <label for="checkbox-' +
                                            count +
                                            '" class="selectgroup-button selectgroup-button-icon custom-checkbox-question margin-0"> \
                                        <i class="fa-solid fa-check"></i> \
                                    <label> \
                                </div> \
                            </td> \
                            <td class="text-center btn-action-container"> \
                                <span class="delete-btn delete-alternative-btn"> \
                                    <i class="fa-solid fa-trash-can"></i> \
                                </span> \
                            </td> \
                        </tr>'
                                        );
                                    } else if (question_type == 4) {
                                        return (
                                            '<tr class="alternative-row" data-index="' +
                                            count +
                                            '"> \
                            <td> \
                                <div class="input-group"> \
                                    <div class="input-group-prepend"> \
                                        <div class="input-group-text text-bold alternative-number"> \
                                        ' +
                                            (parseInt(count) + 1) +
                                            ' \
                                        </div> \
                                    </div> \
                                    <input type="text" name="alternative[]" class="form-control alternative no-label-error" \
                                        placeholder="Ingresa la(s) respuesta(s)"> \
                                </div> \
                            </td> \
                            <td class="text-center btn-action-container"> \
                                <span class="delete-btn delete-alternative-btn"> \
                                    <i class="fa-solid fa-trash-can"></i> \
                                </span> \
                            </td> \
                        </tr>'
                                        );
                                    } else if (question_type == 5) {
                                        return (
                                            '<tr class="alternative-row" data-index="' +
                                            count +
                                            '"> \
                            <td class="input-matching-column input-matching-column-width"> \
                                <div class="input-group"> \
                                    <div class="input-group-prepend"> \
                                        <div class="input-group-text text-bold alternative-number"> \
                                        ' +
                                            (parseInt(count) + 1) +
                                            ' \
                                        </div> \
                                    </div> \
                                    <input type="text" name="alternative[]" class="form-control alternative no-label-error" \
                                        placeholder="Ingresa la alternativa"> \
                                    <span class="position-relative image-icon-alternative"> \
                                        <label for="alternative-image-' +
                                            count +
                                            '" class="margin-0"> \
                                            <i class="fa-solid fa-image fa-xl"></i> \
                                            <span class="inner-icon-context"> \
                                                <i class="fa-solid fa-plus fa-xs"></i> \
                                            </span> \
                                        </label> \
                                        <input type="file" name="image-' +
                                            count +
                                            '" id="alternative-image-' +
                                            count +
                                            '" class="input-alternative-image" data-value=""> \
                                    </span> \
                                </div> \
                                <div class="img-alternative-holder position-relative"> \
                                </div> \
                            </td> \
                            <td class="text-center relation-icon-column"> \
                                <span class="matching-relation-column"> \
                                </span> \
                            </td> \
                            <td class="input-matching-column-width"> \
                                <input type="text" name="droppable-option[]" class="form-control droppable no-label-error" \
                                    placeholder="Ingresa la respuesta"> \
                            </td> \
                            <td class="text-center btn-action-container"> \
                                <span class="delete-btn delete-alternative-btn"> \
                                    <i class="fa-solid fa-trash-can"></i> \
                                </span> \
                            </td> \
                        </tr>'
                                        );
                                    } else {
                                        return "";
                                    }
                                }

                                function deleteAlternative(row, questionType) {
                                    /*--------------- QUESTION: RELLLENAR ESPACIO EN BLANCO  ------------------*/

                                    if (questionType == 4) {
                                        let row_index = row.data("index");
                                        let currentVal = $(
                                            "#statement-fill-blank"
                                        ).val();
                                        let regex = /\[+ ___________ +\]/gm;

                                        let matches = [
                                            ...currentVal.matchAll(regex),
                                        ];

                                        if (
                                            row_index > 0 &&
                                            row_index in matches
                                        ) {
                                            let match = matches[row_index];
                                            let match_start = match.index;
                                            let match_end =
                                                match_start + match[0].length;

                                            $("#statement-fill-blank").val(
                                                currentVal.substring(
                                                    0,
                                                    match_start
                                                ) +
                                                    "" +
                                                    currentVal.substring(
                                                        match_end + 1
                                                    )
                                            );
                                        }
                                    }

                                    row.remove();

                                    $("tr.alternative-row").each(function (
                                        index
                                    ) {
                                        $(this).attr("data-index", index);
                                        $(this)
                                            .find(".alternative-number")
                                            .html(index + 1);

                                        /*--------------- QUESTION: RESPUESTA ÚNICA  ------------------*/

                                        if (questionType == 1) {
                                            $(this)
                                                .find("input[name=is_correct]")
                                                .val(index);
                                        }

                                        /*--------------- QUESTION: RESPUESTA MÚLTIPLE  ------------------*/

                                        if (questionType == 2) {
                                            $(this)
                                                .find("input[type=checkbox]")
                                                .attr(
                                                    "name",
                                                    "is_correct_" + index
                                                )
                                                .attr("id", "checkbox-" + index)
                                                .next("label")
                                                .attr(
                                                    "for",
                                                    "checkbox-" + index
                                                );
                                        }

                                        /*--------------- QUESTION: RELACIONAR  ------------------*/

                                        if (questionType == 5) {
                                            $(this)
                                                .find(
                                                    ".image-icon-alternative label"
                                                )
                                                .attr(
                                                    "for",
                                                    "alternative-image-" + index
                                                );
                                            $(this)
                                                .find(
                                                    "input[type=file].input-alternative-image"
                                                )
                                                .attr("name", "image-" + index)
                                                .attr(
                                                    "id",
                                                    "alternative-image-" + index
                                                );
                                        }
                                    });
                                }

                                function deleteImage(row) {
                                    var column = row.find(
                                        ".input-matching-column"
                                    );
                                    var img_input = row.find(
                                        "input[type=file].input-alternative-image"
                                    );
                                    var img_holder = row.find(
                                        ".img-alternative-holder"
                                    );

                                    column.removeClass("with-image");
                                    column
                                        .find(
                                            ".image-icon-alternative .inner-icon-context"
                                        )
                                        .html(
                                            '<i class="fa-solid fa-plus fa-xs"></i>'
                                        );
                                    img_holder.removeClass("show").empty();
                                    img_input.val("");
                                }

                                $("html").on(
                                    "change",
                                    "#question-status-checkbox",
                                    function () {
                                        var txtDesc = $("#txt-status-question");
                                        if (this.checked) {
                                            txtDesc.html("Activo");
                                        } else {
                                            txtDesc.html("Inactivo");
                                        }
                                    }
                                );

                                // ---------------- AÑADIR ALTERNATIVA --------------

                                $(".main-content").on(
                                    "click",
                                    ".add_alternative_button",
                                    function () {
                                        var questionType = $(
                                            "input#typeQuestionValue"
                                        ).val();

                                        /*--------------- QUESTION: RESPUESTA ÚNICA ------------------*/
                                        /*--------------- QUESTION: RESPUESTA MÚLTIPLE ------------------*/
                                        /*--------------- QUESTION: RELLAR ESPACIO EN BLANCO ------------------*/
                                        /*--------------- QUESTION: RELACIONAR ------------------*/

                                        var type = $(
                                            "input#typeQuestionValue"
                                        ).val();
                                        var alternativesTable = $(
                                            "#alternatives-table"
                                        );

                                        let count =
                                            alternativesTable.find(
                                                ".alternative-row"
                                            ).length;
                                        alternativesTable.append(
                                            addAlternativeHtml(type, count)
                                        );
                                    }
                                );

                                // -------------- ELIMINAR ALTERNATIVA ----------------

                                $(".main-content").on(
                                    "click",
                                    ".delete-alternative-btn",
                                    function () {
                                        var questionType = $(
                                            "input#typeQuestionValue"
                                        ).val();
                                        var row =
                                            $(this).closest(
                                                "tr.alternative-row"
                                            );
                                        var dataStored = $(this).data("stored");

                                        if (dataStored == true) {
                                            var url = $(this).data("url");

                                            SwalDelete.fire().then(
                                                function (e) {
                                                    if (e.value === true) {
                                                        $.ajax({
                                                            method: "POST",
                                                            url: url,
                                                            dataType: "JSON",
                                                            success: function (
                                                                result
                                                            ) {
                                                                if (
                                                                    result.success ===
                                                                    true
                                                                ) {
                                                                    deleteAlternative(
                                                                        row,
                                                                        questionType
                                                                    );

                                                                    Toast.fire({
                                                                        icon: "success",
                                                                        text: "¡Registro eliminado!",
                                                                    });
                                                                } else {
                                                                    Toast.fire({
                                                                        icon: "error",
                                                                        text: result.message,
                                                                    });
                                                                }
                                                            },
                                                            error: function (
                                                                result
                                                            ) {
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
                                        } else {
                                            deleteAlternative(
                                                row,
                                                questionType
                                            );
                                        }
                                    }
                                );

                                // -------------- CAMBIAR PREGUNTA CORRECTA ---------------

                                $(".main-content").on(
                                    "change",
                                    ".alternative-row input[name=is_correct]",
                                    function () {
                                        var questionType = $(
                                            "input#typeQuestionValue"
                                        ).val();

                                        /*--------------- QUESTION: RESPUESTA ÚNICA ------------------*/

                                        if (questionType == 1) {
                                            if ($("#questions-table").length) {
                                                let row =
                                                    $(this).closest(
                                                        "tr.alternative-row"
                                                    );
                                                row.find(".delete-btn")
                                                    .removeClass(
                                                        "delete-alternative-btn"
                                                    )
                                                    .addClass("disabled");
                                                row.siblings()
                                                    .find(".delete-btn")
                                                    .removeClass("disabled")
                                                    .addClass(
                                                        "delete-alternative-btn"
                                                    );
                                            }
                                        }
                                    }
                                );

                                // -------------- AÑADIR ESPACIO EN BLANCO ---------------

                                $(".main-content").on(
                                    "click",
                                    ".add-blank-space-btn",
                                    function () {
                                        var curPos = document.getElementById(
                                            "statement-fill-blank"
                                        ).selectionStart;

                                        var input = $("#statement-fill-blank");
                                        let value = input.val();

                                        if (value != "") {
                                            input.val(
                                                value.slice(0, curPos) +
                                                    " [ " +
                                                    "_".repeat(11) +
                                                    " ] " +
                                                    value.slice(curPos)
                                            );
                                            input.caretTo(curPos + 17);
                                        }

                                        var currentVal = $(
                                            "#statement-fill-blank"
                                        ).val();

                                        var regex = /\[+ ___________ +\]/gm;

                                        var alternativesTable = $(
                                            "#alternatives-table"
                                        );

                                        let matches = [
                                            ...currentVal.matchAll(regex),
                                        ];
                                        let count =
                                            alternativesTable.find(
                                                ".alternative-row"
                                            ).length;
                                        var type = $(
                                            "input#typeQuestionValue"
                                        ).val();

                                        if (matches.length > count) {
                                            alternativesTable.append(
                                                addAlternativeHtml(type, count)
                                            );
                                        }
                                    }
                                );

                                // -------------- CARGAR IMAGEN ----------------

                                $(".main-content").on(
                                    "change",
                                    "input[type=file].input-alternative-image",
                                    function () {
                                        var img_path = $(this).val();
                                        var row =
                                            $(this).closest(".alternative-row");
                                        var column = row.find(
                                            ".input-matching-column"
                                        );
                                        var img_holder = row.find(
                                            ".img-alternative-holder"
                                        );

                                        var img_extension = img_path
                                            .substring(
                                                img_path.lastIndexOf(".") + 1
                                            )
                                            .toLowerCase();

                                        if (
                                            img_extension == "jpeg" ||
                                            img_extension == "jpg" ||
                                            img_extension == "png"
                                        ) {
                                            if (
                                                typeof FileReader != "undefined"
                                            ) {
                                                img_holder.empty();
                                                var reader = new FileReader();

                                                reader.onload = function (e) {
                                                    $("<img/>", {
                                                        src: e.target.result,
                                                        class: "img-fluid alternative_img",
                                                    }).appendTo(img_holder);
                                                };

                                                img_holder.append(
                                                    '<span class="delete-image-alternative"> \
                                        <i class="fa-regular fa-circle-xmark fa-lg"></i> \
                                    </span>'
                                                );

                                                reader.readAsDataURL(
                                                    $(this)[0].files[0]
                                                );

                                                column.addClass("with-image");
                                                column
                                                    .find(
                                                        ".image-icon-alternative .inner-icon-context"
                                                    )
                                                    .html(
                                                        '<i class="fa-solid fa-arrows-rotate"></i>'
                                                    );

                                                img_holder.addClass("show");
                                            } else {
                                                img_holder.html(
                                                    "Este navegador no soporta Lector de Archivos"
                                                );
                                            }
                                        } else {
                                            column.removeClass("with-image");
                                            column
                                                .find(
                                                    ".image-icon-alternative .inner-icon-context"
                                                )
                                                .html(
                                                    '<i class="fa-solid fa-plus fa-xs"></i>'
                                                );
                                            img_holder
                                                .removeClass("show")
                                                .empty();
                                            $(this).val("");

                                            Toast.fire({
                                                icon: "warning",
                                                title: "¡Selecciona una imagen!",
                                            });
                                        }
                                    }
                                );

                                // ------------ ELIMINAR IMAGEN ----------------

                                $(".main-content").on(
                                    "click",
                                    ".delete-image-alternative",
                                    function () {
                                        var row =
                                            $(this).closest(".alternative-row");
                                        var dataStored = $(this).data("stored");
                                        var url = $(this).data("url");

                                        if (dataStored) {
                                            var url = $(this).data("url");

                                            SwalDelete.fire().then(
                                                function (e) {
                                                    if (e.value === true) {
                                                        $.ajax({
                                                            method: "POST",
                                                            url: url,
                                                            dataType: "JSON",
                                                            success: function (
                                                                result
                                                            ) {
                                                                if (
                                                                    result.success ===
                                                                    true
                                                                ) {
                                                                    deleteImage(
                                                                        row
                                                                    );

                                                                    Toast.fire({
                                                                        icon: "success",
                                                                        text: "¡Registro eliminado!",
                                                                    });
                                                                } else {
                                                                    Toast.fire({
                                                                        icon: "error",
                                                                        text: result.message,
                                                                    });
                                                                }
                                                            },
                                                            error: function (
                                                                result
                                                            ) {
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
                                        } else {
                                            deleteImage(row);
                                        }
                                    }
                                );

                                if ($("#question-box-container").length) {
                                    // ------------------ ACTUALIZAR -----------------------

                                    var updateQuestions = $(
                                        "#updateQuestionForm"
                                    ).validate({
                                        rules: {
                                            question_type_id: {
                                                required: true,
                                            },
                                            statement: {
                                                required: true,
                                                maxlength: 1000,
                                            },
                                            points: {
                                                required: true,
                                                number: true,
                                                step: 1,
                                                min: 1,
                                            },
                                            "alternative[]": {
                                                required: true,
                                                maxlength: 500,
                                            },
                                            "droppable-option[]": {
                                                required: true,
                                                maxlength: 500,
                                            },
                                            is_correct: {
                                                required: true,
                                            },
                                        },
                                        submitHandler: function (form, event) {
                                            event.preventDefault();
                                            var form = $(form);
                                            var loadSpinner =
                                                form.find(".loadSpinner");

                                            Swal.fire({
                                                title: "Actualizar registro",
                                                text: "Confirme los cambios antes de continuar",
                                                icon: "question",
                                                showCancelButton: true,
                                                confirmButtonText: "Confirmar",
                                                cancelButtonText: "Cancelar",
                                                reverseButtons: true,
                                            }).then((result) => {
                                                if (result.value) {
                                                    form.find(".btn-save").attr(
                                                        "disabled",
                                                        "disabled"
                                                    );

                                                    loadSpinner.toggleClass(
                                                        "active"
                                                    );

                                                    var formData = new FormData(
                                                        form[0]
                                                    );

                                                    $.ajax({
                                                        method: form.attr(
                                                            "method"
                                                        ),
                                                        url: form.attr(
                                                            "action"
                                                        ),
                                                        data: formData,
                                                        processData: false,
                                                        contentType: false,
                                                        dataType: "JSON",
                                                        success: function (
                                                            data
                                                        ) {
                                                            if (data.success) {
                                                                let question_box =
                                                                    $(
                                                                        "#question-type-container"
                                                                    );
                                                                let question_statement =
                                                                    $(
                                                                        "#question-statement-container"
                                                                    );

                                                                question_box.html(
                                                                    data.html
                                                                );
                                                                question_statement.html(
                                                                    data.statement
                                                                );

                                                                Toast.fire({
                                                                    icon: "success",
                                                                    text: "¡Registro actualizado!",
                                                                });
                                                            } else {
                                                                Toast.fire({
                                                                    icon: "error",
                                                                    text: data.message,
                                                                });
                                                            }
                                                        },
                                                        complete: function (
                                                            data
                                                        ) {
                                                            form.find(
                                                                ".btn-save"
                                                            ).removeAttr(
                                                                "disabled"
                                                            );
                                                            loadSpinner.toggleClass(
                                                                "active"
                                                            );
                                                        },
                                                        error: function (data) {
                                                            console.log(data);
                                                            ToastError.fire();
                                                        },
                                                    });
                                                } else {
                                                    return false;
                                                }
                                            });
                                        },
                                    });
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
};
