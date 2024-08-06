import DataTableEs from "../../../../common/js/datatable_es.js";

$(function () {
    // ----- CERTIFICATIONS FOR COMPANY

    if ($("#certifications-index-table").length) {
        var certifcationIndexTable;

        if ($("#date-range-input-certifications").length) {
            $("#date-range-input-certifications").val("Todos los registros");

            $(".daterange-cus").daterangepicker({
                locale: { format: "YYYY-MM-DD" },
                drops: "down",
                opens: "right",
            });

            $("#daterange-btn-certifications").daterangepicker(
                {
                    ranges: {
                        Todo: [moment("1970-01-01"), moment("3000-01-01")],
                        Hoy: [moment(), moment().add(1, "days")],
                        Ayer: [moment().subtract(1, "days"), moment()],
                        "Últimos 7 días": [
                            moment().subtract(6, "days"),
                            moment().add(1, "days"),
                        ],
                        "Últimos 30 días": [
                            moment().subtract(29, "days"),
                            moment().add(1, "days"),
                        ],
                        "Este mes": [
                            moment().startOf("month"),
                            moment().endOf("month").add(1, "days"),
                        ],
                        "Último mes": [
                            moment().subtract(1, "month").startOf("month"),
                            moment()
                                .subtract(1, "month")
                                .endOf("month")
                                .add(1, "days"),
                        ],
                    },
                    startDate: moment("1970-01-01"),
                    endDate: moment("3000-01-01"),
                },
                function (start, end) {
                    if (start.format("YYYY-MM-DD") == "1970-01-01") {
                        $("#date-range-input-certifications").val(
                            "Todos los registros"
                        );
                    } else {
                        $("#date-range-input-certifications").val(
                            "Del: " +
                                start.format("YYYY-MM-DD") +
                                " hasta el: " +
                                end.format("YYYY-MM-DD")
                        );
                    }

                    certifcationIndexTable.draw();
                }
            );
        }

        /* ---------- FILTER SELECT ----------*/

        $("#search_from_status_select").select2({
            minimumResultsForSearch: -1,
        });

        $("html").on("change", ".select-filter-certification", function () {
            certifcationIndexTable.draw();
        });

        var certificationIndexTableEle = $("#certifications-index-table");
        var getDataUrl = certificationIndexTableEle.data("url");
        certifcationIndexTable = certificationIndexTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: {
                url: getDataUrl,
                data: function (data) {
                    data.from_date = $("#daterange-btn-certifications")
                        .data("daterangepicker")
                        .startDate.format("YYYY-MM-DD");
                    data.end_date = $("#daterange-btn-certifications")
                        .data("daterangepicker")
                        .endDate.format("YYYY-MM-DD");
                    // data.company = $("#search_from_company_select").val();
                    data.course = $("#search_from_course_select").val();
                    data.status = $("#search_from_status_select").val();
                },
            },
            columns: [
                { data: "certifications.id", name: "certifications.id" },
                { data: "user.dni", name: "user.dni" },
                { data: "user.name", name: "user.name" },
                // { data: "company.description", name: "company.description" },
                {
                    data: "event.exam.course.description",
                    name: "event.exam.course.description",
                },
                { data: "event.description", name: "event.description" },
                { data: "event.date", name: "event.date" },
                { data: "score", name: "score" },
                {
                    data: "exam",
                    name: "exam",
                    orderable: false,
                    searchable: false,
                },
                // {
                //     data: "certification",
                //     name: "certification",
                //     orderable: false,
                //     searchable: false,
                // },
                // {
                //     data: "documents",
                //     name: "documents",
                //     orderable: false,
                //     searchable: false,
                // },
            ],
            order: [[0, "desc"]],
        });
    }
});
