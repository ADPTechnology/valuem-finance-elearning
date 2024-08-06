import DataTableEs from "../../../../common/js/datatable_es.js";

$(function () {

    //-----------  USER PROFILE SURVEYS TABLE ----------------

    var userProfileSurveysTableEle = $("#profileUserSurveys_table");
    var getDataUrl = userProfileSurveysTableEle.data("url");
    var userProfileSurveysTable = userProfileSurveysTableEle.DataTable({
        responsive: true,
        language: DataTableEs,
        serverSide: true,
        processing: true,
        ajax: {
            url: getDataUrl,
            data: function (data) {
                data.survey = "user_profile";
            },
        },
        columns: [
            { data: "id", name: "id" },
            { data: "user.dni", name: "user.dni" },
            { data: "user.paternal", name: "user.paternal" },
            { data: "user.maternal", name: "user.maternal" },
            { data: "user.name", name: "user.name" },
            {
                data: "company.description",
                name: "company.description",
                orderable: false,
            },
            { data: "survey.name", name: "survey.name", orderable: false },
            { data: "end_time", name: "end_time" },
            { data: "ec", name: "ec", orderable: false },
            { data: "or", name: "or", orderable: false },
            { data: "ca", name: "ca", orderable: false },
            { data: "ea", name: "ea", orderable: false },
        ],
        order: [[0, "desc"]],
        // dom: 'rtip'
    });
});
