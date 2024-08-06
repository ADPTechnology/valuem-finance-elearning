import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";

$(function () {

    const dateRangeConfig = {
        ranges: {
            'Todo': [moment('1970-01-01'), moment('3000-01-01')],
            'Hoy': [moment(), moment().add(1, 'days')],
            'Ayer': [moment().subtract(1, 'days'), moment()],
            'Últimos 7 días': [moment().subtract(6, 'days'), moment().add(1, 'days')],
            'Últimos 30 días': [moment().subtract(29, 'days'), moment().add(1, 'days')],
            'Este mes': [moment().startOf('month'), moment().endOf('month').add(1, 'days')],
            'Último mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month').add(1, 'days')]
        },
        startDate: moment('1970-01-01'),
        endDate: moment('3000-01-01'),
    }

    if ($('#files-table').length) {

        let filesTable;

        var inputDateRange = $('#date-range-input-files');
        var dateBtn = $('#daterange-btn-files');

        if (inputDateRange.length) {

            inputDateRange.val('Todos los registros');
            dateBtn.daterangepicker(dateRangeConfig, function (start, end) {
                if (start.format('YYYY-MM-DD') == '1970-01-01') {
                    inputDateRange.val('Todos los registros');
                } else {
                    inputDateRange.val('Del: ' + start.format('YYYY-MM-DD') + ' hasta el: ' + end.format('YYYY-MM-DD'))
                }

                filesTable.draw()
            });
        }

        var filesTableEle = $('#files-table');
        var getDataUrl = filesTableEle.data('url');
        filesTable = filesTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: {
                'url': getDataUrl,
                'data': function (data) {
                    data.from_date = $('#daterange-btn-files').data('daterangepicker').startDate.format('YYYY-MM-DD')
                    data.end_date = $('#daterange-btn-files').data('daterangepicker').endDate.format('YYYY-MM-DD')
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'file_path', name: 'file_path' },
                { data: 'category', name: 'category' },
                { data: 'created_at', name: 'created_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false },
            ],
            order: [
                [3, 'desc']
            ]
        });


        // ---------- STORE FILE ---------------------

        var storeFileForm = $('#storeFileForm').validate({
            rules: {
                "files[]": {
                    required: true,
                    extension: "pdf",
                },
            },
            messages: {
                "files[]": {
                    extension: 'Por favor, sólo sube archivos PDF',
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

                            Toast.fire({
                                icon: 'success',
                                text: data.message
                            }).then((result) => {
                                if (result.dismiss === Swal.DismissReason.timer) {
                                    if (data.foundErrors) {
                                        var notebody = $('<ul/>')
                                        $.each(data.rejected, function (key, values) {
                                            var sub_li = $('<li/>').html(values)
                                            notebody.append(sub_li)
                                        })

                                        Swal.fire({
                                            toast: true,
                                            position: 'top-end',
                                            showConfirmButton: true,
                                            timerProgressBar: false,
                                            icon: 'warning',
                                            title: 'No se cargaron los archivos: ',
                                            html: notebody[0].outerHTML
                                        })
                                    }
                                }
                            })

                            form.trigger('reset')
                            storeFileForm.resetForm()
                            filesTable.draw()

                            modal.modal('hide')
                        }
                        else if (data.foundErrors) {

                            var notebody = $('<ul/>')
                            $.each(data.rejected, function (key, values) {
                                var sub_li = $('<li/>').html(values)
                                notebody.append(sub_li)
                            })

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: true,
                                timerProgressBar: false,
                                icon: 'error',
                                title: 'No se cargaron los archivos: ',
                                html: notebody[0].outerHTML
                            })
                        }
                        else {
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
                                filesTable.ajax.reload(null, false)
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
    }

})
