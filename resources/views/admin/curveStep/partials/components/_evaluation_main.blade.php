@if ($step->exams_count < 1)
    <div class="action-btn-dropdown-container top-container-inner-box">
        <button class="btn btn-primary" id="btn-register-exams"
            data-toggle="modal" data-target="#RegisterExamModal">
            <i class="fa-solid fa-plus"></i> &nbsp;
            Añadir Examen
            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
        </button>
    </div>
    <div class="contain">
        @include('admin.curveStep.partials.components._evaluation_list_empty')
    </div>
@elseif ($step->exams_count >= 1)
    <div class="action-btn-dropdown-container top-container-inner-box">
        <span class="btn btn-primary disabled" disabled>
            <i class="fa-solid fa-plus"></i> &nbsp;
            Añadir Examen
            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
        </span>
    </div>
    <div class="contain">
        @include('admin.curveStep.partials.components._evaluation_list')
    </div>
@endif
