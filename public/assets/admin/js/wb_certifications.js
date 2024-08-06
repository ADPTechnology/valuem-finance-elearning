import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    setActiveSubmitButton,
    InitAjaxSelect2
} from "../../common/js/utils.js";

$(function () {

    if ($('#wb_event_inner_certifications_table').length) {

        /* ----- INNER CERTIFICATIONS TABLE ------*/

        var innerCertificationsTableEle = $("#wb_event_inner_certifications_table");
        var getDataUrl = innerCertificationsTableEle.data("url");
        var innerCertificationsTable = innerCertificationsTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: {
                url: getDataUrl,
                data: function (data) {
                    data.type = "inner";
                },
            },
            columns: [
                { data: "id", name: "id" },
                { data: "user.dni", name: "user.dni" },
                { data: "user.name", name: "user.name" },
                {
                    data: "user.company.description",
                    name: "user.company.description",
                },
                { data: "enabled", name: "enabled", orderable: false },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                },
            ],
            order: [[0, "desc"]],
            // dom: 'rtip'
        });


        // ---------------- REGISTRAR PARTICIPANTES ---------------


        const BUTTON_DISABLED =
            '<button class="btn btn-primary btn-save not-user-allowed" disabled> \
                                     Registrar participantes \
                                     <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i> \
                                 </button>';

        const BUTTON_ENABLED =
            '<button type="submit" class="btn btn-primary btn-save"> \
                                 Registrar participantes \
                                 <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i> \
                             </button>';


        var innerParticipantsTable;

        $("html").on("change", ".search_from_company_select", function () {
            innerParticipantsTable.draw();
        });

        $('html').on('click', '#btn-register-participant-modal', function () {
            var modal = $('#wb_registerParticipantsModal')

            if (!$('#wb-internal-users-participants-table_wrapper').length) {

                var wbIntParticipantsTable = $('#wb-internal-users-participants-table')
                var getDataUrl = wbIntParticipantsTable.data('url')

                innerParticipantsTable = wbIntParticipantsTable.DataTable({
                    responsive: true,
                    language: DataTableEs,
                    serverSide: true,
                    processing: true,
                    ajax: {
                        url: getDataUrl,
                        data: function (data) {
                            data.search_company = modal.find(".search_from_company_select").val();
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
                        { data: "dni", name: "dni" },
                        { data: "name", name: "name" },
                        { data: "position", name: "position" },
                        {
                            data: "company.description",
                            name: "company.description",
                        },
                    ],
                    order: [[1, "asc"]]
                })

            } else {
                innerParticipantsTable.draw()
            }

            modal.modal('show')
        })

        $('#wb-internal-users-participants-table').on('draw.dt', function () {
            $("#btn-store-participant-container").html(BUTTON_DISABLED);
        })

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
                buttonContainer.html(BUTTON_ENABLED);
            } else {
                buttonContainer.html(BUTTON_DISABLED);
            }
        });

        $("#wb-register-participants-form").on("submit", function (e) {
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

                                console.log(data)
                                if (data.success) {
                                    if (data.status == "exceeded") {
                                        Toast.fire({
                                            icon: "warning",
                                            text: data.note,
                                        });
                                    } else {
                                        var dataStatus = data.status;

                                        innerParticipantsTable.draw();
                                        innerCertificationsTable.draw();
                                        $(
                                            "#btn-store-participant-container"
                                        ).html(BUTTON_DISABLED);

                                        $("#webinar-event-box-container").html(
                                            data.html
                                        );

                                        Toast.fire({
                                            icon: "success",
                                            text: data.message,
                                        }).then((result) => {
                                            if (
                                                result.dismiss ===
                                                Swal.DismissReason.timer
                                            ) {
                                                if (
                                                    dataStatus == "limitreached"
                                                ) {
                                                    Toast.fire({
                                                        icon: "warning",
                                                        text: data.note,
                                                    });
                                                }
                                            }
                                        });
                                    }
                                } else {
                                    Toast.fire({
                                        icon: "error",
                                        text: data.message,
                                    });
                                }
                            },
                            error: function (data) {
                                console.log(data);
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


        // ----------------- ELIMINAR PARTICIPANTES ----------------

        $("html").on("click", ".deleteWbCertification", function () {
            var url = $(this).data("url");

            SwalDelete.fire().then(
                function (e) {
                    if (e.value === true) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (result) {
                                if (result.success === true) {
                                    innerCertificationsTable.ajax.reload(
                                        null,
                                        false
                                    );
                                    $("#webinar-event-box-container").html(result.html);

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


        // ------------ UPDATE UNLOCK CERT ---------------

        $('html').on('change', 'input[type=checkbox].unlock_cert_user_checkbox', function () {

            var button = $(this)
            var value = button.prop('checked');
            var url = button.data('url')

            button.attr("disabled", "disabled").addClass("disabled");

            $.ajax({
                method: 'POST',
                url: url,
                data: {
                    unlock_cert: value,
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.success) {
                        innerCertificationsTable.ajax.reload(null, false);
                    } else {
                        Toast.fire({
                            icon: "error",
                            text: data.message,
                        });
                    }
                },
                complete: function (data) {
                    button.removeAttr("disabled", "disabled").removeClass("disabled");
                },
                error: function (data) {
                    console.log(data);
                    ToastError.fire();
                },
            })
        })
    }





    if ($('#wb_event_ext_certifications_table').length) {

        /* ----- EXT CERTIFICATIONS TABLE ------*/

        var extCertificationsTableEle = $("#wb_event_ext_certifications_table");
        var getDataUrl = extCertificationsTableEle.data("url");
        var extCertificationsTable = extCertificationsTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: {
                url: getDataUrl,
                data: function (data) {
                    data.type = "external";
                },
            },
            columns: [
                { data: "id", name: "id" },
                { data: "user.dni", name: "user.dni" },
                { data: "user.name", name: "user.name" },
                { data: "enabled", name: "enabled", orderable: false },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                },
            ],
            order: [[0, "desc"]],
            // dom: 'rtip'
        });


          // ----------------- ELIMINAR PARTICIPANTES ----------------

          $("html").on("click", ".deleteWbCertification-ext", function () {
            var url = $(this).data("url");

            SwalDelete.fire().then(
                function (e) {
                    if (e.value === true) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (result) {
                                if (result.success === true) {
                                    extCertificationsTable.ajax.reload(
                                        null,
                                        false
                                    );
                                    $("#webinar-event-box-container").html(result.html);

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


        // ------------ UPDATE UNLOCK CERT ---------------

        $('html').on('change', 'input[type=checkbox].unlock_cert_user_checkbox-ext', function () {

            var button = $(this)
            var value = button.prop('checked');
            var url = button.data('url')

            button.attr("disabled", "disabled").addClass("disabled");

            $.ajax({
                method: 'POST',
                url: url,
                data: {
                    unlock_cert: value,
                },
                dataType: 'JSON',
                success: function (data) {
                    if (data.success) {
                        extCertificationsTable.ajax.reload(null, false);
                    } else {
                        Toast.fire({
                            icon: "error",
                            text: data.message,
                        });
                    }
                },
                complete: function (data) {
                    button.removeAttr("disabled", "disabled").removeClass("disabled");
                },
                error: function (data) {
                    console.log(data);
                    ToastError.fire();
                },
            })
        })


    }








})
