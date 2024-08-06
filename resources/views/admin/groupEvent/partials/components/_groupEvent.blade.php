<div class="info-element-general-container">
    <div class="info-name-element-container">
        <div class="name-info mb-2">
            {{ $groupEvent->title }}
        </div>

        <div class="subtitle-cont">
            <div class="subt-text little-text">Descripción: </div>
            <span>
                {{ $groupEvent->description ?? '-' }}
            </span>
        </div>
    </div>
    <div class="element-status-cont ">

        <div class="status-icon-container border-0">
            <div class="status-cont text-no-wrap">
                <span class="text-info-stat little-text"> Estado: &nbsp;</span>
                {!! getStatusButton($groupEvent->active) !!}
            </div>
        </div>

        <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center gap-1">
                <span class="little-text text-bold">
                    Fecha de creación:
                </span>
                <span>
                    {{ $groupEvent->created_at }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-1 mt-1">
                <span class="little-text text-bold">
                    Fecha de actualización:
                </span>
                <span>
                    {{ $groupEvent->updated_at }}
                </span>
            </div>

        </div>

    </div>

    {{-- <div class="action-box info-element-box">
        <div class="btn-action-container">

            <span id="specCourse-edit-btn" class="edit-btn editGroupEvent-btn"
                data-url="{{ route('admin.specCourses.groupEvents.update', $groupEvent) }}"
                data-send="{{ route('admin.specCourses.groupEvents.edit', $groupEvent) }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </span>

            @if ($groupEvent->participant_groups_count == 0 && $groupEvent->events_count == 0)
                <span class="delete-btn deleteSpecCourse" data-place="show"
                    data-url="{{ route('admin.specCourses.groupEvents.destroy', $groupEvent) }}">
                    <i class="fa-solid fa-trash-can"></i>
                </span>
            @else
                <span class="delete-btn disabled">
                    <i class="fa-solid fa-trash-can"></i>
                </span>
            @endif

        </div>
    </div> --}}

</div>
