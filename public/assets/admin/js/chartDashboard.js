Chart.defaults.global.defaultFontSize = 10;
Chart.defaults.global.defaultFontColor = "black";

(function () {
    // * First chart

    let ctx = $("#chart-student-status");

    let { approved, suspended } = ctx.data("statusCertifications");

    let chart_student_status = document.getElementById("chart-student-status");
    let student_status = document.getElementById("student-status");

    if (approved === 0 && suspended === 0) {
        chart_student_status.remove();
        student_status.classList.add("student-status-active");
        student_status.classList.remove("student-status");
    } else {
        new Chart(ctx, {
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
                        backgroundColor: ["rgb(41, 175, 0)", "rgb(204, 7, 7)"],
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

    // * Second chart

    let brrs = $("#chart-types-of-users");

    let types_users = brrs.data("types");

    const {
        admin,
        companies,
        security_manager,
        security_manager_admin,
        instructor,
        participants,
        supervisor,
        super_admin,
        technical_support,
    } = types_users;

    new Chart(brrs, {
        type: "bar",
        data: {
            labels: [
                `Administrador`,
                `Empresas`,
                `G. Seguridad Nexa`,
                `I. Seguridad Nexa`,
                `Instructor`,
                // "Participante",
                `Soporte Tecnico`,
                `Super Administrador`,
                `Supervisor`,
            ],
            datasets: [
                {
                    label: "Usuarios",
                    data: [
                        admin,
                        companies,
                        security_manager_admin,
                        security_manager,
                        instructor,
                        // participante,
                        technical_support,
                        super_admin,
                        supervisor,
                    ],
                    backgroundColor: [
                        "rgba(255, 99, 132, 0.2)",
                        "rgba(255, 159, 64, 0.2)",
                        "rgba(255, 205, 86, 0.2)",
                        "rgba(75, 192, 192, 0.2)",
                        "rgba(54, 162, 235, 0.2)",
                        "rgba(153, 102, 255, 0.2)",
                        "rgba(103, 102, 255, 0.2)",
                        "rgba(201, 203, 207, 0.2)",
                    ],
                    borderColor: [
                        "rgb(255, 99, 132)",
                        "rgb(255, 159, 64)",
                        "rgb(255, 205, 86)",
                        "rgb(75, 192, 192)",
                        "rgb(54, 162, 235)",
                        "rgb(153, 102, 255)",
                        "rgb(103, 102, 255)",
                        "rgb(201, 203, 207)",
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
