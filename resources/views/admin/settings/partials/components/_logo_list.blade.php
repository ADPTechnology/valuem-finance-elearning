<div class="action-btn-dropdown-container show top-container-inner-box mb-3">

    <button class="btn btn-primary" data-toggle="modal" data-target="#RegisterLogoImageModal">
        <i class="fa-solid fa-square-plus"></i> &nbsp; Actualizar imagen
    </button>

</div>

<div class="logo-banner-container d-flex">
    <div class="image-content-container image-sliderLogin">
        <div class="image-container-logo">
            <img src="{{ verifyUrl($config->logo_url) }}" alt="">
        </div>
    </div>

    <div class="btn-action-container text-center">

        <span data-url="{{ route('admin.settings.config.destroy.logo', $config) }}"
            class="delete-logo-img-btn delete-btn">
            <i class="fa-solid fa-trash-can"></i>
        </span>

    </div>
</div>
