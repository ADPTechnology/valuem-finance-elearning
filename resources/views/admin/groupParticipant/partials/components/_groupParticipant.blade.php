<div class="info-element-general-container">
    <div class="info-name-element-container">
        <div class="name-info mb-2">
            {{ $groupParticipant->title }}
        </div>

        <div class="subtitle-cont">
            <div class="subt-text little-text">Descripción: </div>
            <span>
                {{ $groupParticipant->description ?? '-' }}
            </span>
        </div>
    </div>
    <div class="element-status-cont ">

        <div class="status-icon-container border-0">
            <div class="status-cont text-no-wrap">
                <span class="text-info-stat little-text"> Estado: &nbsp;</span>
                {!! getStatusButton($groupParticipant->active) !!}
            </div>
        </div>

        <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center gap-1">
                <span class="little-text text-bold">
                    Fecha de creación:
                </span>
                <span>
                    {{ $groupParticipant->created_at }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-1 mt-1">
                <span class="little-text text-bold">
                    Fecha de actualización:
                </span>
                <span>
                    {{ $groupParticipant->updated_at }}
                </span>
            </div>

        </div>

    </div>

    <div class="action-box info-element-box">
        <div class="btn-action-container">

            {{-- <span id="specCourse-edit-btn" class="edit-btn editGroupEvent-btn"
                data-url="{{ route('admin.specCourses.groupEvents.update', $groupParticipant) }}"
                data-send="{{ route('admin.specCourses.groupEvents.edit', $groupParticipant) }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </span>

            @if ($groupEvent->participant_groups_count == 0)
                <span class="delete-btn deleteSpecCourse" data-place="show"
                    data-url="{{ route('admin.specCourses.groupEvents.destroy', $groupEvent) }}">
                    <i class="fa-solid fa-trash-can"></i>
                </span>
            @else
                <span class="delete-btn disabled">
                    <i class="fa-solid fa-trash-can"></i>
                </span>
            @endif --}}

            {{-- <span>
                botones
            </span> --}}

        </div>
    </div>

</div>
