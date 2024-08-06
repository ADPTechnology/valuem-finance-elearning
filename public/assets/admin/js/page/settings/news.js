import {
    SwalDelete,
    Toast,
    ToastError,
} from "../../../../common/js/sweet-alerts.js";

$(function () {

    // --------------- ANNOUNCEMENTS ----------------

    if ($("#news-list-container").length) {
        // -------------- REGISTER ----------------

        $("#register-news-status-checkbox").change(function () {
            var txtDesc = $("#txt-register-status-news");
            if (this.checked) {
                txtDesc.html("Activo");
            } else {
                txtDesc.html("Inactivo");
            }
        });

        var newImageRegister = $("#input-news-image-register");
        newImageRegister.val("");

        newImageRegister.on("change", function () {
            var img_path = $(this)[0].value;
            var img_holder = $(this)
                .closest("#registerNewForm")
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
                newImageRegister.val("");
                Toast.fire({
                    icon: "warning",
                    title: "¡Selecciona una imagen!",
                });
            }
        });

        var registerNewForm = $("#registerNewForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
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
                var modal = $("#registerNewModal");
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
                            var newsContainer = $("#news-list-container");
                            newsContainer.html(data.html);
                            newImageRegister.val("");
                            
                            registerNewForm.resetForm();
                            form.trigger("reset");

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

        // ------------- EDITAR -----------

        $("#editOrderSelect").select2({
            dropdownParent: "#editNewForm",
            minimumResultsForSearch: -1,
        });

        $("#edit-new-status-checkbox").change(function () {
            var txtDesc = $("#txt-edit-status-new");
            if (this.checked) {
                txtDesc.html("Activo");
            } else {
                txtDesc.html("Inactivo");
            }
        });

        var editNewForm = $("#editNewForm").validate({
            rules: {
                title: {
                    required: true,
                    maxlength: 100,
                },
                content: {
                    maxlength: 5000,
                    url: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();
                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#editNewModal");
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
                            let newsBox = $("#news-list-container");

                            newsBox.html(data.html);
                            editNewForm.resetForm();

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

        var inputNewEdit = $("#input-new-image-edit");
        inputNewEdit.on("change", function () {
            $("#editNewForm").validate();
            $("#input-new-image-edit").rules("add", {
                required: true,
            });

            var img_path = $(this)[0].value;
            var img_holder = $(this)
                .closest("#editNewForm")
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
                inputNewEdit.val("");
                Toast.fire({
                    icon: "warning",
                    title: "¡Selecciona una imagen!",
                });
            }
        });

        $(".main-content").on("click", ".edit-new-btn", function () {
            var modal = $("#editNewModal");
            var getDataUrl = $(this).data("send");
            var url = $(this).data("url");
            var form = modal.find("#editNewForm");

            let orderSelect = form.find("#editOrderSelect");

            form.validate();
            $("#input-new-image-edit").rules("remove", "required");

            editNewForm.resetForm();
            orderSelect.empty();
            form.trigger("reset");

            form.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    let newPubli = data.new;

                    form.find(".new-url-content-container").html(
                        newPubli.content
                    );

                    let raw_content = form.find(
                        ".new-url-content-container > a"
                    );

                    form.find("input[name=title]").val(newPubli.title);

                    form.find("input[name=content]").val(
                        raw_content.attr("href")
                    );

                    modal
                        .find(".img-holder")
                        .html(
                            '<img class="img-fluid new_img" id="image-new-edit" src="' +
                                data.url_img +
                                '"></img>'
                        );
                    modal
                        .find("#input-new-image-edit")
                        .attr(
                            "data-value",
                            '<img scr="' +
                                data.url_img +
                                '" class="img-fluid new_img"></img>'
                        );
                    modal.find("#input-new-image-edit").val("");

                    $.each(data.orders, function (key, value) {
                        orderSelect.append(
                            '<option value"' +
                                value.publishing_order +
                                '">' +
                                value.publishing_order +
                                "</option>"
                        );
                    });

                    orderSelect.val(newPubli.publishing_order).change();

                    if (raw_content.attr("target") == "_BLANK") {
                        form.find("#checkbox-blank-indicator-news-edit").prop(
                            "checked",
                            true
                        );
                    } else {
                        form.find("#checkbox-blank-indicator-news-edit").prop(
                            "checked",
                            false
                        );
                    }

                    if (newPubli.status == 1) {
                        modal
                            .find("#edit-new-status-checkbox")
                            .prop("checked", true);
                        $("#txt-edit-status-new").html("Activo");
                    } else {
                        modal
                            .find("#edit-new-status-checkbox")
                            .prop("checked", false);
                        $("#txt-edit-status-new").html("Inactivo");
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

        $(".main-content").on("click", ".delete-new-btn", function () {
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
                                    var newsContainer = $(
                                        "#news-list-container"
                                    );
                                    newsContainer.html(result.html);

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
