<input type="hidden" name="place" value="{{ $place }}">

<div class="modal-body">
    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Título *</label>
            <div class="input-group">
                <input type="text" name="title" class="form-control title"
                        placeholder="Ingrese el título del curso">
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
        <div class="form-group col-12">
            <label>Fecha *</label>
            <input type="text" name="date" class="form-control datepicker">
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
            <label>Hora de fin *</label>
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
        <div class="form-group col-md-12">
            <label>Imagen del curso (opcional) </label>
            <div>
                <div id="image-preview" class="image-preview">
                    <label for="image-upload" id="image-label">Subir Imagen</label>
                    <input type="file" name="image" class="course-image-input">
                    <div class="img-holder">

                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <label class="custom-switch mt-2">
            <input type="checkbox" name="active"
                checked class="custom-switch-input specCourse-status-checkbox">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description txt-specCourse-description-status">Activo</span>
        </label>
    </div>

</div>

