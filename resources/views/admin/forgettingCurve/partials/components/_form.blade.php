@php
    $place = isset($place) ? $place : '';
@endphp

<input type="hidden" name="place" value="{{ $place }}">

<div class="modal-body">
    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Título *</label>
            <div class="input-group">
                <input type="text" name="title" class="form-control title" placeholder="Ingrese el título">
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Descripción (opcional)</label>
            <input type="text" name="description" class="form-control" placeholder="Ingrese subtítulo">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Nota mínima *</label>
            <input type="number" name="min_score" class="form-control" placeholder="Ingrese una nota mínima">
        </div>
    </div>

    @if ($place == 'register')
        <div class="form-row">
            <div class="form-group col-md-12 container-course">
                <label>Tipo de curso *</label>
                <select name="type_course" class="form-control select2" id="typeCourseSelect"
                    data-url="{{ route('admin.forgettingCurve.getCourses') }}">
                </select>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group col-md-12 container-course">
                <label>Cursos *</label>
                <select name="courses_id[]" class="form-control select2" id="courseSelect"
                    data-url="{{ route('admin.forgettingCurve.getCourses') }}">
                </select>
            </div>
        </div>
    @else
        <div class="form-row">
            <div class="form-group col-md-12 container-course">

                <label>Cursos</label>
                <p id="courseDescription"></p>

            </div>
        </div>
    @endif

    <div class="form-row">

        <div class="form-group col-12">

            <label> Imagen * </label>
            <input type="file" class="forgetting-curve-image-input" id="forgetting-curve-image-input">

        </div>

    </div>

    <div class="form-group">
        <label class="custom-switch mt-2">
            <input type="checkbox" name="active" checked class="custom-switch-input curve-status-checkbox">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description txt-curve-description-status">Activo</span>
        </label>
    </div>

</div>
