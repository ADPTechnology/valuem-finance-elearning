Chart.defaults.global.defaultFontSize = 10;
Chart.defaults.global.defaultFontColor = "black";

export let chartNotes;

(function () {
    $("#search_from_course_select").select2({
        placeholder: "Selecciona un curso",
        allowClear: true,
    });

    $("#search_from_course_select_satisfaction").select2({
        placeholder: "Selecciona un curso",
        allowClear: true,
    });

    let ctx = $("#chart-student-status");
    let { approved, suspended } = ctx.data("status-certifications");

    let chart_student_status = document.getElementById("chart-student-status");
    let student_status = document.getElementById("student-status");

    if (approved === 0 && suspended === 0) {
        chart_student_status.classList.add("chart-student-status");
        chart_student_status.classList.remove("chart-student-status-active");

        student_status.classList.add("student-status-active");
        student_status.classList.remove("student-status");
    } else {
        chart_student_status.classList.remove("chart-student-status");
        chart_student_status.classList.add("chart-student-status-active");

        chartNotes = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: [
                    `Aprobados: ${approved}`,
                    `Desaprobados: ${suspended}`,
                ],
                datasets: [
                    {
                        label: "Estado de Usuarios los últimos 30 días",
                        data: [approved, suspended],
                        backgroundColor: [
                            "rgb(119, 215, 138)",
                            "rgb(253, 80, 80)",
                        ],
                        responsive: true,
                    },
                ],
            },
            options: {
                cutoutPercentage: 55,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: "bottom",
                    labels: {
                        padding: 20,
                    },
                },
                tooltips: {
                    enabled: false,
                },
            },
        });
    }

    let brrs = $("#chart-types-of-users");

    let types_users = brrs.data("types");

    const { DIVERGENTE, CONVERGENTE, ASIMILADOR, ACOMODADOR } = types_users;

    new Chart(brrs, {
        type: "bar",
        data: {
            labels: ["Divergente", "Convergente", "Asimilador", "Acomodador"],
            datasets: [
                {
                    label: "Usuarios",
                    data: [DIVERGENTE, CONVERGENTE, ASIMILADOR, ACOMODADOR],
                    backgroundColor: [
                        "rgba(255, 205, 86, 0.2)",
                        "rgba(75, 192, 192, 0.2)",
                        "rgba(255, 99, 132, 0.2)",
                        "rgba(255, 159, 64, 0.2)",
                    ],
                    borderColor: [
                        "rgb(255, 205, 86)",
                        "rgb(75, 192, 192)",
                        "rgb(255, 99, 132)",
                        "rgb(255, 159, 64)",
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
})(jQuery);
