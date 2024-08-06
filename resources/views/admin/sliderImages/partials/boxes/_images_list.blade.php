<div class="action-btn-dropdown-container show top-container-inner-box mb-3">
    <button class="btn btn-primary" data-toggle="modal" data-target="#RegisterSliderImagesModal">
        <i class="fa-solid fa-square-plus"></i> &nbsp; Añadir Imagen
    </button>
</div>

@foreach ($sliderImages as $sliderImage)
    <div class="banner-container d-flex">

        <div class="order-indicator-container text-bold">

            {{ $sliderImage->order }}

        </div>

        <div class="image-content-container image-sliderLogin">

            <div class="content-container mb-2">
                <span class="little-text text-bold"> URL: </span>
                {!! $sliderImage->content ?? '-' !!}
            </div>

            <div class="image-container">
                <img src="{{ verifyImage($sliderImage->file) }}" alt="">
            </div>

        </div>

        @if ($sliderImage->status == 1)
            <div class="status-icon-indicator active">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        @else
            <div class="status-icon-indicator">
                <i class="fa-regular fa-circle-check"></i>
            </div>
        @endif


        <div class="btn-action-container text-center">

            <span data-url="{{ route('admin.sliderImage.update', $sliderImage) }}"
                data-send="{{ route('admin.sliderImage.edit', $sliderImage) }}" class="edit-sliderImage-btn edit-btn">
                <i class="fa-solid fa-pen-to-square"></i>
            </span>

            <span data-url="{{ route('admin.sliderImage.destroy', $sliderImage) }}"
                class="delete-sliderImage-btn delete-btn">
                <i class="fa-solid fa-trash-can"></i>
            </span>

        </div>


    </div>
@endforeach
