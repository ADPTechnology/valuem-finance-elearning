<div class="info-element-general-container">
    <div class="info-name-element-container">
        <div class="title-info mb-2">
            {{ $exam->fcStep->title }}
        </div>

        <div class="description">
            <div class="subt-text little-text">Descripción: </div>
            <span>
                {{ $exam->fcStep->description ?? '-' }}
            </span>
        </div>
        <div class="min_score text-no-wrap">
            <div class="sections-cont">
                <span class="little-text little-text-width">Tipo: </span>
                <span class="text-bold">
                    {{ config('parameters.curve_steps_types')[$exam->fcStep->type] }}
                </span>
            </div>
            <div class="instances-cont">
                <span class="little-text little-text-width">Instancia: </span>
                <span class="text-bold">
                    {{ $exam->fcStep->instance->title }}
                </span>
            </div>
            <div class="order-step">
                <span class="little-text little-text-width">Orden: </span>
                <span class="text-bold">
                    {{ $exam->fcStep->order }}
                </span>
            </div>
        </div>
    </div>

    <div class="element-status-cont ">

        <div class="curve_name">
            <span class="little-text little-text-width">Curva: </span>
            <p class="text-bold">
                {{ $exam->fcStep->instance->forgettingCurve->title }}
            </p>
        </div>

        <div class="curve_description">
            <span class="little-text little-text-width">Descripción: </span>
            <p class="text-bold">
                {{ $exam->fcStep->instance->forgettingCurve->description ?? '-' }}
            </p>
        </div>

        <div class="curve_min_score">
            <span class="little-text little-text-width">Nota miníma: </span>
            <p class="text-bold">
                {{ $exam->fcStep->instance->forgettingCurve->min_score }}
            </p>
        </div>

    </div>

    <div class="elment-status-cont">
        <div class="status-icon-container border-0">
            <div class="status-fc text-no-wrap">
                <span class="little-text text-bold"> Cursos:</span>
                <p class="text-bold">
                    @foreach ($exam->fcStep->instance->forgettingCurve->courses as $course)
                        {{ $course->description }} <br>
                    @endforeach
                </p>
            </div>
        </div>
        <div class="status-icon-container border-0">
            <div class="status-fc text-no-wrap">
                <span class="little-text text-bold"> Estado: &nbsp;</span>
                {!! getStatusButton($exam->fcStep->instance->forgettingCurve->active) !!}
            </div>
        </div>

        <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center gap-1">
                <span class="little-text text-bold">
                    Fecha de creación:
                </span>
                <span>
                    {{ $exam->fcStep->instance->forgettingCurve->created_at }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-1 mt-1">
                <span class="little-text text-bold">
                    Fecha de actualización:
                </span>
                <span>
                    {{ $exam->fcStep->instance->forgettingCurve->updated_at }}
                </span>
            </div>

        </div>
    </div>

    <div class="element-status-cont ">

        <div class="curve_name">
            <span class="little-text little-text-width">Examen: </span>
            <p class="text-bold">
                {{ $exam->title }}
            </p>
        </div>

        <div class="curve_description">
            <span class="little-text little-text-width">Descripción: </span>
            <p class="text-bold">
                {{ $exam->description ?? '-' }}
            </p>
        </div>

    </div>

</div>
