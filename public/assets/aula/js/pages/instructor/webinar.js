import DataTableEs from "../../../../common/js/datatable_es.js";

$(() => {
    if ($("#participants-table").length) {
        var participantsTableEle = $("#participants-table");
        var getDataUrl = participantsTableEle.data("url");
        var participantsTable = participantsTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "user.dni", name: "user.dni" },
                { data: "user.name", name: "user.name" },
                { data: "user.paternal", name: "user.paternal" },
                { data: "user.maternal", name: "user.maternal" },
                { data: "unlock_cert", name: "unlock_cert" },
                { data: "user.profile_user", name: "user.profile_user" },
            ],
            order: [[0, "desc"]],
        });
    }
});
