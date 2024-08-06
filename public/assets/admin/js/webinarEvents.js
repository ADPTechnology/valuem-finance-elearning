import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    setActiveSubmitButton,
    InitAjaxSelect2
} from "../../common/js/utils.js";

$(function () {

    const WEBINAR_EVENT_RULES = {
        description: {
            required: true,
            maxlength: 255,
        },
        date: {
            required: true,
        },
        time_start: {
            required: true,
        },
        time_end: {
            required: true,
        },
        user_id: {
            required: true,
        },
        responsable_id: {
            required: true,
        },
        room_id: {
            required: true
        }
    };

    const IMAGE_FILEPOND_CONFIG = {
        'allowMultiple': false,
        'name': 'image',
        'dropValidation': true,
        'storeAsFile': true,
        'allowReplace': true,
        'labelIdle': '<i class="fa-solid fa-images me-2"></i> \
                        Arrastra y suelta una imagen o \
                        <span class="filepond--label-action"> Explora </span>',
        'checkValidity': true,
        'acceptedFileTypes': ['image/*'],
        'labelFileTypeNotAllowed': 'Este tipo de archivo no es válido',
        'fileValidateTypeLabelExpectedTypes': 'Se espera {lastType}',
        'credits': false
    }

    $('.webinar-event-image-input').filepond(IMAGE_FILEPOND_CONFIG);

    const SELECT2_ROOM_CONFIG = {
        placeholder: "Selecciona una sala",
    }

    const SELECT2_INSTRUCTOR_CONFIG = {
        placeholder: "Selecciona un instructor",
    }

    const SELECT2_RESPONSABLE_CONFIG = {
        placeholder: "Selecciona un responsable",
    }

    InitAjaxSelect2('.RoomSelect', SELECT2_ROOM_CONFIG, 'description', {column: 'room'})
    InitAjaxSelect2('.InstructorSelect', SELECT2_INSTRUCTOR_CONFIG, 'full_name', {column: 'instructor'})
    InitAjaxSelect2('.ResponsableSelect', SELECT2_RESPONSABLE_CONFIG, 'full_name', {column: 'responsable'})

    setActiveCheckbox(
        ".webinar-event-status-checkbox",
        ".txt-description-status-webinar-event"
    );

    var webinarEventsTable;

    if ($('#webinar-events-table').length) {

        var webinarEventsTableEle = $("#webinar-events-table");
        var getDataUrl = webinarEventsTableEle.data("url");
        webinarEventsTable = webinarEventsTableEle.DataTable({
            responsive: true,
            language: DataTableEs,
            serverSide: true,
            processing: true,
            ajax: getDataUrl,
            columns: [
                { data: "id", name: "id" },
                { data: "description", name: "description" },
                { data: "date", name: "date" },
                { data: "time_start", name: "time_start" },
                { data: "time_end", name: "time_end" },
                { data: "webinar.title", name: "webinar.title" },
                { data: "instructor.name", name: "instructor.name" },
                { data: "active", name: "active" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                },
            ],
            order: [
                [0, 'desc']
            ]
        });

        // ---------- STORE -----------

        var formRegister = $("#registerWebinarEventForm");
        var registerSubmitButton = formRegister.find("button[type=submit]");
        setActiveSubmitButton(registerSubmitButton);

        var modalRegister = $("#RegisterWebinarEventModal");

        var registerWebinarEventForm = $("#registerWebinarEventForm").validate({
            rules: WEBINAR_EVENT_RULES,
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var button = form.find("button[type=submit][clicked=true]");
                var loadSpinner = button.find(".loadSpinner");

                loadSpinner.toggleClass("active");
                form.find(".btn-save").attr("disabled", "disabled");

                $.ajax({
                    method: form.attr("method"),
                    url: form.attr("action"),
                    data: new FormData(form[0]),
                    processData: false,
                    contentType: false,
                    dataType: "JSON",
                    success: function (data) {

                        if (data.success) {

                            let imageInput = form.find('.webinar-event-image-input')
                            var dateInput = form.find("input[name=date]");

                            if (data.show) {
                                window.location.href = data.route;
                            } else {
                                if ($("#webinar-events-table").length) {
                                    if (data.html) {
                                        var webinar_box = $('#webinar-box-container')
                                        webinar_box.html(data.html)
                                    }
                                    webinarEventsTable.draw();
                                }
                            }

                            registerWebinarEventForm.resetForm();
                            form.trigger("reset");

                            $.each($(".timepicker"), function (key, value) {
                                $(this).timepicker("setTime", new Date());
                            });
                            dateInput.val(moment().format("YYYY-MM-DD"));

                            imageInput.filepond('removeFiles')

                            form.find('.select2').each(function () {
                                $(this).val(null).change()
                            })

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
                        modalRegister.modal("hide");
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

    // --------- EDIT ------------

    if ($('#editWebinarEventModal').length) {

        var formEdit = $('#editWebinarEventForm')
        var modalEdit = $('#editWebinarEventModal')

        var editWebinarEventForm = $('#editWebinarEventForm').validate({
            rules: WEBINAR_EVENT_RULES,
            submitHandler: function (form, event) {
                event.preventDefault();

                var form = $(form);
                var loadSpinner = form.find(".loadSpinner");

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

                            if (data.show) {
                                var webinarEventContainer = $("#webinar-event-box-container");
                                var titleContainer = $("#webinar-event-description-text-principal");
                                webinarEventContainer.html(data.html);
                                titleContainer.html(data.title);
                            } else {
                                webinarEventsTable.ajax.reload(null, false);
                            }

                            editWebinarEventForm.resetForm()
                            form.trigger('reset')

                            Toast.fire({
                                icon: "success",
                                text: data.message,
                            });

                            modalEdit.modal("hide");

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
                        ToastError.fire();
                    },
                });
            }
        })

        $('html').on('click', '.editWebinarEvent', function () {

            var button = $(this)
            var url = button.data('url')
            var getDataUrl = button.data('send')

            formEdit.attr('action', url)
            var imageInput = formEdit.find('.webinar-event-image-input')
            imageInput.filepond('removeFiles')

            $.ajax({
                url: getDataUrl,
                type: 'GET',
                dataType: 'JSON',
                success: function (data) {

                    let webinarEvent = data.webinarEvent
                    let image = data.image

                    if (image) {
                        imageInput.filepond('addFile', decodeURI(image))
                    }

                    $.each(webinarEvent, function (key, value) {
                        let input = formEdit.find('[name=' + key + ']')
                        if (input) { input.val(value) }
                    })

                    formEdit.find('.RoomSelect').select2("trigger", "select", {
                        data: { id: webinarEvent.room_id, text: webinarEvent.room.description}
                    });

                    formEdit.find('.InstructorSelect').select2("trigger", "select", {
                        data: { id: webinarEvent.user_id, text: webinarEvent.instructor.full_name }
                    });

                    if (webinarEvent.responsable) {
                        formEdit.find('.ResponsableSelect').select2("trigger", "select", {
                            data: { id: webinarEvent.responsable_id, text: webinarEvent.responsable.full_name }
                        });
                    }

                    if (webinarEvent.active == 'S') {
                        formEdit.find('.webinar-event-status-checkbox').prop('checked', true);
                        formEdit.find('.txt-description-status-webinar-event').html('Activo');
                    } else {
                        formEdit.find('.webinar-event-status-checkbox').prop('checked', false);
                        formEdit.find('.txt-description-status-webinar-event').html('Inactivo');
                    }

                    modalEdit.modal('show')
                },
                error: function (data) {
                    console.log(data)
                    ToastError.fire()
                }
            })
        })
    }

    // -------- DELETE ------------

    $("html").on("click", ".deleteWebinarEvent", function () {
        var button = $(this);
        var url = button.data("url");
        var place = button.data("place");

        SwalDelete.fire().then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        type: "DELETE",
                        url: url,
                        data: {
                            place: place,
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {
                                if (data.show) {
                                    window.location.href = data.route;
                                } else {
                                    if ($("#webinar-events-table").length) {
                                        if (data.html) {
                                            var webinar_box = $('#webinar-box-container')
                                            webinar_box.html(data.html)
                                        }
                                        webinarEventsTable.ajax.reload(
                                            null,
                                            false
                                        );
                                    }
                                }

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
                        error: function (data) {
                            ToastError.fire();
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

});
