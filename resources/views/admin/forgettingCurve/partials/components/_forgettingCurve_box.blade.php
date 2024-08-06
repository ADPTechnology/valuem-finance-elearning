<div class="img-element-container">
    <img src="{{ verifyImage($forgettingCurve->file) }}">
</div>
<div class="info-element-general-container">
    <div class="info-name-element-container">
        <div class="title-info mb-2">
            {{ $forgettingCurve->title }}
        </div>

        <div class="description">
            <div class="subt-text little-text">Descripción: </div>
            <span>
                {{ $forgettingCurve->description ?? '-' }}
            </span>
        </div>
        <div class="min_score text-no-wrap">
            <div class="sections-cont">
                <span class="little-text little-text-width">Nota mínima: </span>
                <span class="text-bold">
                    {{ $forgettingCurve->min_score }}
                </span>
            </div>
            <div class="instances-cont">
                <span class="little-text little-text-width">Instancias: </span>
                <span class="text-bold">
                    {{ $forgettingCurve->instances_count }}
                </span>
            </div>
        </div>
    </div>

    <div class="element-status-cont ">

        <div class="course_name">
            <span class="little-text little-text-width">Cursos: </span>
            <p class="text-bold">
            <ul>
                @foreach ($forgettingCurve->courses as $course)
                    <li>
                        {{ $course->description }}
                    </li>
                @endforeach
            </ul>
            </p>
        </div>

        <div class="status-icon-container border-0">
            <div class="status-fc text-no-wrap">
                <span class="text-info-stat little-text"> Estado: &nbsp;</span>
                {!! getStatusButton($forgettingCurve->active) !!}
            </div>
        </div>

        <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center gap-1">
                <span class="little-text text-bold">
                    Fecha de creación:
                </span>
                <span>
                    {{ $forgettingCurve->created_at }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-1 mt-1">
                <span class="little-text text-bold">
                    Fecha de actualización:
                </span>
                <span>
                    {{ $forgettingCurve->updated_at }}
                </span>
            </div>

        </div>

    </div>

    <div class="action-box info-element-box">
        <div class="btn-action-container">


            <span id="forgettintCurve-edit-btn" class="edit-btn editForgettingCurve-btn"
                data-url="{{ route('admin.forgettingCurve.update', $forgettingCurve) }}"
                data-send="{{ route('admin.forgettingCurve.edit', $forgettingCurve) }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </span>

            @if (
                $fc_step_progress_count == 0 &&
                    $fc_step_progress_count == 0 &&
                    $fc_exams_count == 0 &&
                    $f_videos_count == 0 &&
                    $f_questions_count == 0 &&
                    $f_questions_video_count == 0)
                <span class="delete-btn deleteForgettingCurve" data-place="show"
                    data-url="{{ route('admin.forgettingCurve.destroy', $forgettingCurve) }}">
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
