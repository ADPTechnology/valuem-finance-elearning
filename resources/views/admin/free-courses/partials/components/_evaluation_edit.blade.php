@php
    $labelStatus = $fcEvaluation->active === 'S' ? 'Activo' : 'Inactivo';
@endphp

<div class="modal-body">
    <div class="form-row">
        <div class="form-group col-12">
            <label>Titulo *</label>
            <input type="text" name="title" class="form-control content" placeholder="Ingresa el titulo"
                value="{{ $fcEvaluation->title }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Descriptión (opcional)</label>
            <textarea style="resize: none ;field-sizing: content;" type="text" name="description" class="form-control content"
                placeholder="Ingresa la descripción">{{ $fcEvaluation->description }}</textarea>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Valor *</label>
            <input type="number" name="value" class="form-control content" placeholder="Ingresa el valor"
                value="{{ $fcEvaluation->value }}">
        </div>
    </div>

    <div class="form-group">
        <label class="custom-switch mt-2">
            <input type="checkbox" name="active" id="edit-evaluation-status-checkbox"
                @if ($fcEvaluation->active === 'S') checked @endif class="custom-switch-input">
            <span class="custom-switch-indicator"></span>
            <span id="txt-edit-description-evaluation" class="custom-switch-description">{{ $labelStatus }}</span>
        </label>
    </div>

</div>

<div class="modal-footer">
    <button type="submit" class="btn btn-primary btn-save">
        Guardar
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
    </button>
</div>
