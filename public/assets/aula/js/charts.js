Chart.defaults.global.defaultFontSize = 10;
Chart.defaults.global.defaultFontColor = "black";

(function () {
    $(".course-progress-results").each(function (e) {
        var id = $(this).attr("id");
        var dataApproved = $(this).data("approved");
        var dataSuspended = $(this).data("suspended");

        const ctx = $("#progress-" + id);

        var progressDoughnut = new Chart(ctx, {
            type: "doughnut",
            data: {
                labels: ["Aprobado", "Desaprobado"],
                datasets: [
                    {
                        label: "Evaluation Progress",
                        data: [dataApproved, dataSuspended],
                        backgroundColor: ["rgb(41, 175, 0)", "rgb(204, 7, 7)"],
                        responsive: true,
                    },
                ],
            },
            options: {
                cutoutPercentage: 65,
                maintainAspectRatio: false,
                legend: {
                    display: true,
                    position: "right",
                    labels: {
                        padding: 25,
                    },
                },
                tooltips: {
                    enabled: true,
                },
            },
        });
    });

    $(".freecourse-progress-chart-box").each(function (e) {
        var id = $(this).attr("id");
        var dataCompleted = $(this).data("completed");
        var dataTotal = $(this).data("total");

        const pchart = $("#freecourse-" + id);

        var freeCourseChart = new Chart(pchart, {
            type: "doughnut",
            data: {
                labels: ["Completado", "Total"],
                datasets: [
                    {
                        label: "Evaluation Progress",
                        data: [dataCompleted, dataTotal],
                        backgroundColor: ["rgb(41, 175, 0)", "rgb(204, 7, 7)"],
                        responsive: true,
                    },
                ],
            },
            options: {
                cutoutPercentage: 40,
                maintainAspectRatio: false,
                legend: {
                    display: false,
                    position: "right",
                    labels: {
                        padding: 25,
                    },
                },
                tooltips: {
                    enabled: false,
                },
            },
        });
    });

    $(".webinar-progress-chart-box").each(function (e) {
        var id = $(this).attr("id");
        var dataCompleted = $(this).data("completed");
        var dataTotal = $(this).data("total");

        const pchart = $("#webinar-" + id);

        var freeCourseChart = new Chart(pchart, {
            type: "doughnut",
            data: {
                labels: ["Completado", "Total"],
                datasets: [
                    {
                        label: "Evaluation Progress",
                        data: [dataCompleted, dataTotal],
                        backgroundColor: ["rgb(41, 175, 0)", "rgb(204, 7, 7)"],
                        responsive: true,
                    },
                ],
            },
            options: {
                cutoutPercentage: 40,
                maintainAspectRatio: false,
                legend: {
                    display: false,
                    position: "right",
                    labels: {
                        padding: 25,
                    },
                },
                tooltips: {
                    enabled: false,
                },
            },
        });
    });

    // $('.specCourse-progress-chart-box').each(function(e){

    //     var id = $(this).attr('id');
    //     var dataCompleted = $(this).data('completed');
    //     var dataTotal = $(this).data('total');

    //     const pchart = $('#specCourse-'+id);

    //     var specCourseChart = new Chart(pchart, {
    //       type: 'doughnut',
    //       data: {
    //         labels: [
    //           'Completado',
    //           'Total',
    //         ],
    //         datasets: [{
    //           label: 'Evaluation Progress',
    //           data: [dataCompleted, dataTotal],
    //           backgroundColor: [
    //             'rgb(41, 175, 0)',
    //             'rgb(204, 7, 7)'
    //           ],
    //           responsive: true,
    //         }]
    //       },
    //       options: {
    //         cutoutPercentage: 40,
    //         maintainAspectRatio: false,
    //         legend: {
    //           display: false,
    //           position: 'right',
    //           labels: {
    //             padding: 25,
    //           }
    //         },
    //         tooltips: {
    //           enabled: false,
    //         }
    //       },
    //     })

    //   })
})(jQuery);
