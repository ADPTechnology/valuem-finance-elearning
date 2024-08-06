(function ($) {
    "use strict";

    // Spinner
    var spinner = function () {
        setTimeout(function () {
            if ($('#spinner').length > 0) {
                $('#spinner').removeClass('show');
            }
        }, 1);
    };
    spinner();


    // Initiate the wowjs
    new WOW().init();


    // Sticky Navbar
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.sticky-top').css('top', '0px');
        } else {
            $('.sticky-top').css('top', '-125px');
        }
    });


    // Dropdown on mouse hover
    const $dropdown = $(".dropdown");
    const $dropdownToggle = $(".dropdown-toggle");
    const $dropdownMenu = $(".dropdown-menu");
    const showClass = "show";

    $(window).on("load resize", function() {
        if (this.matchMedia("(min-width: 992px)").matches) {
            $dropdown.hover(
            function() {
                const $this = $(this);
                $this.addClass(showClass);
                $this.find($dropdownToggle).attr("aria-expanded", "true");
                $this.find($dropdownMenu).addClass(showClass);
            },
            function() {
                const $this = $(this);
                $this.removeClass(showClass);
                $this.find($dropdownToggle).attr("aria-expanded", "false");
                $this.find($dropdownMenu).removeClass(showClass);
            }
            );
        } else {
            $dropdown.off("mouseenter mouseleave");
        }
    });


    // Back to top button
    $(window).scroll(function () {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({scrollTop: 0}, 1500, 'easeInOutExpo');
        return false;
    });


    // Header carousel
    $(".header-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1500,
        items: 1,
        dots: false,
        loop: true,
        nav : true,
        navText : [
            '<i class="bi bi-chevron-left"></i>',
            '<i class="bi bi-chevron-right"></i>'
        ]
    });


    // Testimonials carousel
    $(".testimonial-carousel").owlCarousel({
        autoplay: true,
        smartSpeed: 1000,
        center: true,
        margin: 24,
        dots: true,
        loop: true,
        nav : false,
        responsive: {
            0:{
                items:1
            },
            768:{
                items:2
            },
            992:{
                items:3
            }
        }
    });


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


})(jQuery);

