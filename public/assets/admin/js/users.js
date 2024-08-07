import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    initImageChange,
    setActiveSubmitButton,
} from "../../common/js/utils.js";


$(() => {

    if ($("#users-table").length) {
        /* -------- SELECT ----------*/

        $("#registerRoleSelect").select2({
            dropdownParent: $("#RegisterUserModal"),
            placeholder: "Selecciona un rol",
        });

        $("#editRoleSelect").select2({
            dropdownParent: $("#EditUserModal"),
            placeholder: "Selecciona un rol",
        });

        /* --------------------------------*/

        var usersTableEle = $("#users-table");
        var getDataTable = usersTableEle.data("url");
        var usersTable = usersTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: {
                url: getDataTable,
                data: function (data) {
                    // data.from_date = $("#daterange-btn-certifications")
                    //     .data("daterangepicker")
                    //     .startDate.format("YYYY-MM-DD");
                    // data.end_date = $("#daterange-btn-certifications")
                    //     .data("daterangepicker")
                    //     .endDate.format("YYYY-MM-DD");
                    data.rol = $("#search_from_rol_select").val();
                    data.active = $("#search_from_status_select").val();
                    data.type = $("#search_from_type_select").val();
                },
            },
            columns: [
                { data: "id", name: "id" },
                { data: "name", name: "name" },
                { data: "email", name: "email" },
                { data: "role", name: "role" },
                {
                    data: "status-btn",
                    name: "status-btn",
                    orderable: false,
                    searchable: false,
                },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
            order: [[0, "desc"]],
        });

        $("html").on("change", ".select-filter-users", function () {
            usersTable.draw();
        });

        $("#register-user-status-checkbox").change(function () {
            var txtDesc = $("#txt-register-description-user");
            if (this.checked) {
                txtDesc.html("Activo");
            } else {
                txtDesc.html("Inactivo");
            }
        });

        /* ------------ REGISTRAR -------------*/

        var inputUserImageStore = $("#input-user-image-store");
        inputUserImageStore.on("change", function () {
            if ($(this).val()) {
                // $('#registerUserForm').validate()
                // $('#image-upload-category-edit').rules('add', { required: true })
                var img_path = $(this)[0].value;
                var img_holder = $(this)
                    .closest("#registerUserForm")
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
                                class: "img-fluid avatar_img",
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
                    inputUserImageStore.val("");
                    Toast.fire({
                        icon: "warning",
                        title: "¡Selecciona una imagen!",
                    });
                }
            }
        });

        var registerUserForm = $("#registerUserForm").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255,
                },
                paternal: {
                    required: true,
                    maxlength: 255,
                },
                maternal: {
                    required: true,
                    maxlength: 255,
                },
                email: {
                    required: true,
                    maxlength: 255,
                    email: true,
                    remote: {
                        url: $("#registerUserForm").data("validate"),
                        method: $("#registerUserForm").attr("method"),
                        dataType: "JSON",
                        data: {
                            email: function () {
                                return $("#registerUserForm")
                                    .find("input[name=email]")
                                    .val();
                            },
                        },
                    },
                },
                password: {
                    required: true,
                },
                telephone: {
                    maxlength: 20,
                },
                role: {
                    required: true,
                },
            },
            messages: {
                email: {
                    remote: "Este usuario ya está registrado",
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();
                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#RegisterUserModal");

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
                            registerUserForm.resetForm();
                            usersTable.ajax.reload(null, false);

                            form.trigger("reset");
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

        $("#btn-register-user-modal").on("click", function () {
            var modal = $("#RegisterUserModal");
            var button = $(this);

            registerUserForm.resetForm();

            modal.modal("show");

            // $.ajax({
            //     type: "GET",
            //     url: url,
            //     dataType: "JSON",
            //     success: function (data) {
            //         // var companies = data["companies"];
            //         // select.append("<option></option>");

            //         // $.each(companies, function (key, values) {
            //         //     select.append(
            //         //         '<option value="' +
            //         //             values.id +
            //         //             '">' +
            //         //             values.description +
            //         //             "</option>"
            //         //     );
            //         // });
            //     },
            //     complete: function (data) {
            //         modal.modal("show");
            //     },
            //     error: function (data) {
            //         console.log(data);
            //     },
            // });

            // modal.modal('toggle')
        });

        /* ---------------- EDITAR  ----------------- */

        $("#edit-user-status-checkbox").change(function () {
            var txtDesc = $("#txt-edit-description-user");
            if (this.checked) {
                txtDesc.html("Activo");
            } else {
                txtDesc.html("Inactivo");
            }
        });

        var editUserForm = $("#editUserForm").validate({
            rules: {
                name: {
                    required: true,
                    maxlength: 255,
                },
                paternal: {
                    required: true,
                    maxlength: 255,
                },
                maternal: {
                    required: true,
                    maxlength: 255,
                },
                email: {
                    required: true,
                    maxlength: 255,
                    email: true,
                    remote: {
                        url: $("#editUserForm").data("validate"),
                        method: $("#editUserForm").attr("method"),
                        dataType: "JSON",
                        data: {
                            email: function () {
                                return $("#editUserForm")
                                    .find("input[name=email]")
                                    .val();
                            },
                            id: function () {
                                return $("#editUserForm")
                                    .find("input[name=id]")
                                    .val();
                            },
                        },
                    },
                },
                telephone: {
                    maxlength: 20,
                },
                role: {
                    required: true,
                },
            },
            messages: {
                email: {
                    remote: "Este usuario ya está registrado",
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();
                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#EditUserModal");

                var formData = new FormData(form[0]);

                loadSpinner.toggleClass("active");

                form.find(".btn-save").attr("disabled", "disabled");

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {
                        if (data.success) {
                            usersTable.ajax.reload(null, false);
                            form.trigger("reset");
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
                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").removeAttr("disabled");
                    },
                    error: function (data) {
                        console.log(data);
                    },
                });
            },
        });

        var inputUserImageEdit = $("#input-user-image-edit");
        inputUserImageEdit.on("change", function () {
            if ($(this).val()) {
                var img_path = $(this)[0].value;
                var img_holder = $(this)
                    .closest("#editUserForm")
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
                                class: "img-fluid avatar_img",
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
                    inputUserImageEdit.val("");
                    Toast.fire({
                        icon: "warning",
                        title: "¡Selecciona una imagen!",
                    });
                }
            }
        });

        $("html").on("click", ".editUser", function () {
            var modal = $("#EditUserModal");
            var getDataUrl = $(this).data("send");
            var url = $(this).data("url");
            var form = modal.find("#editUserForm");
            var selectRole = modal.find("#editRoleSelect");

            editUserForm.resetForm();
            form.trigger("reset");

            form.attr("action", url);

            $.ajax({
                type: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {
                    var user = data.user;

                    $.each(user, function (key, value) {
                        let input = modal.find("input[name="+ key +"]")
                        if (input) { input.val(value) }
                    })

                    selectRole.val(user.role).change();

                    modal
                        .find(".img-holder")
                        .html(
                            '<img class="img-fluid avatar_img" id="image-avatar-edit" src="' +
                                data.url_img +
                                '"></img>'
                        );
                    modal
                        .find("#input-user-image-edit")
                        .attr(
                            "data-value",
                            '<img scr="' +
                                data.url_img +
                                '" class="img-fluid avatar_img"></img>'
                        );
                    modal.find("#input-user-image-edit").val("");

                    if (user.active == "S") {
                        modal
                            .find("#edit-user-status-checkbox")
                            .prop("checked", true);
                        $("#txt-edit-description-user").html("Activo");
                    } else {
                        modal
                            .find("#edit-user-status-checkbox")
                            .prop("checked", false);
                        $("#txt-edit-description-user").html("Inactivo");
                    }

                    if (data.isAuth) {
                        selectRole.attr("readonly", true);
                    } else {
                        selectRole.removeAttr("readonly");
                    }
                },
                complete: function (data) {
                    modal.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        });

        /* ----------- ELIMINAR ---------------*/

        $("html").on("click", ".deleteUser", function () {
            var url = $(this).data("url");

            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Esta acción no podrá ser revertida!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "¡Sí!",
                cancelButtonText: "Cancelar",
                cancelButtonColor: "#161616",
                confirmButtonColor: "#de1a2b",
                showLoaderOnConfirm: true,
                reverseButtons: true,
                preConfirm: async () => {
                    return new Promise(function (resolve, reject) {
                        $.ajax({
                            type: "DELETE",
                            url: url,
                            dataType: "JSON",
                            success: function (result) {
                                if (result.success === true) {
                                    usersTable.ajax.reload(null, false);
                                }
                            },
                            error: function (result) {
                                // Swal.showValidationMessage(`
                                //     Request failed: ${result}
                                //   `);
                                ToastError.fire();
                            },
                        });
                        setTimeout(function() {
                          resolve();
                        }, 500);
                    })
                },
                allowOutsideClick: () => !Swal.isLoading()
            }).then((result) => {
                console.log(result)
                if (result.isConfirmed) {
                    Toast.fire({
                        icon: "success",
                        text: "¡Registro eliminado!",
                    });
                }
            });
        });

        // ----------- REGISTRO MASIVO -----------

        var registerUserMassiveForm = $("#registerUserMassiveForm").validate({
            rules: {
                file: {
                    required: true,
                },
            },
            submitHandler: function (form, event) {
                event.preventDefault();
                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");
                var modal = $("#RegisterUserMassiveModal");

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
                            usersTable.ajax.reload(null, false);

                            registerUserMassiveForm.resetForm();
                            form.trigger("reset");
                            modal.modal("hide");

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            }).then((result) => {
                                if (
                                    result.dismiss === Swal.DismissReason.timer
                                ) {
                                    if (data.foundDuplicates) {
                                        var notebody = $("<ul/>");
                                        $.each(
                                            data.notebody,
                                            function (key, values) {
                                                var sub_li =
                                                    $("<li/>").html(values);
                                                notebody.append(sub_li);
                                            }
                                        );

                                        Swal.fire({
                                            toast: true,
                                            position: "top-end",
                                            showConfirmButton: true,
                                            timerProgressBar: false,
                                            icon: "warning",
                                            title: data.note,
                                            html: notebody[0].outerHTML,
                                        });
                                    }
                                }
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
    }

})
