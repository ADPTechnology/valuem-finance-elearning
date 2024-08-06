import { chartSatisfaction as chartSatisfactionForCourse } from "./kpiSatisfaction.js";

(function () {
    let chartSatisfaction = document.getElementById("chart-satisfaction");
    let satisfactionStatus = document.getElementById("satisfaction-status");
    let state;

    $("#search_from_course_select_satisfaction").change(function () {
        let getUrl = $(this).data("url");

        $.ajax({
            url: getUrl,
            type: "GET",
            data: { course: $(this).val() },
            dataType: `JSON`,
            success: function (response) {
                let data = JSON.parse(response);

                let {
                    muyInsatisfecho,
                    insatisfecho,
                    niSatisfechoNiInsatisfecho,
                    satisfecho,
                } = data;

                if (
                    muyInsatisfecho === 0 &&
                    insatisfecho === 0 &&
                    niSatisfechoNiInsatisfecho === 0 &&
                    satisfecho === 0
                ) {
                    chartSatisfaction.classList.remove(
                        "chart-satisfaction-active"
                    );
                    chartSatisfaction.classList.add("chart-satisfaction");
                    satisfactionStatus.classList.add(
                        "satisfaction-status-active"
                    );
                    satisfactionStatus.classList.remove("satisfaction-status");

                    state = false;
                } else {
                    chartSatisfaction.classList.remove("chart-satisfaction");
                    chartSatisfaction.classList.add(
                        "chart-satisfaction-active"
                    );

                    satisfactionStatus.classList.remove(
                        "satisfaction-status-active"
                    );
                    satisfactionStatus.classList.add("satisfaction-status");

                    chartSatisfactionForCourse.data.datasets[0].data = [
                        muyInsatisfecho,
                        insatisfecho,
                        niSatisfechoNiInsatisfecho,
                        satisfecho,
                    ];
                    chartSatisfactionForCourse.data.labels = [
                        "Muy Insatisfecho",
                        "Insatisfecho",
                        "Ni satisfecho ni Insatisfecho",
                        "Satisfecho",
                    ];

                    state = true;
                }
            },
            complete: function (response) {
                if (state) {
                    chartSatisfactionForCourse.update();
                }
            },
        });
    });
})(jQuery);
