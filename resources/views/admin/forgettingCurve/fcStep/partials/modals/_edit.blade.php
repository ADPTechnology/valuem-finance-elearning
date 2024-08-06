<div class="modal fade" id="editFcStepModal" tabindex="-1" aria-labelledby="editFcStepModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editFcStepModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-pen-to-square"></i> &nbsp;
                        Editar Paso
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="editFcStepForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Título *</label>
                            <div class="input-group">
                                <input type="text" name="title" class="form-control title"
                                    placeholder="Ingrese el título">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Descripción (opcional)</label>
                            <input type="text" name="description" class="form-control"
                                placeholder="Ingrese la descripción">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-3">
                            <label for="inputOrder">Orden *</label>
                            <div class="input-group">
                                <select name="order" class="form-control select2" id="editOrderSelect">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Tipo</label>
                            <p id="type" class="form-control disabled" disabled>
                            </p>
                        </div>
                    </div>

                    {{-- add image --}}

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Imagen del paso (opcional)</label>
                            <div>
                                <div id="image-preview" class="image-preview">
                                    <label for="image-upload" id="image-label">Subir Imagen</label>
                                    <input type="file" name="image" id="step-image-register">
                                    <div class="img-holder">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- addend image --}}


                    <div class="form-group">
                        <label class="custom-switch mt-2">
                            <input type="checkbox" name="active" class="custom-switch-input step-status-checkbox">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description txt-step-description-status"></span>
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

            </form>

        </div>
    </div>
</div>
