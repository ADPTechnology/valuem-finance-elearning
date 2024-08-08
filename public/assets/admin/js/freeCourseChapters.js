import DataTableEs from "../../common/js/datatable_es.js";
import { Toast, ToastError, SwalDelete } from "../../common/js/sweet-alerts.js";
import {
    setActiveCheckbox,
    initImageChange,
    setActiveSubmitButton,
} from "../../common/js/utils.js";


$(() => {

    /* ---------- CHAPTERS -----------*/

    /* ------- CHAPTERS TABLE ---------*/

    function chapterTable(ele, lang, url) {

        var chaptersTable = ele.DataTable({
            responsive: true,
            language: lang,
            serverSide: true,
            processing: true,
            ajax: {
                url: url,
                data: {
                    type: "table",
                },
            },
            order: [[3, "asc"]],
            columns: [
                { data: "title", name: "title", className: "text-bold" },
                { data: "description", name: "description" },
                { data: "duration", name: "duration" },
                { data: "chapter_order", name: "chapter_order" },
                {
                    data: "view",
                    name: "view",
                    orderable: false,
                    searchable: false,
                    className: "text-center",
                },
                { data: "content", name: "content", orderable: false, searchable: false, className: "action-with text-center" },
                {
                    data: "action",
                    name: "action",
                    orderable: false,
                    searchable: false,
                    className: "action-with",
                },
            ],
            dom: "rtip",
        });
    }

    /* ----- SET ACTIVE -----*/

    $("html").on(
        "click",
        ".course-section-box .title-container",
        function () {
            var sectionBox = $(this).closest(".course-section-box");

            if (!sectionBox.hasClass("active")) {
                sectionBox.addClass("active").attr("data-active", "active");
                sectionBox
                    .siblings()
                    .removeClass("active")
                    .attr("data-active", "");

                var url = sectionBox.data("table");

                $.ajax({
                    type: "GET",
                    url: url,
                    data: {
                        type: "html",
                    },
                    dataType: "JSON",
                    success: function (data) {
                        var chaptersBox = $("#chapters-list-container");
                        var topTableInfo = $(
                            "#top-chapter-table-title-info"
                        );

                        topTableInfo.html(
                            '<span class="text-bold"> de: </span> \
                                    <span class="title-chapter-top-table">' +
                            data.title +
                            "</span>"
                        );
                        chaptersBox.html(data.html);

                        var chaptersTableEle = $(
                            "#freeCourses-chapters-table"
                        );
                        chapterTable(chaptersTableEle, DataTableEs, url);
                    },
                    error: function (result) {
                        // console.log(result);
                        Toast.fire({
                            icon: "error",
                            title: "¡Ocurrió un error inesperado!",
                        });
                    },
                });
            }
        }
    );

    /*-------  REGISTER  ------*/

    var registerChapterForm = $("#registerChapterForm").validate({
        rules: {
            title: {
                required: true,
                maxlength: 100,
            },
            description: {
                required: true,
                maxlength: 500,
            },
        },
    });

    /*----- STORE DATA -------*/

    $("html").on(
        "click",
        "#btn-register-chapter-modal",
        function () {
            var button = $(this);
            var url = button.data("url");
            var modal = $("#registerChapterModal");
            var form = $("#registerChapterForm");
            var loadSpinner = form.find(".loadSpinner");

            if (
                !$("#input-chapter-video-container").hasClass(
                    "dz-clickable"
                )
            ) {
                var chapterVideoInput = $(
                    "#input-chapter-video-container"
                ).dropzone({
                    url: url,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    paramName: "file",
                    addRemoveLinks: true,
                    uploadMultiple: false,
                    autoProcessQueue: false,
                    maxFiles: 1,
                    hiddenInputContainer: "#input-chapter-video-container",
                    maxfilesexceeded: function (file) {
                        this.removeAllFiles();
                        this.addFile(file);
                    },
                    accept: function (file, done) {
                        // $("#registerChapterForm")
                        //     .find(".message-file-invalid")
                        //     .removeClass("show");
                        if (!file.type.match("video/*")) {
                            Toast.fire({
                                icon: "warning",
                                text: "¡Solo puedes subir videos!",
                            });
                            this.removeFile(file);
                            return false;
                        }
                        if (file.size > 150 * 1024 * 1024) {
                            Toast.fire({
                                icon: "warning",
                                text: "¡Tu archivo pesa más de 50MB!",
                            });
                            this.removeFile(file);
                            return false;
                        }
                        return done();
                    },
                    init: function () {
                        var myDropzone = this;

                        myDropzone.on("processing", function (file) {
                            this.options.url = $(
                                "#btn-register-chapter-modal"
                            ).data("url");
                        });

                        $("#registerChapterForm").on(
                            "submit",
                            function (e) {
                                e.preventDefault();
                                e.stopPropagation();
                                // var messageInvalid = $(this).find(
                                //     ".message-file-invalid"
                                // );

                                if ($("#registerChapterForm").valid()) {
                                    if (
                                        myDropzone.getQueuedFiles().length >= 1
                                    ) {
                                        // messageInvalid.removeClass("show");
                                        // if ($("#registerChapterForm").valid()) {
                                        myDropzone.processQueue();
                                        // }
                                    } else {

                                        myDropzone.removeAllFiles();

                                        loadSpinner.toggleClass("active");

                                        form.find(".btn-save").attr(
                                            "disabled",
                                            "disabled"
                                        );
                                        let sectionActive = $("#sections-list-container")
                                            .find(".course-section-box.active")
                                            .data("id");

                                        let formData = form.serializeArray();
                                        formData.push({ name: "sectionActive", value: sectionActive })

                                        $.ajax({
                                            method: form.attr("method"),
                                            url: url,
                                            data: formData,
                                            dataType: "JSON",
                                            success: function (data) {
                                                if (data.success) {

                                                    let urlTable = $(
                                                        "#section-box-" + data.id
                                                    ).data("table");

                                                    registerChapterForm.resetForm();
                                                    form.trigger("reset");

                                                    var chaptersBox = $("#chapters-list-container");
                                                    var sectionsBox = $("#sections-list-container");
                                                    var courseBox = $("#course-box-container");

                                                    chaptersBox.html(data.htmlChapter);
                                                    sectionsBox.html(data.htmlSection);
                                                    courseBox.html(data.htmlCourse);

                                                    $(".order-section-select").select2({
                                                        minimumResultsForSearch: -1,
                                                    });

                                                    var chaptersTableEle = $(
                                                        "#freeCourses-chapters-table"
                                                    );
                                                    chapterTable(
                                                        chaptersTableEle,
                                                        DataTableEs,
                                                        urlTable
                                                    );

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

                                                modal.modal("hide");
                                            },
                                            complete: function (data) {
                                                loadSpinner.toggleClass("active");
                                                form.find(".btn-save").removeAttr(
                                                    "disabled"
                                                );
                                            },
                                            error: function (data) {
                                                console.log(data);
                                            },
                                        });

                                        // messageInvalid.addClass("show");
                                    }
                                }


                            }
                        );
                    },
                    sending: function (file, xhr, formData) {
                        let title = form.find("input[name=title]").val();
                        let description = form
                            .find("#description-text-area-register")
                            .val();

                        let sectionActive = $("#sections-list-container")
                            .find(".course-section-box.active")
                            .data("id");

                        formData.append("title", title);
                        formData.append("description", description);
                        formData.append("sectionActive", sectionActive);

                        loadSpinner.toggleClass("active");
                        form.find(".btn-save").attr("disabled", "disabled");
                    },
                    success: function (file, response) {

                        if (response.success) {
                            let urlTable = $(
                                "#section-box-" + response.id
                            ).data("table");

                            this.removeAllFiles();
                            registerChapterForm.resetForm();
                            form.trigger("reset");

                            var chaptersBox = $("#chapters-list-container");
                            var sectionsBox = $("#sections-list-container");
                            var courseBox = $("#course-box-container");

                            chaptersBox.html(response.htmlChapter);
                            sectionsBox.html(response.htmlSection);
                            courseBox.html(response.htmlCourse);

                            $(".order-section-select").select2({
                                minimumResultsForSearch: -1,
                            });

                            var chaptersTableEle = $(
                                "#freeCourses-chapters-table"
                            );
                            chapterTable(
                                chaptersTableEle,
                                DataTableEs,
                                urlTable
                            );

                            Toast.fire({
                                icon: "success",
                                text: response.message,
                            });
                        } else {
                            Toast.fire({
                                icon: "error",
                                text: response.message,
                            });
                        }

                        modal.modal("hide");
                        loadSpinner.toggleClass("active");
                    },
                    complete: function () {
                        form.find(".btn-save").removeAttr("disabled");
                    },
                    error: function (file, response) {
                        console.log(response);
                    },
                });
            }
        }
    );

    /*--------- EDIT ............*/

    $("#editOrderSelectChapter").select2({
        dropdownParent: $("#editChapterModal"),
        minimumResultsForSearch: -1,
    });

    var editChapterForm = $("#editChapterForm").validate({
        rules: {
            title: {
                required: true,
                maxlength: 100,
            },
            description: {
                required: true,
                maxlength: 500,
            },
        },
    });

    $("html").on("click", ".editChapter", function () {
        var button = $(this);
        var modal = $("#editChapterModal");
        var getDataUrl = button.data("send");
        var url = button.data("url");
        var form = $("#editChapterForm");
        var loadSpinner = form.find(".loadSpinner");

        $("#editOrderSelectChapter").html("");

        button
            .closest("tr")
            .siblings()
            .find(".editChapter")
            .removeClass("active");
        button.addClass("active");

        if (
            !$("#input-chapter-video-container-edit").hasClass(
                "dz-clickable"
            )
        ) {
            let chapterVideoInputEdit = $(
                "#input-chapter-video-container-edit"
            ).dropzone({
                url: url,
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
                paramName: "file",
                addRemoveLinks: true,
                uploadMultiple: false,
                autoProcessQueue: false,
                maxFiles: 1,
                hiddenInputContainer: "#input-chapter-video-container-edit",
                maxfilesexceeded: function (file) {
                    this.removeAllFiles();
                    this.addFile(file);
                },
                accept: function (file, done) {
                    if (!file.type.match("video/*")) {
                        Toast.fire({
                            icon: "warning",
                            text: "¡Solo puedes subir videos!",
                        });
                        this.removeFile(file);
                        return false;
                    }
                    if (file.size > 150 * 1024 * 1024) {
                        Toast.fire({
                            icon: "warning",
                            text: "¡Tu archivo pesa más de 50MB!",
                        });
                        this.removeFile(file);
                        return false;
                    }
                    return done();
                },
                init: function () {
                    var myDropzone = this;

                    $("#editChapterForm").on("submit", function (e) {
                        e.preventDefault();
                        e.stopPropagation();

                        let urlChanged = $(".editChapter.active").data(
                            "url"
                        );

                        myDropzone.options.url = urlChanged;

                        if ($("#editChapterForm").valid()) {
                            if (myDropzone.getQueuedFiles().length == 1) {
                                myDropzone.processQueue();
                            } else {
                                myDropzone.removeAllFiles();

                                loadSpinner.toggleClass("active");
                                form.find(".btn-save").attr(
                                    "disabled",
                                    "disabled"
                                );

                                $.ajax({
                                    method: form.attr("method"),
                                    url: urlChanged,
                                    data: form.serialize(),
                                    dataType: "JSON",
                                    success: function (data) {
                                        if (data.success) {
                                            let urlTable = $(
                                                "#section-box-" + data.id
                                            ).data("table");
                                            let chaptersBox = $(
                                                "#chapters-list-container"
                                            );

                                            chaptersBox.html(
                                                data.htmlChapter
                                            );

                                            let chaptersTableEle = $(
                                                "#freeCourses-chapters-table"
                                            );

                                            chapterTable(
                                                chaptersTableEle,
                                                DataTableEs,
                                                urlTable
                                            );

                                            editChapterForm.resetForm();
                                            form.trigger("reset");

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
                                        $("#editChapterModal").modal(
                                            "hide"
                                        );
                                        form.find(".btn-save").removeAttr(
                                            "disabled"
                                        );
                                    },
                                    error: function (data) {
                                        console.log(data);
                                    },
                                });
                            }
                        }
                    });
                },
                sending: function (file, xhr, formData) {
                    let title = form.find("input[name=title]").val();
                    let description = form
                        .find("#description-text-area-edit")
                        .val();
                    let order = form.find("#editOrderSelectChapter").val();

                    formData.append("title", title);
                    formData.append("description", description);
                    formData.append("chapter_order", order);

                    loadSpinner.toggleClass("active");
                    form.find(".btn-save").attr("disabled", "disabled");
                },
                success: function (file, response) {
                    this.removeAllFiles();

                    if (response.success) {
                        let urlTable = $(
                            "#section-box-" + response.id
                        ).data("table");
                        let chaptersBox = $("#chapters-list-container");
                        let courseBox = $("#course-box-container");

                        courseBox.html(response.htmlCourse);
                        chaptersBox.html(response.htmlChapter);

                        var chaptersTableEle = $(
                            "#freeCourses-chapters-table"
                        );

                        chapterTable(
                            chaptersTableEle,
                            DataTableEs,
                            urlTable
                        );

                        editChapterForm.resetForm();
                        form.trigger("reset");

                        Toast.fire({
                            icon: "success",
                            text: response.message,
                        });
                    } else {
                        Toast.fire({
                            icon: "error",
                            text: response.message,
                        });
                    }

                    modal.modal("hide");
                    loadSpinner.toggleClass("active");
                },
                complete: function () {
                    form.find(".btn-save").removeAttr("disabled");
                },
                error: function (file, response) {
                    console.log(response);
                },
            });
        }

        $.ajax({
            type: "GET",
            url: getDataUrl,
            dataType: "JSON",
            success: function (data) {
                var chapter = data.chapter;
                form.find("input[name=title]").val(chapter.title);
                form.find("#description-text-area-edit").val(
                    chapter.description
                );

                var select = $("#editOrderSelectChapter");

                select.select2({
                    dropdownParent: $("#editChapterModal"),
                    minimumResultsForSearch: -1,
                });

                $.each(data.chapters_list, function (key, values) {
                    select.append(
                        '<option value="' +
                        values.chapter_order +
                        '">' +
                        values.chapter_order +
                        "</option>"
                    );
                });

                select.val(chapter.chapter_order).change();
            },
            complete: function (data) {
                modal.modal("show");
            },
            error: function (data) {
                console.log(data);
            },
        });
    });

    /* -------- PREVIEW VIDEO ---------*/

    $("html").on(
        "click",
        ".preview-chapter-video-button",
        function (e) {
            e.preventDefault();

            var modal = $("#previewChapterModal");
            var url = $(this).data("url");
            var video_container = $("#video-chapter-container");
            video_container.html(
                '<video id="chapter-video" class="video-js chapter-video"></video>'
            );

            $.ajax({
                type: "GET",
                url: url,
                dataType: "JSON",
                success: function (data) {
                    modal.find(".title-preview-section").html(data.section);
                    modal.find(".title-preview-chapter").html(data.chapter);

                    let urlDelete = data.url_delete
                    let btnDeleteVideo = modal.find('.btn-delete-video')

                    btnDeleteVideo.attr('data-url', urlDelete)

                    var playerChapter = videojs("chapter-video", {
                        controls: true,
                        fluid: true,
                        playbackRates: [0.5, 1, 1.5, 2],
                        autoplay: false,
                        preload: "auto",
                    });

                    playerChapter.src(data.url_video);

                    modal.modal("show");
                },
                error: function (data) {
                    console.log(data);
                },
            });
        }
    );

    $("#previewChapterModal").on("hidden.bs.modal", function () {
        videojs("chapter-video").dispose();
    });

    /* -------- DELETE ----------*/

    $("html").on("click", ".deleteChapter", function () {
        var button = $(this);
        var url = button.data("url");

        var sectionActive = $("#sections-list-container")
            .find(".course-section-box.active")
            .data("id");

        Swal.fire({
            title: "¡Cuidado!",
            text: "¡Esto también eliminará el progreso de los usuarios!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Continuar y eliminar",
            cancelButtonText: "Cancelar",
            reverseButtons: true,
        }).then(
            function (e) {
                if (e.value === true) {
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {
                            id: sectionActive,
                        },
                        dataType: "JSON",
                        success: function (data) {
                            if (data.success) {

                                var courseBox = $("#course-box-container");
                                var sectionBox = $(
                                    "#sections-list-container"
                                );
                                var chaptersBox = $(
                                    "#chapters-list-container"
                                );

                                courseBox.html(data.htmlCourse);
                                sectionBox.html(data.htmlSection);
                                chaptersBox.html(data.htmlChapter);

                                let urlTable = $(
                                    "#section-box-" + data.id
                                ).data("table");

                                var chaptersTableEle = $(
                                    "#freeCourses-chapters-table"
                                );

                                chapterTable(
                                    chaptersTableEle,
                                    DataTableEs,
                                    urlTable
                                );

                                $(".order-section-select").select2({
                                    minimumResultsForSearch: -1,
                                });

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
                        error: function (result) {
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

    // * ----------- DELETE VIDEO -------------

    $('html').on('click', '.btn-delete-video', function () {

        var button = $(this)
        let url = button.data('url')
        var modal = $('#previewChapterModal')

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

                            if (result.success) {
                                var chaptersBox = $(
                                    "#chapters-list-container"
                                );

                                chaptersBox.html(result.htmlChapter);

                                let urlTable = $(
                                    "#section-box-" + result.id
                                ).data("table");

                                var chaptersTableEle = $(
                                    "#freeCourses-chapters-table"
                                );

                                chapterTable(
                                    chaptersTableEle,
                                    DataTableEs,
                                    urlTable
                                );
                            }

                        },
                        error: function (result) {
                            // Swal.showValidationMessage(`
                            //     Request failed: ${result}
                            //   `);
                            ToastError.fire();
                        },
                    });
                    setTimeout(function () {
                        resolve();
                    }, 500);
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
        }).then((result) => {
            if (result.isConfirmed) {
                modal.modal('hide')
                Toast.fire({
                    icon: "success",
                    text: "¡Registro actualizado!",
                });
            }
        });
    })








    // * -------------- LOAD CONTENT MODAL -------------------

    if ($('#viewContentChapterModal').length) {

        function getSummernoteConfig(summernoteElement, cardForm) {
            return {
                dialogsInBody: true,
                minHeight: 500,
                disableDragAndDrop: true,
                dialogsFade: true,
                toolbar: [
                    ["style", ["style"]],
                    ["font", ["bold", "underline", "clear"]],
                    ["fontname", ["fontname"]],
                    ["color", ["color"]],
                    ["insert", ["link"]],
                    ["para", ["ul", "ol", "paragraph"]],
                    ["height", ["height"]],
                ],
                lang: "es-ES",
                lineHeights: ["1.2", "1.4", "1.6", "1.8", "2.0", "3.0"],
                callbacks: {
                    onChange: function (contents, $editable) {
                        summernoteElement.val(
                            summernoteElement.summernote("isEmpty")
                                ? ""
                                : contents
                        );
                        cardForm.element(summernoteElement);
                    },
                },
            };
        }

        $('html').on('click', '.showContentChapter-btn', function () {

            let button = $(this)
            let getDataUrl = button.data("send")
            let url = button.data("url")

            let modal = $("#viewContentChapterModal");

            $("#updateContentChapterForm").trigger("reset");

            $.ajax({
                method: "GET",
                url: getDataUrl,
                dataType: "JSON",
                success: function (data) {

                    let chapter = data.chapter
                    let chapterTitle = chapter.title
                    let html = data.html;

                    let chapterTitleCont = modal.find(
                        ".modal_chapter_title_content"
                    );
                    chapterTitleCont.html(chapterTitle);

                    let bodyContentChapter = modal.find(
                        ".modal-body-content-chapter"
                    );

                    bodyContentChapter.html(html);

                    modal.find("textarea[name=content]").val(chapter.content);

                    $("#updateContentChapterForm").attr("action", url);

                    var updateContentChapterForm = $("#updateContentChapterForm").validate({
                        rules: {
                            content: {
                                maxlength: 3000,
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
                                error.insertAfter(
                                    element.siblings(".note-editor")
                                );
                            } else {
                                error.insertAfter(element);
                            }
                        },
                        submitHandler: function (form, event) {
                            event.preventDefault();

                            var form = $(form);
                            var loadSpinner = form.find(".loadSpinner");
                            var modal = $("#viewContentChapterModal");

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

                                        // destroy summerNote

                                        $("#card-content-chapter-register").summernote(
                                            "destroy"
                                        );
                                        modal.find("textarea[name=content]").empty();

                                        let html = data.html;

                                        modal.find(".modal-body-content-chapter").html(html);

                                        modal.find("textarea[name=content]").val(data.content);

                                        // create summerNote
                                        $("#card-content-chapter-register").summernote(
                                            getSummernoteConfig(
                                                $("#card-content-chapter-register"),
                                                updateContentChapterForm
                                            )
                                        );

                                        modal.find("textarea[name=content]").empty();

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
                                    form.find(".btn-save").removeAttr(
                                        "disabled"
                                    );
                                    loadSpinner.toggleClass("active");
                                },
                                error: function (data) {
                                    ToastError.fire();
                                },
                            });
                        },
                    });

                    // * ------------ CREATE - DESTROY SUMMERNOTE ---------------- *

                    $("#viewContentChapterModal").on(
                        "show.bs.modal",
                        function () {
                            $("#card-content-chapter-register").summernote(
                                getSummernoteConfig(
                                    $("#card-content-chapter-register"),
                                    updateContentChapterForm
                                )
                            );
                            $(this).find("textarea[name=content]").empty();
                        }
                    );
                    $("#viewContentChapterModal").on(
                        "hidden.bs.modal",
                        function () {
                            $("#card-content-chapter-register").summernote("destroy");
                            $(this).find("textarea[name=content]").empty();
                        }
                    );
                },
                complete: function (data) {
                    modal.modal("show");
                },
                error: function (data) {
                    ToastError.fire();
                },
            });

        })
    }

})
