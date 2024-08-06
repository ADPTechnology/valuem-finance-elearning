<div class="info-element-general-container">
    <div class="info-name-element-container">
        <div class="title-info mb-2">
            {{ $step->title }}
        </div>

        <div class="description">
            <div class="subt-text little-text">Descripción: </div>
            <span>
                {{ $step->description ?? '-' }}
            </span>
        </div>
        <div class="min_score text-no-wrap">
            <div class="sections-cont">
                <span class="little-text little-text-width">Tipo: </span>
                <span class="text-bold">
                    {{ config('parameters.curve_steps_types')[$step->type] }}
                </span>
            </div>
            <div class="instances-cont">
                <span class="little-text little-text-width">Instancia: </span>
                <span class="text-bold">
                    {{ $step->instance->title }}
                </span>
            </div>
            <div class="order-step">
                <span class="little-text little-text-width">Orden: </span>
                <span class="text-bold">
                    {{ $step->order }}
                </span>
            </div>
        </div>
    </div>

    <div class="element-status-cont ">

        <div class="curve_name">
            <span class="little-text little-text-width">Curva: </span>
            <p class="text-bold">
                {{ $step->instance->forgettingCurve->title }}
            </p>
        </div>

        <div class="curve_description">
            <span class="little-text little-text-width">Descripción: </span>
            <p class="text-bold">
                {{ $step->instance->forgettingCurve->description ?? '-' }}
            </p>
        </div>

        <div class="curve_min_score">
            <span class="little-text little-text-width">Nota miníma: </span>
            <p class="text-bold">
                {{ $step->instance->forgettingCurve->min_score }}
            </p>
        </div>

    </div>

    <div class="elment-status-cont">
        <div class="status-icon-container border-0">
            <div class="status-fc text-no-wrap">
                <span class="little-text text-bold"> Cursos:</span>
                <p class="text-bold">
                    @foreach ($step->instance->forgettingCurve->courses as $course)
                        {{ $course->description }} <br>
                    @endforeach
                </p>
            </div>
        </div>
        <div class="status-icon-container border-0">
            <div class="status-fc text-no-wrap">
                <span class="little-text text-bold"> Estado: &nbsp;</span>
                {!! getStatusButton($step->instance->forgettingCurve->active) !!}
            </div>
        </div>

        <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center gap-1">
                <span class="little-text text-bold">
                    Fecha de creación:
                </span>
                <span>
                    {{ $step->instance->forgettingCurve->created_at }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-1 mt-1">
                <span class="little-text text-bold">
                    Fecha de actualización:
                </span>
                <span>
                    {{ $step->instance->forgettingCurve->updated_at }}
                </span>
            </div>

        </div>
    </div>



    {{-- <div class="action-box info-element-box">
        <div class="btn-action-container">
            <span id="forgettintCurve-edit-btn" class="edit-btn editForgettingCurve-btn"
                data-url="{{ route('admin.forgettingCurve.update', $forgettingCurve) }}"
                data-send="{{ route('admin.forgettingCurve.edit', $forgettingCurve) }}"
                >
                <i class="fa-solid fa-pen-to-square"></i>
            </span>

            @if ($forgettingCurve->instances_count == 0)
                <span class="delete-btn deleteForgettingCurve" data-place="show"
                 data-url="{{ route('admin.specCourses.destroy', $forgettingCurve) }}"
                >
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
