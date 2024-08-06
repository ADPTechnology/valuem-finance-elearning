Chart.defaults.global.defaultFontSize = 10;
Chart.defaults.global.defaultFontColor = "black";

import { chartNotes } from "./kpis.js";

(function () {
    let chart_student_status = document.getElementById("chart-student-status");
    let student_status = document.getElementById("student-status");
    let state;

    $("#search_from_course_select").change(function () {
        let getUrl = $(this).data("url");

        $.ajax({
            url: getUrl,
            type: "GET",
            data: {
                course_id: $(this).val(),
            },
            dataType: `JSON`,
            success: function (response) {
                let data = JSON.parse(response);
                let { approved, suspended } = data;

                if (approved === 0 && suspended === 0) {
                    chart_student_status.classList.remove(
                        "chart-student-status-active"
                    );
                    chart_student_status.classList.add("chart-student-status");

                    student_status.classList.add("student-status-active");
                    student_status.classList.remove("student-status");

                    state = false;
                } else {
                    chart_student_status.classList.remove(
                        "chart-student-status"
                    );
                    chart_student_status.classList.add(
                        "chart-student-status-active"
                    );

                    student_status.classList.remove("student-status-active");
                    student_status.classList.add("student-status");
                    chartNotes.data.datasets[0].data = [approved, suspended];
                    chartNotes.data.labels = [
                        `Aprobados: ${approved}`,
                        `Desaprobados: ${suspended}`,
                    ];

                    state = true;
                }
            },
            complete: function (response) {
                if (state) {
                    chartNotes.update();
                }
            },
        });
    });
})(jQuery);
