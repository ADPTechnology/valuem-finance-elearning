import DataTableEs from "../../../../common/js/datatable_es.js";
import {
    Toast,
    ToastError,
    SwalDelete,
} from "../../../../common/js/sweet-alerts.js";
import { dateRangeConfig } from "../../../../common/js/utils.js";

$(() => {

    if ($('#events-table').length) {

        var eventsTable

        var inputDateRange = $('#date-range-input-event');
        var dateBtn = $('#daterange-btn-events')

        if (inputDateRange.length) {

            inputDateRange.val('Todos los registros');
            dateBtn.daterangepicker(dateRangeConfig, function (start, end) {
                if (start.format('YYYY-MM-DD') == '1970-01-01') {
                    inputDateRange.val('Todos los registros');
                } else {
                    inputDateRange.val('Del: ' + start.format('YYYY-MM-DD') + ' hasta el: ' + end.format('YYYY-MM-DD'))
                }

                eventsTable.draw()
            });
        }

        /* ---------- FILTER SELECT ----------*/

        $("html").on("change", ".select-filter-event", function () {
            eventsTable.draw();
        });

        var eventsTableEle = $("#events-table");
        var getDataUrl = eventsTableEle.data("url");
        eventsTable = eventsTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: {
                url: getDataUrl,
                data: function (data) {
                    data.from_date = $("#daterange-btn-events")
                        .data("daterangepicker")
                        .startDate.format("YYYY-MM-DD");
                    data.end_date = $("#daterange-btn-events")
                        .data("daterangepicker")
                        .endDate.format("YYYY-MM-DD");
                    data.search_course = $("#search_from_course_select").val();
                    data.search_instructor = $(
                        "#search_from_instructor_select"
                    ).val();
                    data.search_responsable = $(
                        "#search_from_responsable_select"
                    ).val();
                },
            },
            columns: [
                { data: "id", name: "id" },
                { data: "description", name: "description" },
                { data: "type", name: "type" },
                { data: "date", name: "date" },
                {
                    data: "exam.course.description",
                    name: "exam.course.description",
                },
                { data: "user.name", name: "user.name" },
                { data: "responsable.name", name: "responsable.name" },
                { data: "flg_asist", name: "flg_asist" },
                { data: "active", name: "active" },
            ],
            order: [[3, "desc"]],
            // dom: 'rtip'
        });
    }

    // ------------- EVENT SHOW -------------------

    if ($("#certifications-table").length) {

        $("#search_from_status_select").select2({
            minimumResultsForSearch: -1,
        });

        /* ----- CERTIFICATIONS TABLE ------*/

        var certificationsTableEle = $("#certifications-table");
        var getDataUrl = certificationsTableEle.data("url");
        var certificationsTable = certificationsTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: {
                url: getDataUrl,
                data: function (data) {
                    data.from_status = $("#search_from_status_select").val();
                },
            },
            columns: [
                { data: "id", name: "id" },
                { data: "user.dni", name: "user.dni" },
                { data: "user.name", name: "user.name" },
                {
                    data: "user.company.description",
                    name: "user.company.description",
                },
                { data: "score", name: "score" },
                { data: "status", name: "status" },
                { data: "enabled", name: "enabled", orderable: false },
                {
                    data: "assit",
                    name: "assit",
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                },
            ],
            order: [[0, "desc"]],
            // dom: 'rtip'
        });

        $("html").on("change", "#search_from_status_select", function () {
            certificationsTable.draw();
        });

        // -------------- VER CERTIFICADO ------------------------


        $("html").on("click", ".showCertification-btn", function () {
            var url = $(this).data("send");
            var modal = $("#showCertificationModal");

            $.ajax({
                type: "GET",
                url: url,
                dataType: "JSON",
                success: function (data) {

                    var modal_cont = modal.find('.modal-content')

                    modal_cont.html(data.html)
                },
                complete: function () {
                    modal.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });

    }
})
