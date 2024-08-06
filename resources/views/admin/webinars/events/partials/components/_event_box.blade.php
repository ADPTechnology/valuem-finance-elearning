<div class="info-element-general-container">

    <div class="img-element-container">
        <img src="{{ verifyImage($webinarEvent->file) }}">
    </div>

    <div class="info-name-element-container">
        <div class="name-info to-capitalize mb-1">
            <span class="little-text text-center">
                N° {{ $webinarEvent->id }}
            </span>
            <div class="text-bold">
                {{ $webinarEvent->description }}
            </div>
        </div>

        <div class="pt-2">
            <div class="flex-between flex-vertical-center ">
                <span class="little-text">
                    Fecha:
                </span>
                <div class="content-text">
                    {{ $webinarEvent->date }}
                </div>
            </div>
        </div>

        <div class="pt-2">
            <span class="little-text text-center">
                Capacitador:
            </span>
            <div class="d-flex justify-content-between mt-1 align-items-center gap-1">
                <span class="content-text text-left">
                    {{ $webinarEvent->instructor->full_name_complete }}
                </span>
                <span class="little-text text-no-wrap">
                    DNI: {{ $webinarEvent->instructor->dni }}
                </span>
            </div>

        </div>

    </div>

    <div class="extra-info-container">
        <div class="mb-1">
            <div class="subt-text little-text text-center">Sala: </div>
            <div class="d-flex justify-content-between gap-1">
                <span>
                    {{ $webinarEvent->room->description }}
                </span>
                <span>
                    <span class="little-text">
                        Capacidad:
                    </span>
                    <span>
                        {{ $webinarEvent->room->capacity }}
                    </span>
                </span>
            </div>
        </div>

        <div class="pt-2">
            <div class="subt-text little-text">Webinar: </div>
            <div>
                {{ $webinarEvent->webinar->title }}
            </div>
        </div>

    </div>

    <div class="extra-info-container">

        <div class="mb-1">
            <div class="status-cont text-no-wrap d-flex justify-content-between align-items-center gap-1 mb-2">
                <span class="text-info-stat little-text"> Estado: &nbsp;</span>
                {!! getStatusButton($webinarEvent->active) !!}
            </div>
        </div>

        <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center gap-1">
                <span class="little-text text-bold">
                    Fecha de creación:
                </span>
                <span>
                    {{ $webinarEvent->created_at }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-1 mt-1">
                <span class="little-text text-bold">
                    Fecha de actualización:
                </span>
                <span>
                    {{ $webinarEvent->updated_at }}
                </span>
            </div>
        </div>

    </div>

    <div class="action-box info-element-box">

        <div class="btn-action-container">
            <span id="event-edit-btn" class="edit-btn editWebinarEvent"
            data-url="{{ route('admin.webinars.all.events.update', $webinarEvent) }}"
            data-send="{{ route('admin.webinars.all.events.edit', $webinarEvent) }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </span>
            @if( $webinarEvent->certifications_count == 0 )
            <span class="delete-btn deleteWebinarEvent"
                data-url="{{ route('admin.webinars.all.events.destroy', $webinarEvent) }}"
                data-place="show">
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
