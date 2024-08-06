<div class="modal fade" id="addCommentsModal" tabindex="-1" aria-labelledby="addCommentsModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="addCommentsModal">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-user-pen"></i>&nbsp;
                        Añadir comentario sobre la asginación:
                    </div>
                    <p id="title-ass">
                    </p>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="addCommentsForm" method="POST" enctype="multipart/form-data">
                {{-- enctype="multipart/form-data" --}}
                @csrf
                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group  d-flex justify-content-around flex-column gap-5 w-100">
                            <label>Comentario *</label>
                            <textarea name="content"  rows="8" class="col-12 summernote-card-editor" id="card-content-register"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-save">
                            Enviar
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                        <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
                    </div>

                </div>

            </form>


        </div>
    </div>
</div>
