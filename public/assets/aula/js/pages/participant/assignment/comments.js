import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../../common/js/sweet-alerts.js";

import { getFirstCharUpper } from "../../../../../common/js/utils.js";

$(function () {


    // var registerCommentsForm = $("#addCommentsForm").validate({
    //     rules: {
    //         content: {
    //             required: true,
    //         },
    //     },
    //     submitHandler: function (form, event) {
    //         event.preventDefault();

    //         var form = $(form);
    //         var loadSpinner = form.find(".loadSpinner");
    //         var modal = $("#addCommentsModal");

    //         loadSpinner.toggleClass("active");
    //         form.find(".btn-save").attr("disabled", "disabled");

    //         let formdata = new FormData(form[0]);

    //         $.ajax({
    //             method: form.attr("method"),
    //             url: form.attr("action"),
    //             data: formdata,
    //             processData: false,
    //             contentType: false,
    //             dataType: "JSON",
    //             success: function (data) {
    //                 if (data.success) {
    //                     registerCommentsForm.resetForm();
    //                     form.trigger("reset");

    //                     Toast.fire({
    //                         icon: "success",
    //                         text: data.message,
    //                     });
    //                 } else {
    //                     Toast.fire({
    //                         icon: "error",
    //                         text: data.message,
    //                     });
    //                 }
    //             },
    //             complete: function (data) {
    //                 modal.modal("hide");
    //                 loadSpinner.toggleClass("active");
    //                 form.find(".btn-save").removeAttr("disabled");
    //             },
    //             error: function (data) {
    //                 console.log(data);
    //                 ToastError.fire();
    //             },
    //         });
    //     },
    // });

    // $(".main-content").on("click", "#addComments-btn", function () {
    //     console.log("click");

    //     var button = $(this);
    //     var getDataUrl = button.data("send");
    //     var url = button.data("url");
    //     var modal = $("#addCommentsModal");
    //     var form = modal.find("#addCommentsForm");

    //     registerCommentsForm.resetForm();

    //     form.trigger("reset");
    //     form.attr("action", url);

    //     $.ajax({
    //         type: "GET",
    //         url: getDataUrl,
    //         dataType: "JSON",
    //         success: function (data) {
    //             let ass = data.data;

    //             $("#title-ass").val(ass.title);

    //             modal.modal("show");
    //         },
    //         error: function (data) {
    //             console.log(data);
    //         },
    //     });
    // });




});
