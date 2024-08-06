    <div class="modal fade" id="RegisterCategoryModal" tabindex="-1" aria-labelledby="RegisterCategoryModal"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="RegisterCategoryModalLabel">
                        <div class="section-title mt-0">
                            <i class="fa-solid fa-square-plus"></i> &nbsp;
                            Registrar Categoría
                        </div>
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form action="{{ route('admin.freeCourses.categories.store') }}" id="registerCategoryForm"
                    enctype="multipart/form-data" method="POST" data-validate="">
                    @csrf

                    <div class="modal-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Nombre *</label>
                                <div class="input-group">
                                    <input type="text" name="description" class="form-control name"
                                        placeholder="Ingrese nombre de la categoría">
                                </div>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label>Imagen de la categoría * </label>
                                <div>
                                    <div id="image-preview" class="image-preview">
                                        <label for="image-upload" id="image-label">Subir Imagen</label>
                                        <input type="file" name="image" id="input-category-image-register">
                                        <div class="img-holder">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="custom-switch mt-2">
                                <input type="checkbox" name="status" id="register-category-status-checkbox" checked
                                    class="custom-switch-input">
                                <span class="custom-switch-indicator"></span>
                                <span id="txt-register-description-category"
                                    class="custom-switch-description">Activo</span>
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
