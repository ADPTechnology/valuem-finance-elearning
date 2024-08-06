<div class="img-element-container">
    <img src="{{ verifyImage($webinar->file) }}">
</div>
<div class="info-element-general-container">
    <div class="info-name-element-container">
        <div class="name-info mb-2">
            {{ $webinar->title }}
        </div>

        <div class="subtitle-cont">
            <div class="subt-text little-text">Descripción: </div>
            <span>
                {{ $webinar->description ?? '-' }}
            </span>
        </div>


        <div class="text-no-wrap text-left">
            <div class="sections-cont">
                <span class="little-text little-text-width">Fecha: </span>
                <span class="text-bold">
                    {{ getDateForHummans($webinar->date) }}
                </span>
            </div>
        </div>

    </div>


    <div class="element-status-cont ">

        <div class="status-icon-container border-0">
            <div class="status-cont text-no-wrap">
                <span class="text-info-stat little-text"> Estado: &nbsp;</span>
                {!! getStatusButton($webinar->active) !!}
            </div>
        </div>

        <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center gap-1">
                <span class="little-text text-bold">
                    Fecha de creación:
                </span>
                <span>
                    {{ $webinar->created_at }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-1 mt-1">
                <span class="little-text text-bold">
                    Fecha de actualización:
                </span>
                <span>
                    {{ $webinar->updated_at }}
                </span>
            </div>

        </div>

    </div>

    <div class="action-box info-element-box">
        <div class="btn-action-container">
            <span id="specCourse-edit-btn" class="edit-btn editWebinar"
                data-url="{{ route('admin.webinars.all.update', $webinar) }}"
                data-send="{{ route('admin.webinars.all.edit', $webinar) }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </span>
            @if ($webinar->events_count == 0 && $webinar->files_count == 0)
                <span class="delete-btn deleteWebinar"
                    data-place="show"
                    data-url="{{ route('admin.webinars.all.destroy', $webinar) }}">
                    <i class="fa-solid fa-trash-can"></i>
                </span>
            @else
                <span class="delete-btn disabled">
                    <i class="fa-solid fa-trash-can"></i>
                </span>
            @endif

        </div>
    </div>

</div>
