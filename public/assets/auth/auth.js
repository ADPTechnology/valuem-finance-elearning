$(function () {


    $('.select2').select2();


    // -------------- REGISTER ----------------

    if ($('#register-form').length) {

        const registerRules = {
            dni: {
                required: true,
                maxlength: 11,
                remote: {
                    url: $('#register-form').data('validate'),
                    type: 'GET',
                    dataType: 'JSON',
                    data: {
                        dni: function () {
                            return $('#register-form').find('input[name=dni]').val()
                        }
                    }
                }
            },
            name: {
                required: true,
                maxlength: 255,
            },
            paternal: {
                required: true,
                maxlength: 255
            },
            maternal: {
                required: true,
                maxlength: 255
            },
            email: {
                required: true,
                maxlength: 255,
                email: true
            },
            password: {
                required: true,
                maxlength: 255,
                minlength: 8,
                oneLowercase: true,
                oneUppercase: true,
                oneNumber: true,
                oneSpecialChar: true,
            }
        }

        var registerUserForm = $('#register-form').validate({
            rules: registerRules,
            messages: {
                dni: {
                    remote: 'Este usuario ya está registrado'
                },
                password: {
                    oneUppercase: "Ingrese al menos una mayúscula",
                    oneLowercase: "Ingrese al menos una minúscula",
                    oneNumber: "Ingrese al menos un número",
                    oneSpecialChar: "Ingrese al menos un caracter especial"
                }
            },
            submitHandler: function (form, event) {
                event.preventDefault()
                var form = $(form)
                var loadSpinner = form.find('.loadSpinner')

                loadSpinner.toggleClass('active')
                form.find('.btn-save').attr('disabled', 'disabled')

                $.ajax({
                    method: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize(),
                    dataType: 'JSON',
                    success: function (data) {

                        if (data.success) {

                            registerUserForm.resetForm()
                            form.trigger('reset')

                            let messageContainer = $('#register-content-box')
                            messageContainer.html(data.html);
                        }
                        else {
                            Toast.fire({
                                icon: 'error',
                                text: data.message
                            })
                        }
                    },
                    complete: function (data) {
                        loadSpinner.toggleClass('active')
                        form.find('.btn-save').removeAttr('disabled')
                    },
                    error: function (data) {
                        console.log(data)
                        ToastError.fire()
                    }
                })
            }
        })

    }


    $('#registerCompanySelect').select2({
        placeholder: 'Selecciona una empresa'
    })

    $('#registerMiningUnitsSelect').select2({
        placeholder: 'Selecciona una o más unidades mineras',
        closeOnSelect: false
    })

    jQuery.extend(jQuery.validator.messages, {
        required:
            '<i class="fa-solid fa-circle-exclamation"></i> &nbsp; Este campo es obligatorio',
        email: "Ingrese un email válido",
        number: "Por favor, ingresa un número válido",
        url: "Por favor, ingresa una URL válida",
        max: jQuery.validator.format(
            "Por favor, ingrese un valor menor o igual a {0}"
        ),
        min: jQuery.validator.format(
            "Por favor, ingrese un valor mayor o igual a {0}"
        ),
        step: jQuery.validator.format("Ingrese un número múltiplo de {0}"),
        maxlength: jQuery.validator.format("Ingrese menos de {0} caracteres."),
        minlength: jQuery.validator.format("Ingrese al menos {0} caracteres."),
        accept: "Por favor, selecciona un archivo con extensión válida.",
    });

    jQuery.validator.addMethod("oneUppercase", function (value, element, param) {
        return (
            this.optional(element) ||
            (/(?=.*[A-Z])/.test(value) == param)
        );
    });

    jQuery.validator.addMethod("oneLowercase", function (value, element, param) {
        return (
            this.optional(element) ||
            (/(?=.*[a-z])/.test(value) == param)
        );
    });

    jQuery.validator.addMethod("oneNumber", function (value, element, param) {
        return (
            this.optional(element) ||
            (/(?=.*\d)/.test(value) == param)
        );
    });

    jQuery.validator.addMethod("oneSpecialChar", function (value, element, param) {
        return (
            this.optional(element) ||
            (/(?=.*\W)/.test(value) == param)
        );
    });

})
