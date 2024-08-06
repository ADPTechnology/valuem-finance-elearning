import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";

$(() => {
    var getCertificationForm = $("#getCertificationDocuments").validate({
        rules: {
            dni: {
                required: true,
                number: true,
            },
        },
        submitHandler: function (form, event) {
            event.preventDefault();

            var form = $(form);
            var loadSpinner = form.find(".loadSpinner");

            loadSpinner.toggleClass("active");
            form.find(".btn-search").attr("disabled", "disabled");

            let formData = form.serialize();

            $.ajax({
                method: form.attr("method"),
                url: form.attr("action"),
                data: formData,

                dataType: "JSON",
                success: function (data) {

                    if (data.success) {
                        $("#container-info-documents").html(data.html);
                    } else {
                        Toast.fire({
                            icon: "error",
                            text: 'Ha ocurrido un error',
                        });
                    }
                },
                complete: function (data) {
                    loadSpinner.toggleClass("active");
                    form.find(".btn-search").removeAttr("disabled");
                },
                error: function (data) {
                    ToastError.fire();
                },
            });
        },
    });
});
