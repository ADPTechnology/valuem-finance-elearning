import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";

import {
    setActiveCheckbox,
    setActiveCheckBoxForResult,
} from "../../../../common/js/utils.js";

$(() => {
    setActiveCheckbox(
        "#edit-evaluation-status-checkbox",
        "#txt-edit-description-evaluation"
    );

    if ($("#fcEvaluationForm").length) {
        let editEvaluationForm = $("#fcEvaluationForm").validate({
            rules: {
                title: {
                    required: true,
                },
                description: {
                    maxlength: 255,
                },
                value: {
                    required: true,
                    number: true,
                    min: 1,
                    max: 100,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                // var modal = $("#editConfigModal");

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
                            editEvaluationForm.resetForm();

                            form.trigger("reset");

                            $("#fcEvaluationForm").html(data.html);

                            setActiveCheckbox(
                                "#edit-evaluation-status-checkbox",
                                "#txt-edit-description-evaluation"
                            );

                            $(".fcEvaluationTitle").text(data.title);
                            $(".fcEvaluationDescription").text(data.description);

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
                        // modal.modal("hide");
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
});
