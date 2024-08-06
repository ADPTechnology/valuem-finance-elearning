
import DataTableEs from "../../../../common/js/datatable_es.js";

$(function () {

    if($('#usersCompany_table').length){

        var usersCompanyTableEle = $('#usersCompany_table');
        var getDataUrl = usersCompanyTableEle.data('url');
        var usersCompanyTable = usersCompanyTableEle.DataTable({
            language: DataTableEs,
            responsive: true,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: 'id', name: 'id' },
                { data: 'dni', name: 'dni' },
                { data: 'name', name: 'name' },
                { data: 'paternal', name: 'paternal'},
                { data: 'email', name: 'email' },
                { data: 'telephone', name: 'telephone' },
                { data: 'role', name: 'role' },
                { data: 'company.description', name: 'company.description'},
                { data: 'status-btn', name: 'status-btn', orderable: false, searchable: false },
            ],
            order: [
            [0, 'desc']
            ]
        });

    }

})

