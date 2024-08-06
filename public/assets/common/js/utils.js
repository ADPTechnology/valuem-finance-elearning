// function setActiveCheckbox(ele, txtClass) {
//     $("html").on("change", ele, function () {
//         var txtDesc = $(this).closest("form").find(txtClass);

function setActiveCheckbox(ele, txtClass) {
    $("html").on("change", ele, function () {
        var txtDesc = $(this).closest("form").find(txtClass);
        if (this.checked) {
            txtDesc.html("Activo");
        } else {
            txtDesc.html("Inactivo");
        }
    });
}

/**
 *
 * @param {Form} form
 * @param {*} checkbox - Checkbox to change
 * @param {*} txt - Text to change
 * @param {*} status - Active or not active
 */
function setActiveCheckBoxForResult(form, checkbox, txt = null, status) {
    if (status === "S") {
        form.find(checkbox).prop("checked", true);
        form.find(txt).html("Activo");
    } else if (status === "N") {
        form.find(checkbox).prop("checked", false);
        form.find(txt).html("Inactivo");
    }

    if (status === 1) {
        form.find(checkbox).prop("checked", true);
    } else if (status === 0) {
        form.find(checkbox).prop("checked", false);
    }
}

function setActiveCheckboxYesNot(ele, txtClass) {
    $("html").on("change", ele, function () {
        var txtDesc = $(this).closest("form").find(txtClass);
        if (this.checked) {
            txtDesc.html("Si");
        } else {
            txtDesc.html("No");
        }
    });
}

function viewSelectYesNot(selectGroupContainer, checkBoxIsGroupal) {
    $(selectGroupContainer).hide();

    $(checkBoxIsGroupal).on("change", function () {
        if (this.checked) {
            $(selectGroupContainer).show();
        } else {
            $(selectGroupContainer).hide();
        }
    });
}

function initImageChange(inputEle, formEle, Toast) {
    inputEle.val("");
    inputEle.on("change", function () {
        var img_path = $(this)[0].value;
        var img_holder = formEle.find(".img-holder");
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
                        class: "img-fluid",
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
            inputEle.val("");
            Toast.fire({
                icon: "warning",
                title: "¡Selecciona una imagen!",
            });
        }
    });
}

function initImageEditChange(inputEle, formEle, Toast) {
    inputEle.val("");
    inputEle.on("change", function () {
        var img_path = $(this)[0].value;
        var img_holder = formEle.find(".img-holder");
        var img_extension = img_path
            .substring(img_path.lastIndexOf(".") + 1)
            .toLowerCase();

        formEle.validate();
        inputEle.rules("add", { required: true });

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
                        class: "img-fluid",
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
            inputEle.val("");
            Toast.fire({
                icon: "warning",
                title: "¡Selecciona una imagen!",
            });
        }
    });
}

function setActiveSubmitButton(buttonEle) {
    buttonEle.click(function () {
        $("button[type=submit]", $(this).parents("form"))
            .removeAttr("clicked")
            .removeAttr("name");
        $(this).attr("clicked", "true").attr("name", "verifybtn");
    });
}

function getFirstCharUpper(str) {
    return str.charAt(0).toUpperCase() + str.slice(1);
}

const dateRangeConfig = {
    ranges: {
        Todo: [moment("1970-01-01"), moment("3000-01-01")],
        Hoy: [moment(), moment().add(1, "days")],
        Ayer: [moment().subtract(1, "days"), moment()],
        "Últimos 7 días": [
            moment().subtract(6, "days"),
            moment().add(1, "days"),
        ],
        "Últimos 30 días": [
            moment().subtract(29, "days"),
            moment().add(1, "days"),
        ],
        "Este mes": [
            moment().startOf("month"),
            moment().endOf("month").add(1, "days"),
        ],
        "Último mes": [
            moment().subtract(1, "month").startOf("month"),
            moment().subtract(1, "month").endOf("month").add(1, "days"),
        ],
    },
    startDate: moment("1970-01-01"),
    endDate: moment("3000-01-01"),
};

function InitSelect2(ele_class, config = {}) {
    $(ele_class).each(function () {
        let select_cnf = {
            dropdownParent: $(this).closest("form"),
        };

        for (let key in config) {
            select_cnf[key] = config[key];
        }

        $(this).select2(select_cnf);
    });
}

function InitAjaxSelect2(ele_class, config = {}, TEXT, DATA = {}) {
    $(ele_class).each(function () {
        let select_cnf = {
            dropdownParent: $(this).closest("form"),
            language: {
                noResults: function () {
                    return "No se encontraron resultados";
                },
                searching: function () {
                    return "Buscando...";
                },
            },
        };

        for (let key in config) {
            select_cnf[key] = config[key];
        }

        select_cnf["ajax"] = {
            url: $(this).data("url"),
            dataType: "JSON",
            data: DATA,
            delay: 0,
            processResults: function (data) {
                return {
                    results: $.map(data, function (item) {
                        return {
                            text: item[TEXT],
                            id: item.id,
                        };
                    }),
                };
            },
        };

        $(this).select2(select_cnf);
    });
}

function getSummernoteConfig(summernoteElement, cardForm) {
    return {
        dialogsInBody: true,
        minHeight: 160,
        disableDragAndDrop: true,
        dialogsFade: true,
        toolbar: [
            ["font", ["bold", "italic", "underline", "clear"]],
            ["fontsize", ["fontsize"]],
            ["color", ["color"]],
            ["insert", ["link"]],
            ["para", ["ul", "ol", "paragraph"]],
            ["height", ["height"]],
        ],
        lang: "es-ES",
        fontSizes: [
            "8",
            "9",
            "10",
            "11",
            "12",
            "14",
            "18",
            "24",
            "36",
            "48",
            "64",
            "82",
            "150",
        ],
        lineHeights: ["1.0", "1.2", "1.4", "1.6", "1.8", "2.0", "3.0"],
        callbacks: {
            onChange: function (contents, $editable) {
                summernoteElement.val(
                    summernoteElement.summernote("isEmpty") ? "" : contents
                );
                cardForm.element(summernoteElement);
            },
        },
    };
}

export {
    setActiveCheckbox,
    initImageChange,
    initImageEditChange,
    setActiveSubmitButton,
    setActiveCheckboxYesNot,
    dateRangeConfig,
    viewSelectYesNot,
    getFirstCharUpper,
    InitSelect2,
    InitAjaxSelect2,
    getSummernoteConfig,
    setActiveCheckBoxForResult,
};
