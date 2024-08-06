import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    initImageChange,
    setActiveSubmitButton,
} from "../../common/js/utils.js";

$(function () {
    var groupParticipantsTable;

    setActiveCheckbox(
        ".groupParticipant-status-checkbox",
        ".txt-group-participant-status"
    );

    // Table Groups Participants

    var groupParticipants = $("#group-participants-table");
    var getDataUrl = groupParticipants.data("url");
    groupParticipantsTable = groupParticipants.DataTable({
        responsive: true,
        language: DataTableEs,
        serverSide: true,
        processing: true,
        ajax: getDataUrl,
        columns: [
            { data: "id", name: "id" },
            { data: "title", name: "title" },
            { data: "description", name: "description" },
            { data: "created_at", name: "created_at" },
            { data: "updated_at", name: "updated_at" },
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

    // Create Group Participant

    if ($("#registerGroupParticipantForm").length) {
        var registerCourseModuleForm = $(
            "#registerGroupParticipantForm"
        ).validate({
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
                var modal = $("#registerGroupParticipantModal");

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
                            groupParticipantsTable.draw();
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

    // Edit Group Participant

    if ($("#editGroupParticipantForm").length) {
        var editGroupParticipantForm = $("#editGroupParticipantForm").validate({
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
                var modal = $("#editGroupParticipantModal");

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
                            editGroupParticipantForm.resetForm();

                            form.trigger("reset");
                            groupParticipantsTable.draw();
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

        $("html").on("click", ".editGroupParticipant-btn", function () {
            var button = $(this);
            var getDataUrl = button.data("send");
            var url = button.data("url");
            var modal = $("#editGroupParticipantModal");
            var form = modal.find("#editGroupParticipantForm");

            editGroupParticipantForm.resetForm();

            form.trigger("reset");
            form.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let groupParticipant = data.groupParticipant;

                    form.find("input[name=title]").val(groupParticipant.title);
                    form.find("input[name=description]").val(
                        groupParticipant.description
                    );

                    if (groupParticipant.active == "S") {
                        form.find(".groupParticipant-status-checkbox").prop(
                            "checked",
                            true
                        );
                        form.find(".txt-group-participant-status").html(
                            "Activo"
                        );
                    } else {
                        form.find(".groupParticipant-status-checkbox").prop(
                            "checked",
                            false
                        );
                        form.find(".txt-group-participant-status").html(
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

    // Delete Group Participant

    $("html").on("click", ".deleteGroupParticipant-btn", function () {
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
                                groupParticipantsTable.draw();

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

    // add participants on group participants

    var participantsOnGroup = $("#participants-on-group-table");
    var getDataUrl = participantsOnGroup.data("url");
    var participantsOnGroupTable = participantsOnGroup.DataTable({
        responsive: true,
        language: DataTableEs,
        serverSide: true,
        processing: true,
        ajax: getDataUrl,
        columns: [
            { data: "id", name: "id" },
            { data: "name", name: "name" },
            { data: "dni", name: "dni" },
            {
                data: "action",
                name: "action",
                orderable: false,
                searchable: false,
            },
        ],
        order: [[0, "desc"]],
    });

    // table participants

    var participantsTable;

    $("html").on("change", "#search_from_company_select", function () {
        participantsTable.draw();
    });

    $(".main-content").on(
        "click",
        "#btn-register-participant-modal",
        function () {
            var modal = $("#registerParticipantsModal");

            if (!$("#users-participants-table_wrapper").length) {
                var usersParticipantsTableEle = $("#users-participants-table");
                var getDataUrl = usersParticipantsTableEle.data("url");

                participantsTable = usersParticipantsTableEle.DataTable({
                    responsive: true,
                    language: DataTableEs,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: getDataUrl,
                        data: function (data) {
                            data.search_company = $(
                                "#search_from_company_select"
                            ).val();
                        },
                    },
                    columns: [
                        {
                            data: "choose",
                            name: "choose",
                            orderable: false,
                            searchable: false,
                            className: "text-center",
                        },
                        { data: "id", name: "id" },
                        { data: "name", name: "name" },
                        { data: "dni", name: "dni" },
                        {
                            data: "company",
                            name: "company",
                            orderable: false,
                            searchable: false,
                        },
                    ],
                    order: [[1, "asc"]],
                });
            } else {
                participantsTable.draw();
            }

            modal.modal("show");
        }
    );

    // Register Participants on Group

    const buttonDisabled =
        '<button class="btn btn-primary btn-save not-user-allowed" disabled> \
                                    Registrar participantes \
                                    <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i> \
                                </button>';

    const buttonEnabled =
        '<button type="submit" class="btn btn-primary btn-save"> \
                                    Registrar participantes \
                                    <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i> \
                                </button>';

    var participantsTable;

    $("html").on("change", "#search_from_company_select", function () {
        participantsTable.draw();
    });

    $("#users-participants-table").on("draw.dt", function () {
        $("#btn-store-participant-container").html(buttonDisabled);
    });

    $("html").on("click", ".checkbox-user-label", function () {
        let input = $("#" + $(this).attr("for"));
        let buttonContainer = $("#btn-store-participant-container");
        let count = 0;

        if (input.is(":checked")) {
            count--;
        } else {
            count++;
        }
        count += $(".checkbox-user-input:checked").length;

        if (count > 0) {
            buttonContainer.html(buttonEnabled);
        } else {
            buttonContainer.html(buttonDisabled);
        }
    });

    $("#register-participants-form").on("submit", function (e) {
        e.preventDefault();

        var form = $(this);

        Swal.fire({
            title: "Registrar participantes",
            text: "Confirme antes de continuar",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Confirmar",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
        }).then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        method: form.attr("method"),
                        url: form.attr("action"),
                        data: form.serialize(),
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                participantsTable.draw();
                                participantsOnGroupTable.draw();
                                $("#btn-store-participant-container").html(
                                    buttonDisabled
                                );
                                // $("#event-box-container").html(data.html);

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

    // delete participant on group

    $(".main-content").on("click", ".deleteParticipant-btn", function () {
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

                                participantsOnGroupTable.draw();
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
});
