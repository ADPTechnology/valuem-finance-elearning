<div class="modal fade" id="editFreeCourseModal" tabindex="-1" aria-labelledby="editFreeCourseModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editFreeCourseModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-pen-to-square"></i> &nbsp;
                        Editar Curso libre
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.freeCourses.courses.update', $course) }}" id="editFreeCourseForm"
                enctype="multipart/form-data" method="POST" data-validate="">
                @csrf

                <input type="hidden" name="place" value="index">

                <input type="hidden" name="category_id" value="{{ $course->courseCategory->id }}">

                <div class="modal-body">

                    <div class="row">
                        <div class="col-5">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Nombre *</label>
                                    <div class="input-group">
                                        <input type="text" name="description" class="form-control description"
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
                                    <label>Nota mínima: </label>
                                    <input type="number" name="min_score" class="form-control"
                                    placeholder="Ingrese un valor numérico"
                                    value="{{ $course->min_score ?? 0 }}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Duración (Horas) *</label>
                                    <input type="number" name='hours' class="form-control"
                                    placeholder="Ingrese la duración del curso">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label>Categoría *</label>
                                    <div class="input-disabled to-capitalize">
                                        {{ mb_strtolower($course->courseCategory->description, 'UTF-8') }}
                                    </div>
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Imagen del curso * </label>
                                    <div>
                                        <div id="image-preview" class="image-preview">
                                            <label for="image-upload" id="image-label">Subir Imagen</label>
                                            <input type="file" name="image" id="image-upload-freecourse-edit">
                                            <div class="img-holder">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="active" id="edit-course-status-checkbox" checked
                                        class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span id="txt-edit-description-course"
                                        class="custom-switch-description">Activo</span>
                                </label>
                            </div>

                            <div class="form-group">

                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="flg_recom" id="edit-course-recom-checkbox"
                                        class="custom-switch-input">
                                    <span class="custom-switch-indicator"></span>
                                    <span id="txt-edit-description-course-recom" class="custom-switch-description">
                                        Registrar como curso recomendado
                                    </span>
                                </label>
                            </div>
                        </div>

                        <div class="col-7">
                            <div class="form-row">
                                <div class="form-group col-md-12">
                                    <label>Precio *</label>
                                    <input type="number" name="price" class="form-control"
                                        placeholder="Ingrese el precio del curso">
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-12">
                                    <label>Descripción (opcional)</label>
                                    <textarea name="description_details" class="summernote-card-editor" id="card-content-register"></textarea>
                                </div>
                            </div>
                        </div>

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
