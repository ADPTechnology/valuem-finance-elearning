import {
    SwalDelete,
    Toast,
    ToastError,
} from "../../../../common/js/sweet-alerts.js";

import {
    initImageChange,
    setActiveCheckbox,
    getSummernoteConfig,
    InitSelect2,
    InitAjaxSelect2,
    initImageEditChange
} from "../../../../common/js/utils.js";

$(function () {

    // * --------- LOGO ----------

    if ($('#logo-list-container').length) {

        //* -------- STORE -------------

        var logoFormStore = $('#registerLogoImageForm')
        var inputlogoImageStore = logoFormStore.find('.input-logo-image')

        initImageChange(inputlogoImageStore, logoFormStore, Toast)

        var registerLogoForm = $("#registerLogoImageForm").validate({
            rules: {
                image: {
                    required: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault()

                var form = $(form)
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#RegisterLogoImageModal");
                var img_holder = form.find(".img-holder");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = new FormData(form[0]);

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        var logo_list = $('#logo-list-container')
                        var logo_sidebar = $('.dynamic-logo-container')

                        logo_list.html(data.html)

                        $.each(logo_sidebar, function (i) {
                            $(this).html(data.html_sidebar)
                        })

                        inputlogoImageStore.val("");
                        registerLogoForm.resetForm();

                        $(img_holder).empty();

                        modal.modal("hide");

                        Toast.fire({
                            icon: "success",
                            text: data.message,
                        });
                    },
                    complete: function (data) {
                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").removeAttr("disabled");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    }
                })
            }
        })


        // * -------- ELIMINAR ----------

        $('html').on('click', '.delete-logo-img-btn', function () {
            var url = $(this).data('url')

            SwalDelete.fire().then(
                function (e) {
                    if (e.value === true) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (result) {
                                if (result.success === true) {

                                    var logo_list = $('#logo-list-container')
                                    var logo_sidebar = $('.dynamic-logo-container')

                                    logo_list.html(result.html)

                                    $.each(logo_sidebar, function (i) {
                                        $(this).html(result.html_sidebar)
                                    })

                                    Toast.fire({
                                        icon: "success",
                                        text: result.message,
                                    });
                                } else {
                                    Toast.fire({
                                        icon: "error",
                                        text: result.message,
                                    });
                                }
                            },
                            error: function (result) {
                                ToastError.fire();
                                console.log(result);
                            },
                        });
                    } else {
                        e.dismiss;
                    }
                },
                function (dismiss) {
                    return false;
                }
            );
        })
    }

    // * --------- WSP -----------

    if ($('#editConfigForm').length) {

        let editConfigForm = $("#editConfigForm").validate({
            rules: {
                whatsapp_message: {
                    maxlength: 100,
                },
                whatsapp_number: {
                    required: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editConfigModal");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = form.serializeArray();

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: $.param(formData),
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            editConfigForm.resetForm();

                            form.trigger("reset");

                            $("#editConfigForm").html(data.html);

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                text: data.message,
                            });
                        }
                    },
                    complete: function (data) {
                        modal.modal("hide");
                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").removeAttr("disabled");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            },
        });
    }

    // * --------- CARRUSEL PAGINA PRINCIPAL ---------

    if ($('#p-banners-list-container').length) {

        setActiveCheckbox('.status_p_banner_checkbox', '.txt_status_p_banner')

        const P_BANNER_ORDER_SELECT_CONFIG = {
            minimumResultsForSearch: -1,
        }
        InitAjaxSelect2('.p_banner_order_select', P_BANNER_ORDER_SELECT_CONFIG, 'publishing_order')

        // ------- STORE ----------

        var pBannerFormStore = $('#registerPrincipalBannerForm')
        var pBannerSmntEle = pBannerFormStore.find('.p_banner_smnt_content')
        var pBannerImageStore = pBannerFormStore.find('.input_p_banner_image')

        initImageChange(pBannerImageStore, pBannerFormStore, Toast)

        var registerPBannerForm = $("#registerPrincipalBannerForm").validate({
            rules: {
                title: {
                    maxlength: 100,
                },
                content: {
                    maxlength: 5000,
                },
                image: {
                    required: true,
                },
            },
            errorElement: "label",
            errorClass: "is-invalid",
            validClass: "is-valid",
            ignore: ":hidden:not(.summernote-card-editor),.note-editable.card-block",
            errorPlacement: function (error, element) {
                error.addClass("error");
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.siblings("label"));
                } else if (element.hasClass("summernote")) {
                    error.insertAfter(element.siblings(".note-editor"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#registerPrincipalBannerModal");
                var img_holder = form.find(".img-holder");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = new FormData(form[0]);

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {

                            pBannerSmntEle.summernote('reset')

                            var pBannersContainer = $("#p-banners-list-container");
                            pBannersContainer.html(data.html);
                            pBannerImageStore.val("");
                            registerPBannerForm.resetForm();

                            $(img_holder).empty();

                            modal.modal("hide");

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                text: data.message,
                            });
                        }
                    },
                    complete: function (data) {
                        form.find(".btn-save").removeAttr("disabled");
                        loadSpinner.toggleClass("active");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            },
        });

        $('#registerPrincipalBannerModal').on('show.bs.modal', function () {
            pBannerSmntEle.summernote(getSummernoteConfig(pBannerSmntEle, registerPBannerForm))
            $(this).find("textarea[name=content]").empty();
        })

        $('#registerPrincipalBannerModal').on('hidden.bs.modal', function () {
            pBannerSmntEle.summernote("destroy");
            $(this).find("textarea[name=content]").empty();
        })

        // ---------- EDITAR ----------

        var pBannerFormEdit = $('#editPrincipalBannerForm')
        var pBannerEditSmntEle = pBannerFormEdit.find('.p_banner_smnt_content')
        var pBannerImageEdit = pBannerFormEdit.find('.input_p_banner_image')

        initImageEditChange(pBannerImageEdit, pBannerFormEdit, Toast)

        var pBannerEditForm = pBannerFormEdit.validate({
            rules: {
                title: {
                    maxlength: 100,
                },
                content: {
                    maxlength: 5000,
                },
                publishing_order: {
                    required: true
                }
            },
            errorElement: "label",
            errorClass: "is-invalid",
            validClass: "is-valid",
            ignore: ":hidden:not(.summernote-card-editor),.note-editable.card-block",
            errorPlacement: function (error, element) {
                error.addClass("error");
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.siblings("label"));
                } else if (element.hasClass("summernote")) {
                    error.insertAfter(element.siblings(".note-editor"));
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function (form, event) {
                event.preventDefault();
                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editPrincipalBannerModal");
                var img_holder = form.find(".img-holder");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = new FormData(form[0]);

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            pBannerEditSmntEle.summernote("reset");

                            var bannersList = $('#p-banners-list-container')
                            bannersList.html(data.html)

                            pBannerEditForm.resetForm();
                            form.trigger("reset");

                            $(img_holder).empty();

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });

                            modal.modal("hide");

                        } else {
                            Toast.fire({
                                icon: "error",
                                text: data.message,
                            });
                        }
                    },
                    complete: function (data) {
                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").removeAttr("disabled");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            },
        });

        $('html').on('click', '.edit-p-banner-btn', function () {
            var url = $(this).data('url')
            var getDataUrl = $(this).data('send')
            var modal = $('#editPrincipalBannerModal')
            var form = modal.find('#editPrincipalBannerForm')

            form.attr('action', url)

            form.validate()
            pBannerImageEdit.rules("remove", "required")

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let banner = data.banner;

                    modal.find(".img-holder")
                        .html(
                            '<img class="img-fluid card_img" id="image-banner-edit" src="' +
                            data.url_img +
                            '"></img>'
                        );
                    modal.find(".input_p_banner_image")
                        .attr(
                            "data-value",
                            '<img scr="' +
                            data.url_img +
                            '" class="img-fluid card_img"></img>'
                        );
                    modal.find(".input_p_banner_image").val("");

                    $.each(banner, function (key, value) {
                        let input = form.find('[name=' + key + ']')
                        if (input) { input.val(value) }
                    })

                    form.find('.p_banner_order_select').select2("trigger", "select", {
                        data: { id: banner.publishing_order, text: banner.publishing_order }
                    });

                    if (banner.status == 1) {
                        form.find(".status_p_banner_checkbox").prop("checked", true);
                        form.find('.txt_status_p_banner').html('Activo')
                    } else {
                        form.find(".status_p_banner_checkbox").prop("checked", false);
                        form.find('.txt_status_p_banner').html('Inactivo')
                    }
                },
                complete: function (data) {
                    modal.modal("show");
                    let dataCard = data.responseJSON;
                    modal.find(".note-editable").html(dataCard["banner"].content);
                },
                error: function (data) {
                    console.log(data);
                    ToastError.fire();
                },
            });
        })

        $("#editPrincipalBannerModal").on("show.bs.modal", function () {
            pBannerEditSmntEle.summernote(
                getSummernoteConfig(pBannerEditSmntEle, pBannerEditForm)
            );
        });

        $("#editPrincipalBannerModal").on("hidden.bs.modal", function () {
            pBannerEditSmntEle.summernote("destroy");
        });


        // -------- ELIMINAR ----------

        $('html').on('click', '.delete-p-banner-btn', function () {
            var url = $(this).data('url')

            SwalDelete.fire().then(
                function (e) {
                    if (e.value === true) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (result) {
                                if (result.success === true) {
                                    var bannersContainer = $('#p-banners-list-container')
                                    bannersContainer.html(result.html)

                                    Toast.fire({
                                        icon: "success",
                                        text: result.message,
                                    });
                                } else {
                                    Toast.fire({
                                        icon: "error",
                                        text: result.message,
                                    });
                                }
                            },
                            error: function (result) {
                                ToastError.fire();
                                console.log(result);
                            },
                        });
                    } else {
                        e.dismiss;
                    }
                },
                function (dismiss) {
                    return false;
                }
            );
        })


    }


    // * ----------------- SLIDER IMAGES ----------------- *

    if ($("#sliderImages-list-container").length) {

        $("#register-imagenSlider-status-checkbox").change(function () {
            var txtDesc = $("#txt-register-status-banner");
            if (this.checked) {
                txtDesc.html("Activo");
            } else {
                txtDesc.html("Inactivo");
            }
        });

        var banerImageRegister = $("#input-slider-image-register");

        banerImageRegister.val("");
        banerImageRegister.on("change", function () {
            var img_path = $(this)[0].value;
            var img_holder = $(this)
                .closest("#registerSliderImageForm")
                .find(".img-holder");
            var img_extension = img_path
                .substring(img_path.lastIndexOf(".") + 1)
                .toLowerCase();

            if (
                img_extension == "jpeg" ||
                img_extension == "jpg" ||
                img_extension == "png"
            ) {
                if (typeof FileReader != "undefined") {
                    img_holder.empty();
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("<img/>", {
                            src: e.target.result,
                            class: "img-fluid category_img",
                        }).appendTo(img_holder);
                    };
                    img_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    $(img_holder).html(
                        "Este navegador no soporta Lector de Archivos"
                    );
                }
            } else {
                $(img_holder).empty();
                banerImageRegister.val("");
                Toast.fire({
                    icon: "warning",
                    title: "¡Selecciona una imagen!",
                });
            }
        });

        var registerImagenSliderForm = $("#registerSliderImageForm").validate({
            rules: {
                content: {
                    required: false,
                    url: true,
                },
                image: {
                    required: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#RegisterSliderImagesModal");
                var img_holder = form.find(".img-holder");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = new FormData(form[0]);

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            var sliderImagesContainer = $(
                                "#sliderImages-list-container"
                            );
                            sliderImagesContainer.html(data.html);
                            banerImageRegister.val("");
                            registerImagenSliderForm.resetForm();

                            $(img_holder).empty();

                            modal.modal("toggle");

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                text: data.message,
                            });
                        }
                    },
                    complete: function (data) {
                        form.find(".btn-save").removeAttr("disabled");
                        loadSpinner.toggleClass("active");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            },
        });

        // * ----------------- EDIT SLIDER IMAGES ----------------- *

        var SI_ORDER_SELECT_CONFIG = {
            minimumResultsForSearch: -1
        }
        InitSelect2(".editOrderSliderLoginSelect", SI_ORDER_SELECT_CONFIG)

        $("#edit-sliderImage-status-checkbox").change(function () {
            var txtDesc = $("#txt-edit-status-sliderImage");
            if (this.checked) {
                txtDesc.html("Activo");
            } else {
                txtDesc.html("Inactivo");
            }
        });

        var editSliderImageForm = $("#editSliderImageForm").validate({
            rules: {
                content: {
                    required: false,
                    url: true,
                },
                image: {
                    required: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();
                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editSliderImageModal");
                var img_holder = form.find(".img-holder");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = new FormData(form[0]);

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            let sliderImagesBox = $(
                                "#sliderImages-list-container"
                            );

                            sliderImagesBox.html(data.html);
                            editSliderImageForm.resetForm();

                            modal.modal("hide");

                            $(img_holder).empty();

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                text: data.message,
                            });
                        }
                    },
                    complete: function (data) {
                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").removeAttr("disabled");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            },
        });

        var inputImageEdit = $("#input-sliderImage-image-edit");
        inputImageEdit.on("change", function () {
            $("#editSliderImageForm").validate();
            $("#input-sliderImage-image-edit").rules("add", { required: true });

            var img_path = $(this)[0].value;
            var img_holder = $(this)
                .closest("#editSliderImageForm")
                .find(".img-holder");
            var currentImagePath = $(this).data("value");
            var img_extension = img_path
                .substring(img_path.lastIndexOf(".") + 1)
                .toLowerCase();

            if (
                img_extension == "jpeg" ||
                img_extension == "jpg" ||
                img_extension == "png"
            ) {
                if (typeof FileReader != "undefined") {
                    img_holder.empty();
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("<img/>", {
                            src: e.target.result,
                            class: "img-fluid category_img",
                        }).appendTo(img_holder);
                    };
                    img_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    $(img_holder).html(
                        "Este navegador no soporta Lector de Archivos"
                    );
                }
            } else {
                $(img_holder).html(currentImagePath);
                inputImageEdit.val("");
                Toast.fire({
                    icon: "warning",
                    title: "¡Selecciona una imagen!",
                });
            }
        });

        $("html").on(
            "click",
            ".edit-sliderImage-btn",
            function () {
                var modal = $("#editSliderImageModal");
                var getDataUrl = $(this).data("send");
                var url = $(this).data("url");
                var form = modal.find("#editSliderImageForm");

                let orderSelect = form.find(".editOrderSliderLoginSelect");

                form.validate();
                $("#input-sliderImage-image-edit").rules("remove", "required");

                editSliderImageForm.resetForm();
                orderSelect.empty();
                form.trigger("reset");

                form.attr("action", url);

                $.ajax({
                    type: "GET",
                    url: getDataUrl,
                    dataType: "JSON",
                    success: function (data) {
                        let sliderImage = data.sliderImage;

                        modal
                            .find(".img-holder")
                            .html(
                                '<img class="img-fluid banner_img" id="image-banner-edit" src="' +
                                data.url_img +
                                '"></img>'
                            );
                        modal
                            .find("#input-sliderImage-image-edit")
                            .attr(
                                "data-value",
                                '<img scr="' +
                                data.url_img +
                                '" class="img-fluid banner_img"></img>'
                            );
                        modal.find("#input-sliderImage-image-edit").val("");

                        $.each(data.sliderImages, function (key, value) {
                            orderSelect.append(
                                '<option value"' +
                                value.order +
                                '">' +
                                value.order +
                                "</option>"
                            );
                        });

                        orderSelect.val(sliderImage.order).change();

                        // edit

                        form.find(".slider-url-content-container").html(
                            sliderImage.content
                        );

                        let raw_content = form.find(
                            ".slider-url-content-container > a"
                        );

                        form.find("input[name=content]").val(
                            raw_content.attr("href")
                        );

                        if (raw_content.attr("target") == "_BLANK") {
                            form.find(
                                "#checkbox-blank-indicator-slider-image-edit"
                            ).prop("checked", true);
                        } else {
                            form.find(
                                "#checkbox-blank-indicator-slider-image-edit"
                            ).prop("checked", false);
                        }

                        if (sliderImage.status == 1) {
                            modal
                                .find("#edit-sliderImage-status-checkbox")
                                .prop("checked", true);
                            $("#txt-edit-status-sliderImage").html("Activo");
                        } else {
                            modal
                                .find("#edit-sliderImage-status-checkbox")
                                .prop("checked", false);
                            $("#txt-edit-status-sliderImage").html("Inactivo");
                        }
                    },
                    complete: function (data) {
                        modal.modal("toggle");
                    },
                    error: function (data) {
                        console.log(data);
                    },
                });
            }
        );

        // * ----------------- DELETE SLIDER IMAGES -----------------

        $(".main-container-page").on(
            "click",
            ".delete-sliderImage-btn",
            function () {
                var url = $(this).data("url");

                SwalDelete.fire().then(
                    function (e) {
                        if (e.value === true) {
                            $.ajax({
                                type: "DELETE",
                                url: url,
                                dataType: "JSON",
                                success: function (result) {
                                    if (result.success === true) {
                                        var sliderImagesContainer = $(
                                            "#sliderImages-list-container"
                                        );
                                        sliderImagesContainer.html(result.html);

                                        Toast.fire({
                                            icon: "success",
                                            text: result.message,
                                        });
                                    } else {
                                        Toast.fire({
                                            icon: "error",
                                            text: result.message,
                                        });
                                    }
                                },
                                error: function (result) {
                                    ToastError.fire();
                                    console.log(result);
                                },
                            });
                        } else {
                            e.dismiss;
                        }
                    },
                    function (dismiss) {
                        return false;
                    }
                );
            }
        );
    }

    // * --------------- ANNOUNCEMENTS ----------------

    if ($("#banners-list-container").length) {
        // -------------- REGISTER ----------------

        $("#register-banner-status-checkbox").change(function () {
            var txtDesc = $("#txt-register-status-banner");
            if (this.checked) {
                txtDesc.html("Activo");
            } else {
                txtDesc.html("Inactivo");
            }
        });

        var banerImageRegister = $("#input-banner-image-register");
        banerImageRegister.val("");
        banerImageRegister.on("change", function () {
            var img_path = $(this)[0].value;
            var img_holder = $(this)
                .closest("#registerBannerForm")
                .find(".img-holder");
            var img_extension = img_path
                .substring(img_path.lastIndexOf(".") + 1)
                .toLowerCase();

            if (
                img_extension == "jpeg" ||
                img_extension == "jpg" ||
                img_extension == "png"
            ) {
                if (typeof FileReader != "undefined") {
                    img_holder.empty();
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("<img/>", {
                            src: e.target.result,
                            class: "img-fluid category_img",
                        }).appendTo(img_holder);
                    };
                    img_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    $(img_holder).html(
                        "Este navegador no soporta Lector de Archivos"
                    );
                }
            } else {
                $(img_holder).empty();
                banerImageRegister.val("");
                Toast.fire({
                    icon: "warning",
                    title: "¡Selecciona una imagen!",
                });
            }
        });

        var registerBannerForm = $("#registerBannerForm").validate({
            rules: {
                content: {
                    maxlength: 1000,
                    url: true,
                },
                image: {
                    required: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#registerBannerModal");
                var img_holder = form.find(".img-holder");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = new FormData(form[0]);

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            var bannersContainer = $("#banners-list-container");
                            bannersContainer.html(data.html);
                            banerImageRegister.val("");
                            registerBannerForm.resetForm();

                            $(img_holder).empty();

                            modal.modal("hide");

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                text: data.message,
                            });
                        }
                    },
                    complete: function (data) {
                        form.find(".btn-save").removeAttr("disabled");
                        loadSpinner.toggleClass("active");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            },
        });

        // ------------- EDITAR -----------

        var ODDER_SELECT_CLASSROOM_BANNER_CONFIG = {
            minimumResultsForSearch: -1,
        }

        InitSelect2('.editOrderClassroomBannerSelect', ODDER_SELECT_CLASSROOM_BANNER_CONFIG)

        $("#edit-banner-status-checkbox").change(function () {
            var txtDesc = $("#txt-edit-status-banner");
            if (this.checked) {
                txtDesc.html("Activo");
            } else {
                txtDesc.html("Inactivo");
            }
        });

        var editBannerForm = $("#editBannerForm").validate({
            rules: {
                content: {
                    maxlength: 5000,
                    url: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();
                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editBannerModal");
                var img_holder = form.find(".img-holder");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                var formData = new FormData(form[0]);

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            let bannersBox = $("#banners-list-container");

                            bannersBox.html(data.html);
                            editBannerForm.resetForm();

                            modal.modal("hide");

                            $(img_holder).empty();

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                text: data.message,
                            });
                        }
                    },
                    complete: function (data) {
                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").removeAttr("disabled");
                    },
                    error: function (data) {
                        console.log(data);
                        ToastError.fire();
                    },
                });
            },
        });

        var inputBannerEdit = $("#input-banner-image-edit");
        inputBannerEdit.on("change", function () {
            $("#editBannerForm").validate();
            $("#input-banner-image-edit").rules("add", { required: true });

            var img_path = $(this)[0].value;
            var img_holder = $(this)
                .closest("#editBannerForm")
                .find(".img-holder");
            var currentImagePath = $(this).data("value");
            var img_extension = img_path
                .substring(img_path.lastIndexOf(".") + 1)
                .toLowerCase();

            if (
                img_extension == "jpeg" ||
                img_extension == "jpg" ||
                img_extension == "png"
            ) {
                if (typeof FileReader != "undefined") {
                    img_holder.empty();
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $("<img/>", {
                            src: e.target.result,
                            class: "img-fluid category_img",
                        }).appendTo(img_holder);
                    };
                    img_holder.show();
                    reader.readAsDataURL($(this)[0].files[0]);
                } else {
                    $(img_holder).html(
                        "Este navegador no soporta Lector de Archivos"
                    );
                }
            } else {
                $(img_holder).html(currentImagePath);
                inputBannerEdit.val("");
                Toast.fire({
                    icon: "warning",
                    title: "¡Selecciona una imagen!",
                });
            }
        });

        $("html").on("click", ".edit-banner-btn", function () {
            var modal = $("#editBannerModal");
            var getDataUrl = $(this).data("send");
            var url = $(this).data("url");
            var form = modal.find("#editBannerForm");

            let orderSelect = form.find(".editOrderClassroomBannerSelect");

            form.validate();
            $("#input-banner-image-edit").rules("remove", "required");

            editBannerForm.resetForm();
            orderSelect.empty();
            form.trigger("reset");

            form.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let banner = data.banner;

                    form.find(".banner-url-content-container").html(
                        banner.content
                    );

                    let raw_content = form.find(
                        ".banner-url-content-container > a"
                    );

                    form.find("input[name=content]").val(
                        raw_content.attr("href")
                    );

                    modal
                        .find(".img-holder")
                        .html(
                            '<img class="img-fluid banner_img" id="image-banner-edit" src="' +
                            data.url_img +
                            '"></img>'
                        );
                    modal
                        .find("#input-banner-image-edit")
                        .attr(
                            "data-value",
                            '<img scr="' +
                            data.url_img +
                            '" class="img-fluid banner_img"></img>'
                        );
                    modal.find("#input-banner-image-edit").val("");

                    $.each(data.orders, function (key, value) {
                        orderSelect.append(
                            '<option value"' +
                            value.publishing_order +
                            '">' +
                            value.publishing_order +
                            "</option>"
                        );
                    });
                    orderSelect.val(banner.publishing_order).change();

                    if (raw_content.attr("target") == "_BLANK") {
                        form.find("#checkbox-blank-indicator-edit").prop(
                            "checked",
                            true
                        );
                    } else {
                        form.find("#checkbox-blank-indicator-edit").prop(
                            "checked",
                            false
                        );
                    }

                    if (banner.status == 1) {
                        modal
                            .find("#edit-banner-status-checkbox")
                            .prop("checked", true);
                        $("#txt-edit-status-banner").html("Activo");
                    } else {
                        modal
                            .find("#edit-banner-status-checkbox")
                            .prop("checked", false);
                        $("#txt-edit-status-banner").html("Inactivo");
                    }
                },
                complete: function (data) {
                    modal.modal("toggle");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });

        // -------- ELIMINAR --------------

        $(".main-content").on("click", ".delete-banner-btn", function () {
            var url = $(this).data("url");

            SwalDelete.fire().then(
                function (e) {
                    if (e.value === true) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (result) {
                                if (result.success === true) {
                                    var bannersContainer = $(
                                        "#banners-list-container"
                                    );
                                    bannersContainer.html(result.html);

                                    Toast.fire({
                                        icon: "success",
                                        text: result.message,
                                    });
                                } else {
                                    Toast.fire({
                                        icon: "error",
                                        text: result.message,
                                    });
                                }
                            },
                            error: function (result) {
                                ToastError.fire();
                                console.log(result);
                            },
                        });
                    } else {
                        e.dismiss;
                    }
                },
                function (dismiss) {
                    return false;
                }
            );
        });
    }

});
