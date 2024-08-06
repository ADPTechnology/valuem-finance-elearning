import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../../common/js/sweet-alerts.js";
import DataTableEs from "../../../../../common/js/datatable_es.js";
import { UPLOAD_VIDEO } from "./upload.js";

export const QUESTION_VIDEO = () => {
    if ($("#registerQuestionForm").length) {
        // Questions dataTable
        let questionsVideo = $("#questions-steps-table");
        let getDataUrl = questionsVideo.data("url");
        questionsVideo.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                {
                    data: "question_type.description",
                    name: "questionType.description",
                },
                { data: "statement", name: "statement" },
                { data: "points", name: "points" },
                { data: "created_at", name: "created_at" },
                { data: "updated_at", name: "updated_at" },
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

        // Register question
        let registerQuestionForm = $("#registerQuestionForm").validate({
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
                var loadSpinner = form.find(".loadSpinner");

                Swal.fire({
                    title: "Registrar enunciado",
                    text: "Confirme antes de continuar",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonText: "Confirmar",
                    cancelButtonText: "Cancelar",
                    reverseButtons: true,
                }).then((result) => {
                    if (result.value) {
                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").attr("disabled", "disabled");

                        let valueSelect = $("#registerQuestionTypeSelect").val();

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
                                    Toast.fire({
                                        icon: "success",
                                        text: data.message,
                                    });

                                    let data_show = data.data_show;

                                    let question_box = $(
                                        "#question-type-container"
                                    );

                                    question_box.html(data_show.htmlQuestion);

                                    $("#questions-steps-table")
                                        .DataTable()
                                        .ajax.reload();

                                    $("#video-reload").html(data_show.html);

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
                                registerQuestionForm.resetForm();
                                $("#registerQuestionTypeSelect").val(valueSelect);

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

    // Edit question

    if ($("#editQuestionForm").length) {
        // Edit question
        let editQuestionForm = $("#editQuestionForm").validate({
            rules: {
                statement: {
                    required: true,
                },
                points: {
                    required: true,
                    number: true,
                    min: 1,
                },
                correct_answer: {
                    required: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editQuestionModal");

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
                            editQuestionForm.resetForm();

                            form.trigger("reset");

                            $("#questions-steps-table")
                                .DataTable()
                                .ajax.reload();

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

        $("html").on("click", ".editQuestion", function () {
            var button = $(this);
            var getDataUrl = button.data("send");
            var url = button.data("url");
            var modal = $("#editQuestionModal");
            var form = modal.find("#editQuestionForm");
            editQuestionForm.resetForm();

            form.trigger("reset");
            form.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let question = data;

                    form.find("input[name=statement]").val(question.statement);
                    form.find("input[name=correct_answer]").val(
                        question.correct_answer
                    );
                    form.find("input[name=points]").val(question.points);

                    modal.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });
    }

    $(".main-content").on("click", ".deleteQuestion-btn", function () {
        var url = $(this).data("url");

        SwalDelete.fire().then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        method: "DELETE",
                        url: url,
                        dataType: "JSON",
                        success: function (result) {
                            if (result.success === true) {
                                $("#questions-steps-table")
                                    .DataTable()
                                    .ajax.reload();

                                $("#video-reload").html(result.html);

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
                        error: function (result) {
                            console.log(result);
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

    // delete video
    if ($(".active-video").length) {
        $("html").on("click", "#btn-delete-video", function () {
            var button = $(this);
            var url = button.data("url");
            SwalDelete.fire().then(
                function (e) {
                    if (e.value === true) {
                        $(".active-video .loadSpinner").toggleClass("active");
                        $("#btn-delete-video").attr("disabled", "disabled");

                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    $("#container-video").html(data.html);

                                    $("#questions-steps-table")
                                        .DataTable()
                                        .destroy();

                                    UPLOAD_VIDEO();

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
                                $(".active-video .loadSpinner").toggleClass(
                                    "active"
                                );
                                $("#btn-delete-video").removeAttr("disabled");
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
    }
};
