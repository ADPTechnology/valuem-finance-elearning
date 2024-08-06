Chart.defaults.global.defaultFontSize = 10;
Chart.defaults.global.defaultFontColor = "black";

export let chartSatisfaction;

(function () {
    let chart_satisfaction = $("#chart-satisfaction");
    let satisfaction_status = $("#satisfaction-status");

    let satisfaction = chart_satisfaction.data("satisfaction");

    let brrs = $("#chart-satisfaction");

    let {
        muyInsatisfecho,
        insatisfecho,
        niSatisfechoNiInsatisfecho,
        satisfecho,
    } = satisfaction;

    if (
        muyInsatisfecho === 0 &&
        insatisfecho === 0 &&
        niSatisfechoNiInsatisfecho === 0 &&
        satisfecho === 0
    ) {
        chart_satisfaction.removeClass("chart-satisfaction-active");
        chart_satisfaction.addClass("chart-satisfaction");

        satisfaction_status.addClass("satisfaction-status-active");
        satisfaction_status.removeClass("satisfaction-status");
    } else {
        chart_satisfaction.removeClass("chart-satisfaction");
        chart_satisfaction.addClass("chart-satisfaction-active");

        satisfaction_status.removeClass("satisfaction-status-active");
        satisfaction_status.addClass("satisfaction-status");

        chartSatisfaction = new Chart(brrs, {
            type: "bar",
            data: {
                labels: [
                    "Muy Insatisfecho",
                    "Insatisfecho",
                    "Ni satisfecho ni Insatisfecho",
                    "Satisfecho",
                ],
                datasets: [
                    {
                        label: "Usuarios",
                        data: [
                            muyInsatisfecho,
                            insatisfecho,
                            niSatisfechoNiInsatisfecho,
                            satisfecho,
                        ],
                        backgroundColor: [
                            "rgba(255, 99, 132, 0.2)",
                            "rgba(255, 159, 64, 0.2)",
                            "rgba(255, 205, 86, 0.2)",
                            "rgba(125, 250, 112, 0.2)",
                        ],
                        borderColor: [
                            "rgb(255, 99, 132)",
                            "rgb(255, 159, 64)",
                            "rgb(255, 205, 86)",
                            "rgb(125, 250, 112)",
                        ],
                        borderWidth: 3,
                        borderRadius: 4,
                        responsive: true,
                        maxWidth: 12,
                        hoverBorderWidth: 5,
                    },
                ],
            },
            options: {
                maintainAspectRatio: false,
                legend: {
                    display: false,
                    position: "top",
                    labels: {
                        padding: 5,
                    },
                },
                tooltips: {
                    enabled: true,
                },
                scales: {
                    y: {
                        beginAtZero: true,
                    },
                },
                indexAxis: "y",
            },
        });
    }
})(jQuery);
