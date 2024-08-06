import DataTableEs from "../../../common/js/datatable_es.js";

$(function () {

    if ($('#events-table').length) {

        var columns = [
            { data: 'id', name: 'id' },
            { data: 'description', name: 'description' },
            { data: 'date', name: 'date' },
            { data: 'participants', name: 'participants', searchable: false, orderable: false },
        ]

        var eventsTableEle = $('#events-table');
        var getDataUrl = eventsTableEle.data('url');

        $.ajax({
            url: getDataUrl,
            type: 'GET',
            dataType: 'JSON',
            data: {
                dynamic_columns: true
            },
            success: function (data) {

                $.each(data.columns, function (key, value){
                    columns.push({
                        data: value,
                        name: value,
                        searchable: false,
                        orderable: false,
                        className: 'text-center'
                    })
                })

                var eventsTable = eventsTableEle.DataTable({
                    responsive: true,
                    language: DataTableEs,
                    serverSide: true,
                    processing: true,
                    ajax: getDataUrl,
                    columns: columns,
                    order: [
                        [2, 'desc']
                    ]
                });
            },
            error: function (data) {
                console.log(data)
            }
        })
    }

    if ($('#participants-table').length) {

        var participantsTableEle = $('#participants-table');
        var getDataUrl = participantsTableEle.data('url');
        var participantsTable = participantsTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: 'id', name: 'id'},
                { data: 'user.dni', name: 'user.dni' },
                { data: 'user.name', name: 'user.name' },
                { data: 'user.paternal', name: 'user.paternal' },
                { data: 'user.maternal', name: 'user.maternal' },
                { data: 'user.company.description', name: 'user.company.description' },
                { data: 'user.miningUnits', name: 'user.miningUnits', searchable: false, orderable: false},
            ],
            order: [
                [0, 'desc']
            ]
        });
    }
})