<div class="img-element-container">
    <img src="{{ verifyImage($course->file) }}">
</div>
<div class="info-element-general-container">
    <div class="info-name-element-container">
        <div class="name-info mb-2">
            {{ $course->description }}
        </div>

        <div class="subtitle-cont">
            <div class="subt-text little-text">Subtítulo: </div>
            <span>
                {{ $course->subtitle ?? '-' }}
            </span>
        </div>


        <div class="text-no-wrap text-left">
            <div class="sections-cont">
                <span class="little-text little-text-width">Hora de Inicio: </span>
                <span class="text-bold">
                    {{ getTimeforHummans($course->time_start) }}
                </span>
            </div>
            <div class="chapters-cont">
                <span class="little-text little-text-width">Hora de finalización: </span>
                <span class="text-bold">
                    {{ getTimeforHummans($course->time_end) }}
                </span>
            </div>
        </div>


    </div>


    <div class="element-status-cont ">

        <div class="status-icon-container border-0">
            <div class="status-cont text-no-wrap">
                <span class="text-info-stat little-text"> Estado: &nbsp;</span>
                {!! getStatusButton($course->active) !!}
            </div>
        </div>

        <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center gap-1">
                <span class="little-text text-bold">
                    Fecha de creación:
                </span>
                <span>
                    {{ $course->created_at }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-1 mt-1">
                <span class="little-text text-bold">
                    Fecha de actualización:
                </span>
                <span>
                    {{ $course->updated_at }}
                </span>
            </div>

        </div>

    </div>

    <div class="action-box info-element-box">
        <div class="btn-action-container">
            <span id="specCourse-edit-btn" class="edit-btn editLiveFreeCourse"
                data-url="{{ route('admin.freeCourseLive.update', $course) }}"
                data-send="{{ route('admin.freeCourseLive.edit', $course) }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </span>
            @if ($course->exams_count == 0 && $course->files_count == 0)
                <span class="delete-btn deleteliveFreeCourse"
                    data-place="show"
                    data-url="{{ route('admin.freeCourseLive.destroy', $course) }}">
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
