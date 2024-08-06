<div class="modal-body">

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Título *</label>
            <div class="input-group">
                <input type="text" name="title" class="form-control title"
                    placeholder="Ingrese el título del Grupo del Evento">
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Descripción (opcional)</label>
            <div class="input-group">
                <input type="text" name="subtitle" class="form-control"
                    placeholder="Ingrese una descripción">
            </div>
        </div>
    </div>

    @if (isset($edit))
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="selectOrder">Orden *</label>
                <div class="input-group">
                    <select name="order" class="form-control select2 editOrderSelect">
                    </select>
                </div>
            </div>
        </div>
    @endif

    <div class="form-group">
        <label class="custom-switch mt-2">
            <input type="checkbox" name="active" checked class="custom-switch-input module-status-checkbox">
            <span class="custom-switch-indicator"></span>
            <span class="custom-switch-description txt-module-description-status">Activo</span>
        </label>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
    <button type="submit" class="btn btn-primary btn-save">
        Guardar
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
    </button>
</div>
