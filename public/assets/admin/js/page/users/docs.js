import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";

$(() => {
    /*-------------- DOCUMENTS MODAL -----------------*/

    if ($("#users-table").length) {
        
        $("html").on("click", ".showDocsParticipant", function () {
            var button = $(this);
            var getDataUrl = button.data("send");
            var getUrlUpdate = button.data("url");
            var modal = $("#viewDocsParticipantModal");

            $("#storeFileForm").trigger("reset");

            $.ajax({
                method: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    // dibujar datos en el modal

                    let participantTitle = data.title;
                    let html = data.html;
                    let participantTitleCont = modal.find(
                        ".modal_docs_participant_name"
                    );
                    let modalDocsBody = modal.find("#tableDocs");
                    participantTitleCont.html(participantTitle + ": ");
                    modalDocsBody.html(html);
                    $("#storeFileForm").attr("action", getUrlUpdate);

                    // validar la subida del archivo para cada empresa
                    var storeFileForm = $("#storeFileForm").validate({
                        rules: {
                            "files[]": {
                                required: true,
                            },
                        },
                        messages: {
                            "files[]": {
                                extension: "Por favor, sólo sube archivos PDF",
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
                                                            notebody.append(
                                                                sub_li
                                                            );
                                                        }
                                                    );

                                                    Swal.fire({
                                                        toast: true,
                                                        position: "top-end",
                                                        showConfirmButton: true,
                                                        timerProgressBar: false,
                                                        icon: "warning",
                                                        title: "No se cargaron los archivos: ",
                                                        html: notebody[0]
                                                            .outerHTML,
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
                                    form.find(".btn-save").removeAttr(
                                        "disabled"
                                    );
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
                                                let html = result.html;
                                                let modalDocsBody =
                                                    modal.find("#tableDocs");
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
                                            $(".btn-save").removeAttr(
                                                "disabled"
                                            );
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
    }
});
