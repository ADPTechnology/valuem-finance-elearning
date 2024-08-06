import { Toast, ToastError, SwalDelete } from '../../../common/js/sweet-alerts.js'

$(function () {

    if ($('#authorization_modal').length) {

        const iziModalConfig = {
            overlayColor: 'rgba(0, 0, 0, 0.6)',
            theme: 'light',
            headerColor: '#6e6e6e',
            closeButton: true,
            padding: 25,
        }

        var authorizationModal = $('#authorization_modal').iziModal(iziModalConfig)
        var tutorialExmModal = $('#tutorial_ex_modal').iziModal(iziModalConfig)
    }

    if ($('#signature-pad').length) {

        var button = $('#btn_save_signature')
        var url = button.data('url')

        var wrapper = document.getElementById("signature-pad");
        var clearButton = wrapper.querySelector("[data-action=clear]");
        var undoButton = wrapper.querySelector("[data-action=undo]");
        var savePNGButton = wrapper.querySelector("[data-action=save-png]");
        var canvas = wrapper.querySelector("canvas");
        
        var signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgb(255, 255, 255)'
        });

        signaturePad.penColor = "#000000";

        function resizeCanvas() {

            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }

        window.onresize = resizeCanvas;
        resizeCanvas();

        clearButton.addEventListener("click", function (event) {
            signaturePad.clear();
        });

        undoButton.addEventListener("click", function (event) {
            var data = signaturePad.toData();

            if (data) {
                data.pop(); // remove the last dot or line
                signaturePad.fromData(data);
            }
        });

        savePNGButton.addEventListener("click", function (event) {
            if (signaturePad.isEmpty()) {
                Toast.fire({
                    icon: 'warning',
                    text: 'Por favor, provea una firma.'
                })

            } else {
                var dataURL = signaturePad.toDataURL("image/png")
                let _token = $('meta[name="csrf-token"]').attr('content')

                button.attr('disabled', 'disabled')

                $.ajax({
                    method: "POST",
                    url: url,
                    data: {
                        imgBase64: dataURL,
                        _token: _token
                    },
                    success: function (data) {

                        if (data.success) {
                            Toast.fire({
                                icon: 'success',
                                text: data.message
                            })
    
                            window.location.href = data.route;
                        }
                        else {
                            Toast.fire({
                                icon: 'error',
                                text: data.message
                            })
                        }
                    },
                    complete: function (data) {
                        button.removeAttr('disabled')
                    },
                    error: function (XMLHttpRequest, textStatus, errorThrown) {
                        ToastError.fire()
                    }
                })
            }
        });
    }
})