import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";

$(() => {
    // Table Report Forgetting Curve

    var reportForgettingCurve = $("#reportForgettingCurve-table");
    var getDataUrl = reportForgettingCurve.data("url");
    let reportForgettingCurveTable = reportForgettingCurve.DataTable({
        responsive: true,
        language: DataTableEs,
        serverSide: true,
        processing: true,
        ajax: getDataUrl,
        columns: [
            { data: "id", name: "id" },
            { data: "title_curve", name: "title_curve" },
            { data: "min_score", name: "min_score" },
            { data: "course.description", name: "course.description" },
             { data: "user.id", name: "user.id" },
            { data: "user.name", name: "user.name" },
            {
                data: "steps_completed_septime",
                name: "steps_completed_septime",
                orderable: false,
                searchable: false,
            },
            { data: 'end_date_first', name:'end_date_first', orderable: false, searchable: false },
            { data: 'score_first', name:'score_first', orderable: false, searchable: false },
            {
                data: "steps_completed_fifteenth",
                name: "steps_completed_fifteenth",
                orderable: false,
                searchable: false,
            },
            { data: 'end_date_second', name:'end_date_second', orderable: false, searchable: false },
            { data: 'score_second', name:'score_second', orderable: false, searchable: false },
            { data: "qualification", name: "qualification" },
        ],
        order: [[0, "desc"]],
    });
});
