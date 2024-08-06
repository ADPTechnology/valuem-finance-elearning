import DataTableEs from "../../../../common/js/datatable_es.js";
import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";

$(() => {
    if ($("#participants-table").length) {
        // ------------- PARTICIPANTS TABLE -------------

        var participantsTableEle = $("#participants-table");
        var getDataUrl = participantsTableEle.data("url");
        var participantsTable = participantsTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "user.dni", name: "user.dni" },
                { data: "user.name", name: "user.name" },
                { data: "user.paternal", name: "user.paternal" },
                { data: "user.maternal", name: "user.maternal" },
                { data: "status", name: "status" },
                { data: "assist_user", name: "assist_user" },
                { data: "user.profile_user", name: "user.profile_user" },
                { data: "score_fin", name: "score_fin" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
            order: [[0, "desc"]],
        });

        // ------------- UPDATE ASSIST ------------

        $("html").on(
            "change",
            "input[type=checkbox].flg_assist_user_checkbox",
            function () {
                var button = $(this);
                var value = button.prop("checked");
                var url = button.data("url");

                button.attr("disabled", "disabled").addClass("disabled");

                $.ajax({
                    method: "POST",
                    url: url,
                    data: {
                        assist_user: value,
                    },
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            participantsTable.ajax.reload(null, false);
                        } else {
                            Toast.fire({
                                icon: "error",
                                text: data.message,
                            });
                        }
                    },
                    complete: function (data) {
                        button
                            .removeAttr("disabled", "disabled")
                            .removeClass("disabled");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            }
        );
    }

    // VIEW MODAL

    $("#participants-table").on("click", ".showScoreFin", function (e) {
        e.preventDefault();

        let button = $(this);
        let getDataUrl = button.data("send");
        let modal = $("#storeCertificationScoreFin");
        let url = button.data("url");

        modal.find("form").attr("action", url);

        $.ajax({
            url: getDataUrl,
            method: "GET",
            dataType: "JSON",
            success: function (response) {
                let score_fin = response.score;

                modal.find("input[name=score_fin]").val(score_fin);

                // STORE SCORE_FIN

                let formRegisterScoreFinCertification = $(
                    "#registerScoreFinCertification"
                ).validate({
                    rules: {
                        score_fin: {
                            required: true,
                            number: true,
                            min: 0,
                            max: 20,
                        },
                    },
                    submitHandler: function (form, event) {
                        event.preventDefault();

                        var form = $(form);
                        let loadSpinner = form.find(".loadSpinner");

                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").attr("disabled", "disabled");

                        let formData = form.serialize();

                        $.ajax({
                            method: form.attr("method"),
                            url: form.attr("action"),
                            data: formData,
                            dataType: "JSON",
                            success: function (data) {
                                if (data.success) {
                                    Toast.fire({
                                        icon: "success",
                                        text: data.message,
                                    });
                                    form.trigger("reset");
                                    formRegisterScoreFinCertification.resetForm();
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
                                modal.modal("hide");
                                participantsTable.draw();
                            },
                            error: function (data) {
                                console.log(data);
                                ToastError.fire();
                            },
                        });
                    },
                });
            },
            complete: function (data) {
                modal.modal("show");
            },
            error: function (data) {
                console.log(data);
                ToastError.fire();
            },
        });
    });
});
