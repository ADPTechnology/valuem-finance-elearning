import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    setActiveCheckboxYesNot,
    viewSelectYesNot,
} from "../../common/js/utils.js";

$(function () {
    setActiveCheckbox(
        ".assignment-status-checkbox",
        ".txt-assignment-description-status"
    );
    setActiveCheckboxYesNot(
        ".evaluable-status-checkbox",
        ".txt-evaluable-status"
    );
    setActiveCheckboxYesNot(".groupal-status-checkbox", ".txt-groupal-status");

    $("#group_select_participants").select2({
        placeholder: "Selecciona un grupo de evento",
    });

    viewSelectYesNot(".select-group-container", ".groupal-status-checkbox");

    var assignmentsTable;

    // Datatable de los asignables

    var assignments = $("#assignments-table");
    var getDataUrl = assignments.data("url");
    assignmentsTable = assignments.DataTable({
        responsive: true,
        language: DataTableEs,
        serverSide: true,
        processing: true,
        ajax: getDataUrl,
        columns: [
            { data: "id", name: "id" },
            { data: "title", name: "title" },
            { data: "description", name: "description" },
            { data: "value", name: "value" },
            { data: "flg_groupal", name: "flg_groupal" },
            { data: "flg_evaluable", name: "flg_evaluable" },

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

    // Registrar los asignables

    if ($("#register-assignment-form").length) {
        var registerAssignmentForm = $("#register-assignment-form").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
                description: {
                    maxlength: 100,
                },
                value: {
                    required: true,
                    number: true,
                    max: 100,
                },
                date_limit: {
                    required: true,
                },
            },

            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#registerAssignmentModal");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                let formdata = new FormData(form[0]);

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: formdata,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            registerAssignmentForm.resetForm();
                            assignmentsTable.draw();

                            form.trigger("reset");

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });

                            $("#group_select_participants")
                                .val(null)
                                .trigger("change");
                            $(".select-group-container").hide();
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

        $(".groupal-status-checkbox").on("change", function () {
            if (this.checked) {
                $("#group_select_participants").rules("add", {
                    required: true,
                });
            } else {
                $("#group_select_participants").rules("remove", "required");
            }
        });
    }

    // edit assignment

    if ($("#edit-assignment-form").length) {
        var editAssignmentForm = $("#edit-assignment-form").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
                description: {
                    maxlength: 100,
                },
                value: {
                    required: true,
                    number: true,
                    max: 100,
                },
                date_limit: {
                    required: true,
                },
            },

            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editAssignmentModal");

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
                            editAssignmentForm.resetForm();

                            form.trigger("reset");
                            assignmentsTable.draw();
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

        $("html").on("click", ".editAssignment-btn", function () {
            var button = $(this);
            var getDataUrl = button.data("send");
            var url = button.data("url");
            var modal = $("#editAssignmentModal");
            var form = modal.find("#edit-assignment-form");

            editAssignmentForm.resetForm();
            form.trigger("reset");
            form.attr("action", url);

            form.find(".groups-element-dom").remove();
            form.find(".groupal-status-checkbox").prop("disabled", true);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    form.find("input[name=title]").val(data.title);
                    form.find("input[name=description]").val(data.description);
                    form.find("input[name=value]").val(data.value);
                    form.find("input[name=date_limit]").val(data.date_limit);

                    if (data.active == "S") {
                        form.find(".assignment-status-checkbox").prop(
                            "checked",
                            true
                        );
                        form.find(".txt-assignment-description-status").html(
                            "Activo"
                        );
                    } else {
                        form.find(".assignment-status-checkbox").prop(
                            "checked",
                            false
                        );
                        form.find(".txt-assignment-description-status").html(
                            "Inactivo"
                        );
                    }

                    if (data.flg_evaluable == 1) {
                        form.find(".evaluable-status-checkbox").prop(
                            "checked",
                            true
                        );
                        form.find(".txt-evaluable-status").html("Si");
                    } else {
                        form.find(".evaluable-status-checkbox").prop(
                            "checked",
                            false
                        );
                        form.find(".txt-evaluable-status").html("No");
                    }

                    if (data.flg_groupal == 1) {
                        form.find(".groupal-status-checkbox").prop(
                            "checked",
                            true
                        );
                        form.find(".txt-groupal-status").html("Si");
                    } else {
                        form.find(".groupal-status-checkbox").prop(
                            "checked",
                            false
                        );
                        form.find(".txt-groupal-status").html("No");
                    }

                    if (data.participant_groups.length) {
                        let colors = ["warning", "info", "light", "dark"];

                        let div = document.createElement("div");
                        div.classList.add(
                            "form-group",
                            "d-flex",
                            "flex-wrap",
                            "groups-element-dom"
                        );

                        let i = 0;

                        data.participant_groups.forEach((group) => {
                            let span = document.createElement("span");
                            span.classList.add(
                                "badge",
                                "badge-fill",
                                `badge-${colors[i]}`,
                                "mr-1",
                                "mb-1"
                            );
                            span.innerHTML = group.title;

                            div.appendChild(span);

                            i = i >= 3 ? 0 : i + 1;
                        });

                        form.find(".after").after(div);
                    }

                    modal.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });
    }

    // eliminar asignable

    $(".main-content").on("click", ".deleteAssignment-btn", function () {
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
                                assignmentsTable.draw();
                                // certificationsTable.ajax.reload(null, false);
                                // $("#event-box-container").html(result.html);

                                Toast.fire({
                                    icon: "success",
                                    text: result.message,
                                });
                            } else {
                                Toast.fire({
                                    icon: "error",
                                    text: result.message,
                                });
                            }
                        },
                        error: function (result) {
                            ToastError.fire();
                            console.log(result);
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

    // cargar los files del assignment

    $("html").on("click", ".showDocsAssignment-btn", function () {
        var button = $(this);

        var getDataUrl = button.data("send");
        var getUrlUpdate = button.data("url");
        var modal = $("#viewDocsCompanyModal");

        $.ajax({
            method: "GET",
            url: getDataUrl,
            dataType: "JSON",
            success: function (data) {
                let assignmentTitle = data.title;
                let html = data.html;

                let assignmentTitleCont = modal.find(
                    ".modal_docs_company_name"
                );
                let modalDocsBody = modal.find("#tableDocs");

                assignmentTitleCont.html(assignmentTitle + ": ");
                modalDocsBody.html(html);

                $("#storeFileForm").attr("action", getUrlUpdate);

                var storeFileForm = $("#storeFileForm").validate({
                    rules: {
                        "files[]": {
                            required: true,
                        },
                    },

                    submitHandler: function (form, event) {
                        event.preventDefault();
                        var form = $(form);
                        var loadSpinner = form.find(".loadSpinner");
                        var modal = $("#storeFileModal");

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
                                    Toast.fire({
                                        icon: "success",
                                        text: data.message,
                                    }).then((result) => {
                                        if (
                                            result.dismiss ===
                                            Swal.DismissReason.timer
                                        ) {
                                            if (data.foundErrors) {
                                                var notebody = $("<ul/>");
                                                $.each(
                                                    data.rejected,
                                                    function (key, values) {
                                                        var sub_li =
                                                            $("<li/>").html(
                                                                values
                                                            );
                                                        notebody.append(sub_li);
                                                    }
                                                );

                                                Swal.fire({
                                                    toast: true,
                                                    position: "top-end",
                                                    showConfirmButton: true,
                                                    timerProgressBar: false,
                                                    icon: "warning",
                                                    title: "No se cargaron los archivos: ",
                                                    html: notebody[0].outerHTML,
                                                });
                                            }
                                        }
                                    });

                                    form.trigger("reset");
                                    storeFileForm.resetForm();
                                    modalDocsBody.html(data.html);

                                    modal.modal("hide");
                                } else if (data.foundErrors) {
                                    var notebody = $("<ul/>");
                                    $.each(
                                        data.rejected,
                                        function (key, values) {
                                            var sub_li =
                                                $("<li/>").html(values);
                                            notebody.append(sub_li);
                                        }
                                    );

                                    Swal.fire({
                                        toast: true,
                                        position: "top-end",
                                        showConfirmButton: true,
                                        timerProgressBar: false,
                                        icon: "error",
                                        title: "No se cargaron los archivos: ",
                                        html: notebody[0].outerHTML,
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
                            },
                            error: function (data) {
                                ToastError.fire();
                            },
                        });
                    },
                });

                // delete

                $("#tableDocs").on("click", ".deleteFile", function () {
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
                                            let assignmentTitle = result.title;
                                            let html = result.html;

                                            let assignmentTitleCont =
                                                modal.find(
                                                    ".modal_docs_company_name"
                                                );

                                            let modalDocsBody =
                                                modal.find("#tableDocs");

                                            assignmentTitleCont.html(
                                                assignmentTitle + ": "
                                            );
                                            modalDocsBody.html(html);

                                            Toast.fire({
                                                icon: "success",
                                                text: result.message,
                                            });
                                        } else {
                                            Toast.fire({
                                                icon: "error",
                                                text: result.message,
                                            });
                                        }
                                    },
                                    complete: function () {
                                        $(".btn-save").removeAttr("disabled");
                                        // loadSpinner.toggleClass("active");
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
            },
            complete: function (data) {
                modal.modal("show");
            },
            error: function (data) {
                ToastError.fire();
            },
        });
    });
});
