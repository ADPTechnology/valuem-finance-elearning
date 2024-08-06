import DataTableEs from "../../../../common/js/datatable_es.js";
import {Toast, ToastError, SwalDelete} from "../../../../common/js/sweet-alerts.js";

$(function () {

    $('html').on('click', '.assignment-box .inner-btn-view', function () {
        var button = $(this)
        var parent = button.closest('.assignment-box')
        var container_assign = $('.participants_assign_container')

        var url = button.data('url')


        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'JSON',
            success: function (data) {

                container_assign.html(data.html)

                parent.siblings().removeClass('active')
                parent.addClass('active')
            },
            error: function (data) {
                console.log(data)
            }
        })
    })


    $('html').on('click', '.view_part_assignment.enable', function () {

        var button = $(this)
        var url = button.data('url')
        var urlStore = button.data('store')
        var modal = $('#updateAssignableScoreModal')
        var form = modal.find('#registerAssignablePointsForm')

        var modal_body = form.find('.modal-body-cont')
        modal_body.html('')

        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'JSON',
            success: function (data) {

                form.attr('action', urlStore)
                modal_body.html(data.html)

                modal.modal('show');
            },
            error: function (data) {
                console.log(data)
            }
        })


    })


    var registerAssignPointsForm = $('#registerAssignablePointsForm').validate({
        rules: {
            "points": {
                required: true,
                min: 0,
                max: 20
            }
        },
        submitHandler: function (form, event) {
            event.preventDefault()
            var form = $(form)
            var loadSpinner = form.find('.loadSpinner')
            var modal = $('#updateAssignableScoreModal')
            var listContainer = $('.participants_assign_container');

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

                        listContainer.html(data.html)
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

})
