<div class="modal-body">

    <input type="hidden" name="place" value="{{ $place }}">

    <div class="d-flex form-row modal-multiple-columns">

        <div class="col-12">

            <div class="form-row">
                <div class="form-group col-12">
                    <label>Titulo *</label>
                    <input type="text" name="title" class="form-control title"
                        placeholder="Ingresa el titulo del Grupo de evento">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-12">
                    <label>Descripción *</label>
                    <input type="text" name="description" class="form-control description"
                        placeholder="Ingresa la descripción del Grupo de evento">
                </div>
            </div>
            <div class="form-row">

                <div class="form-group col-6">
                    <label class="custom-switch mt-2">
                        <input type="checkbox" name="active" checked class="custom-switch-input event-status-checkbox">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description txt-event-status">Activo</span>
                    </label>
                </div>


            </div>


        </div>


    </div>

</div>
