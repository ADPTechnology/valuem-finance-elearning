<input type="hidden" name="place" value="{{ $place }}">

<div class="modal-body">

    <div class="form-row">
        <div class="form-group col-12">
            <label>Descripciòn *</label>
            <input type="text" name="description" class="form-control description"
                placeholder="Ingresa la descripción del evento">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-6">
            <label>Fecha *</label>
            <input type="text" name="date" class="form-control datepicker">
        </div>

        <div class="form-group col-6">
            <label>Sala *</label>
            <div class="input-group">
                <select name="room_id" class="form-control select2 RoomSelect"
                    data-url="{{ route('admin.webinars.all.events.create') }}">
                    <option></option>
                </select>
            </div>
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
            <label>Instructor *</label>
            <div class="input-group">
                <select name="user_id" class="form-control select2 InstructorSelect"
                    data-url="{{ route('admin.webinars.all.events.create') }}">
                    <option></option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Responsable *</label>
            <div class="input-group">
                <select name="responsable_id" class="form-control select2 ResponsableSelect"
                    data-url="{{ route('admin.webinars.all.events.create') }}">
                    <option></option>
                </select>
            </div>
        </div>
    </div>

    <div class="form-row">

        <div class="form-group col-12">

            <label> Imagen (opcional) </label>
            <input type="file" class="webinar-event-image-input">

        </div>

    </div>

    <div class="form-group">
        <label class="custom-switch mt-2">
            <input type="checkbox" name="active"
                checked class="custom-switch-input webinar-event-status-checkbox">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description txt-description-status-webinar-event">Activo</span>
        </label>
    </div>

</div>
