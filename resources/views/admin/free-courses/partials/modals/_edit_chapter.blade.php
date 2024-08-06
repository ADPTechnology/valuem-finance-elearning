<div class="modal fade" id="editChapterModal" tabindex="-1" aria-labelledby="editChapterModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editSectionModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-square-plus"></i> &nbsp;
                        Editar capítulo
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="editChapterForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Título *</label>
                            <div class="input-group">
                                <input type="text" name="title" class="form-control title"
                                    placeholder="Ingrese el título del capítulo">
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Descripción * (Max: 500 caracteres)</label>
                            <div class="input-group">
                                <textarea name="description" id="description-text-area-edit" class="form-control edit"
                                    placeholder="Ingrese la descripción del capítulo"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="selectOrder">Orden *</label>
                            <div class="input-group">
                                <select name="chapter_order" class="form-control select2"
                                    id="editOrderSelectChapter">
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Reemplazar video (Max: 150MB) (opcional)</label>
                            <div class="input-group dropzone" id="input-chapter-video-container-edit">

                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="btn-chapter-update-submit" class="btn btn-primary btn-save">
                        Guardar
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
