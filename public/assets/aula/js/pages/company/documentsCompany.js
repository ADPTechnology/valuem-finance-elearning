import DataTableEs from "../../../../common/js/datatable_es.js";
import {Toast, ToastError, SwalDelete} from "../../../../common/js/sweet-alerts.js";

$(function () {

    // ---------- DATATABLE -----------

    var companyFilesTableEle = $('#companyFiles_table')
    var getDataTable = companyFilesTableEle.data('url')
    var companyFilesTable = companyFilesTableEle.DataTable({
        language: DataTableEs,
        responsive: true,
        serverSide: true,
        processing: true,
        ajax: getDataTable,
        columns: [
            { data: 'id', name: 'id' },
            { data: 'file_path', name: 'file_path'},
            { data: 'created_at', name: 'created_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        order: [
            [3, 'desc']
        ]
    });


    // ---------- REGISTER ----------------

    var registerFilesForm = $('#storeFileForm').validate({
        rules: {
            "files[]": {
                required: true
            }
        },
        submitHandler: function (form, event) {
            event.preventDefault()
            var form = $(form)
            var loadSpinner = form.find('.loadSpinner')
            var modal = $('#storeFileModal')

            loadSpinner.toggleClass('active')
            form.find('.btn-save').attr('disabled', 'disabled')

            var formData = new FormData(form[0])

            $.ajax({
                method: form.attr('method'),
                url: form.attr('action'),
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'JSON',
                success: function (data) {
                    if (data.success) {

                        registerFilesForm.resetForm()
                        companyFilesTable.draw()

                        form.trigger('reset')
                        modal.modal('hide')

                        Toast.fire({
                            icon: 'success',
                            text: data.message
                        })

                    } else {
                        Toast.fire({
                            icon: 'error',
                            text: data.message
                        })
                    }
                },
                complete: function (data) {
                    form.find('.btn-save').removeAttr('disabled')
                    loadSpinner.toggleClass('active')
                },
                error: function (data) {
                    console.log(data)
                    ToastError.fire()
                }
            })
        }
    })



    // ----------- DESTROY FILE ---------------

    $('html').on('click', '.deleteFile', function () {

        var url = $(this).data('url')

        SwalDelete.fire().then(function (e) {
            if (e.value === true) {
                $.ajax({
                    type: 'DELETE',
                    url: url,
                    dataType: 'JSON',
                    success: function (data) {

                        if (data.success) {
                            companyFilesTable.ajax.reload(null, false)
                            Toast.fire({
                                icon: 'success',
                                text: data.message,
                            })
                        }
                        else {
                            Toast.fire({
                                icon: 'error',
                                text: data.message
                            })
                        }
                    },
                    error: function (data) {
                        ToastError.fire()
                    }
                });
            } else {
                e.dismiss;
            }
        }, function (dismiss) {
            return false;
        });
    })

})
