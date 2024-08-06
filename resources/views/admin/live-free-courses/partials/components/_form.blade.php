<input type="hidden" name="place" value="{{ $place }}">

<div class="modal-body">
    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Nombre *</label>
            <div class="input-group">
                <input type="text" name="description" class="form-control"
                        placeholder="Ingrese nombre del curso">
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Subtítulo (opcional)</label>
            <input type="text" name="subtitle" class="form-control"
                placeholder="Ingrese subtítulo">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Fecha *</label>
            <input type="text" name="date" class="form-control datepicker">
        </div>

        <div class="form-group col-md-6">
            <label>Horas *</label>
            <input type="number" name="hours" step="0.1" min="0.1" class="form-control">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label>Hora de inicio *</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-clock"></i>
                    </div>
                  </div>
                <input name="time_start" type="text" class="form-control timepicker">
            </div>
        </div>

        <div class="form-group col-md-6">
            <label>Hora de finalización *</label>
            <div class="input-group">
                <div class="input-group-prepend">
                    <div class="input-group-text">
                      <i class="fas fa-clock"></i>
                    </div>
                  </div>
                <input name="time_end" type="text" class="form-control timepicker">
            </div>
        </div>
    </div>

    <div class="form-row">

        <div class="form-group col-12">

            <label> Imagen * </label>
            <input type="file" class="live-free-course-image-input">

        </div>

    </div>

    <div class="form-group">
        <label class="custom-switch mt-2">
            <input type="checkbox" name="active"
                checked class="custom-switch-input live-freecourse-course-status-checkbox">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description txt-description-status-live-freecourse">Activo</span>
        </label>
    </div>

</div>
