<div class="action-btn-dropdown-container show top-container-inner-box mb-3">
    @if ($news->count() >= 3)
        <button class="btn btn-primary disabled" disabled>
            <i class="fa-solid fa-square-plus"></i> &nbsp; Añadir Noticia
        </button>
    @else
        <button class="btn btn-primary" data-toggle="modal" data-target="#registerNewModal">
            <i class="fa-solid fa-square-plus"></i> &nbsp; Añadir Noticia
        </button>
    @endif
</div>

@foreach ($news as $new)
    <div class="banner-container d-flex">

        <div class="order-indicator-container text-bold">

            {{ $new->publishing_order }}

        </div>


        <div class="image-content-container">

            <div class="content-container mb-2">
                <span class="little-text text-bold"> Titulo: </span>
                {{ $new->title }}
            </div>

            <div class="content-container mb-2">
                <span class="little-text text-bold"> URL: </span>
                {!! $new->content ?? '-' !!}
            </div>

            <div class="image-container" style="width: auto">
                <img src="{{ verifyImage($new->file) }}" alt="">
            </div>

        </div>

        @if ($new->status == 1)
            <div class="status-icon-indicator active">
                <i class="fa-solid fa-circle-check"></i>
            </div>
        @else
            <div class="status-icon-indicator">
                <i class="fa-regular fa-circle-check"></i>
            </div>
        @endif


        <div class="btn-action-container text-center">

            <span data-url="{{ route('admin.news.update', $new) }}" data-send="{{ route('admin.news.edit', $new) }}"
                class="edit-new-btn edit-btn">
                <i class="fa-solid fa-pen-to-square"></i>
            </span>

            <span data-url="{{ route('admin.news.destroy', $new) }}" class="delete-new-btn delete-btn">
                <i class="fa-solid fa-trash-can"></i>
            </span>

        </div>


    </div>
@endforeach
