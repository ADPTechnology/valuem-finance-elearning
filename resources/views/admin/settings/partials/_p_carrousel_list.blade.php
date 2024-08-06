<div class="action-btn-dropdown-container show top-container-inner-box mb-3">
    <button class="btn btn-primary" data-toggle="modal"
        data-target="#registerPrincipalBannerModal">
        <i class="fa-solid fa-square-plus"></i> &nbsp; Añadir banner
    </button>
</div>

@foreach ($principalBanners as $banner)

<div class="banner-container d-flex">

    <div class="order-indicator-container text-bold">

        {{ $banner->publishing_order }}

    </div>


    <div class="image-content-container">

        <div class="content-container mb-2">
            <span class="little-text text-bold"> Título: </span>
            {{ $banner->title ?? '-' }}
        </div>

        <div class="image-container p_banner_image_container">
            <img src="{{ verifyImage($banner->file) }}">
        </div>

    </div>

    @if($banner->status == 1)
        <div class="status-icon-indicator active">
            <i class="fa-solid fa-circle-check"></i>
        </div>
    @else
        <div class="status-icon-indicator">
            <i class="fa-regular fa-circle-check"></i>
        </div>
    @endif


    <div class="btn-action-container text-center">

        <span data-url="{{ route('admin.settings.pbanner.update', $banner) }}"
            data-send="{{ route('admin.settings.pbanner.edit', $banner) }}"
            class="edit-p-banner-btn edit-btn">
            <i class="fa-solid fa-pen-to-square"></i>
        </span>

        <span data-url="{{ route('admin.settings.pbanner.destroy', $banner) }}"
            class="delete-p-banner-btn delete-btn">
            <i class="fa-solid fa-trash-can"></i>
        </span>

    </div>


</div>

@endforeach

