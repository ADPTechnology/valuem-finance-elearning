<div class="form-row">
    <div class="form-group col-md-12">
        <div class="alert alert-info">
            <i class="fa-solid fa-exclamation-triangle"></i>
            Solo se puede asignar un examen a esta sección
        </div>

    </div>
</div>

@if (!$fcEvaluation)
    <div class="form-row">
        <div class="form-group
        col-md-12">
            <label>Titulo *</label>
            <input type="text" class="form-control" id="title" name="title" placeholder="Ingrese el titulo">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12 container-course">
            <label>Examen *</label>
            <select name="exam_id" class="form-control select2" id="examToSectionSelect"
                data-url="{{ route('admin.freeCourses.getExamsThatBelongToCourse', $section->course) }}">
            </select>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group
        col-md-12">
            <label>Valor *</label>
            <input type="number" class="form-control" id="value" name="value"
                placeholder="Ingrese el valor de la evaluación">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group
        col-md-12">
            <label>Descripción (opcional)</label>
            <textarea name="description" class="form-control" id="description" cols="30" rows="6"
                placeholder="Ingrese la descripción"></textarea>
        </div>
    </div>

    <div class="form-group">
        <label class="custom-switch mt-2">
            <input type="checkbox" name="active" checked class="custom-switch-input curve-status-checkbox">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description txt-curve-description-status">Activo</span>
        </label>
    </div>

    <div class="alert alert-warning">
        <i class="fa-solid fa-exclamation-triangle"></i>
        No hay examen asignado a esta sección
    </div>
@else
    <div class="form-row">
        <div class="form-group col-md-12">
            <div class="exam-within-section">
                <table class="table table-bordered table-striped table-responsive">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Evaluación</th>
                            <th>Descripción</th>
                            <th>Valor</th>
                            <th>Examen</th>
                            <th>Duración (minutos)</th>
                            <th>Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $fcEvaluation->id }}</td>
                            <td>
                                <a href="{{ route('admin.freeCourses.evaluations.index', $fcEvaluation) }}">
                                    {{ $fcEvaluation->title }}
                                </a>
                            </td>
                            <td>{{ $fcEvaluation->description ?? '-' }}</td>
                            <td>{{ $fcEvaluation->value }}</td>
                            <td>
                                <a href="{{ route('admin.freeCourses.exams.show', $fcEvaluation->exam) }}">
                                    {{ $fcEvaluation->exam->title }}
                                </a>
                            </td>
                            <td>{{ $fcEvaluation->exam->exam_time }}</td>
                            <td>
                                @if ($fcEvaluation->user_fc_evaluations_count === 0)
                                    <span class="btn btn-danger btn-sm btn-delete-evaluation"
                                        data-url="{{ route('admin.freeCourses.sections.deleteEvaluation', $fcEvaluation) }}">
                                        <i class="fa-solid fa-trash"></i>
                                    </span>
                                @else
                                    <a href="javascript:void(0)" class="btn btn-danger btn-sm disabled">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif
