import { ToastError } from "../../../../common/js/sweet-alerts.js";

$(function () {

    $("html").on("click", ".btn-evaluation-start", function (e) {
        let button = $(this);
        let url = button.data("url");
        let sendUrl = button.data("send");
        let modal = $("#fcInstructions-modal");

        $.ajax({
            type: "GET",
            url: sendUrl,
            dataType: "JSON",
            success: function (response) {
                let html = response.html;
                let fcStep = response.fcStep;
                modal.find("#container-modal-ev").html(html);

                if (fcStep.type == "video") {
                    if (
                        fcStep.video == null ||
                        fcStep.video.questions.length == 0
                    ) {
                        modal
                            .find("#btn-start-evaluation")
                            .attr("disabled", true);
                        modal
                            .find("#btn-start-evaluation")
                            .attr("type", "button");
                    } else {
                        modal
                            .find("#btn-start-evaluation")
                            .attr("disabled", false);
                        modal
                            .find("#btn-start-evaluation")
                            .attr("type", "submit");
                        modal
                            .find(".fcEvaluation-start-form")
                            .attr("action", url);
                    }
                } else {
                    if (fcStep.exams.length == 0 || fcStep.exams[0].questions.length == 0) {
                        modal
                            .find("#btn-start-evaluation")
                            .attr("disabled", true);
                        modal
                            .find("#btn-start-evaluation")
                            .attr("type", "button");
                    } else {
                        modal
                            .find("#btn-start-evaluation")
                            .attr("disabled", false);
                        modal
                            .find("#btn-start-evaluation")
                            .attr("type", "submit");
                        modal
                            .find(".fcEvaluation-start-form")
                            .attr("action", url);
                    }
                }
            },
            complete: function () {
                modal.modal("show");
            },
            error: function (response) {
                ToastError();
            },
        });
    });

    $(".fcEvaluation-start-form").on("submit", function () {
        var button = $(this).find("#btn-start-evaluation");
        button.attr("disabled", "disabled");
    });
});
