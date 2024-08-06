<div class="modal-body">

    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Título (opcional)</label>
            <div class="input-group">
                <input type="text" class="form-control" name="title"
                placeholder="Ingrese un título">
            </div>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Contenido (opcional)</label>
            <textarea name="content" class="summernote-card-editor p_banner_smnt_content"></textarea>
        </div>
    </div>


    <div class="form-row">
        <div class="form-group col-md-12">
            <label>Banner (Resolución recomendada: 2250px x 1080px) * </label>
            <div class="d-flex justify-content-center">
                <div id="image-preview" class="image-preview p_banner_image_container">
                    <label for="image-upload" id="image-label">Subir Imagen</label>
                    <input type="file" name="image" class="input_p_banner_image">
                    <div class="img-holder">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($type == 'edit')

    <div class="form-row">
        <div class="form-group col-md-4">
            <label>Orden * </label>
            <div class="input-group">
                <select name="publishing_order" class="form-control select2 p_banner_order_select"
                data-url="{{ route('admin.settings.pbanner.getBannersOrder') }}">

                </select>
            </div>
        </div>
    </div>

    @endif

    <div class="form-row">
        <label class="custom-switch">
                <input type="checkbox" name="status"
                    class="custom-switch-input status_p_banner_checkbox" checked>
                <span class="custom-switch-indicator"></span>
                <span
                class="custom-switch-description txt_status_p_banner">Activo</span>
        </label>
    </div>

</div>
