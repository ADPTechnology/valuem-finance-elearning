<div class="modal fade" id="registerChapterModal" tabindex="-1" aria-labelledby="registerChapterModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="registerSectionModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-square-plus"></i> &nbsp;
                        Añadir capítulo
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="registerChapterForm" method="POST" enctype="multipart/form-data">
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
                                <textarea name="description" id="description-text-area-register" class="form-control description"
                                    placeholder="Ingrese la descripción del capítulo"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Video (opcional) (Max: 150MB)</label>
                            <div class="input-group dropzone" id="input-chapter-video-container">
                                {{-- <div class="message-file-invalid">
                                    <i class="fa-solid fa-circle-exclamation fa-bounce"></i> &nbsp;
                                    Este campo es obligatorio
                                </div> --}}
                            </div>
                        </div>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
                    <button type="submit" id="btn-chapter-register-submit" class="btn btn-primary btn-save">
                        Guardar
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
