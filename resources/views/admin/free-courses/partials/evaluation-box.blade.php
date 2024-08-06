<div class="img-element-container">
    <img src="{{ verifyImage($course->file) }}">
</div>
<div class="info-element-general-container">
    <div class="info-name-element-container">
        <div class="name-info">
            {{ $course->description }}
        </div>
        <div class="category-info">
            <div class="cat-text little-text">Categoría</div>
            <span class="to-capitalize text-bold text-primary">
                {{ mb_strtolower($course->courseCategory->description, 'UTF-8') }}
            </span>
        </div>
    </div>
    <div class="extra-info-container">
        <div class="subtitle-cont">
            <div class="subt-text little-text">Subtítulo: </div>
            <span>
                {{ $course->subtitle }}
            </span>
        </div>
        <div class="counts-cont text-no-wrap">
            <div class="sections-cont">
                <span class="little-text little-text-width">Secciones: </span>
                <span class="text-bold">
                    {{ $course->course_sections_count }}
                </span>
            </div>
            <div class="chapters-cont">
                <span class="little-text little-text-width">Capítulos: </span>
                <span class="text-bold">
                    {{ $course->course_chapters_count }}
                </span>
            </div>
        </div>

    </div>
    <div class="element-status-cont">
        <div class="duration-cont">
            <span class="little-text">
                Duración:
            </span>
            <div class="text-no-wrap">
                {{ $course->hours }} hrs.
            </div>
        </div>
        <div class="status-icon-container">
            <div class="status-cont text-no-wrap">
                <span class="text-info-stat little-text"> Estado: &nbsp;</span>
                <span class="status {{ getStatusClass($course->active) }}">
                    {{ getStatusText($course->active) }}
                </span>
            </div>
            <div class="stat-recom-cont text-no-wrap">
                <span class="text-info-stat little-text"> Recomendado: &nbsp; </span>
                <span class="icon-recom-cont text-center">
                    {!! getStatusRecomended($course->flg_recom) !!}
                </span>
            </div>
        </div>
    </div>

    <div class="element-status-cont">
        <div class="duration-cont">
            <span class="little-text">
                Evaluación:
            </span>
            <div class="text-no-wrap fcEvaluationTitle">
                {{ $fcEvaluation->title }}
            </div>
        </div>
        <div class="status-icon-container">
            <div class="status-cont text-no-wrap">
                <div class="text-info-stat little-text"> Descripción: &nbsp;</div>
                <div class="fcEvaluationDescription w-100" style="overflow: hidden; text-overflow: ellipsis">
                    {{-- text-truncate --}}
                    {{ $fcEvaluation->description ?? '-' }}
                </div>
            </div>
        </div>

    </div>
</div>
