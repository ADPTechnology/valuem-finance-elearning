$(() => {

    $('html').on('click', '.btn-view-modules-files', function () {
        var url = $(this).data('url')
        var modal = $('#moduleFilesModal')
        var files_cont = modal.find('#module_files_cont')

        files_cont.empty()

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'JSON',
            success: function (data) {
                files_cont.html(data.html)
            },
            complete: function (data) {
                modal.modal('show')
            },
            error: function (data) {
                console.log(data)
            }
        })
    })

})
