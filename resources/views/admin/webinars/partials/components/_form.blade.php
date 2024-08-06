<input type="hidden" name="place" value="{{ $place }}">

<div class="modal-body">
    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Título *</label>
            <div class="input-group">
                <input type="text" name="title" class="form-control"
                        placeholder="Ingrese el título">
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Descripción (opcional)</label>
            <input type="text" name="description" class="form-control"
                placeholder="Ingrese una descripción">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Fecha *</label>
            <input type="text" name="date" class="form-control datepicker">
        </div>

    </div>

    <div class="form-row">

        <div class="form-group col-12">

            <label> Imagen * </label>
            <input type="file" class="webinar-image-input">

        </div>

    </div>

    <div class="form-group">
        <label class="custom-switch mt-2">
            <input type="checkbox" name="active"
                checked class="custom-switch-input webinar-status-checkbox">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description txt-description-status-webinar">Activo</span>
        </label>
    </div>

</div>
