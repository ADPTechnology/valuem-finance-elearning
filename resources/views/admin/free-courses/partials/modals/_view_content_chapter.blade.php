<div class="modal fade" id="viewContentChapterModal" tabindex="-1" aria-labelledby="viewContentChapterModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="viewContentChapterModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-building"></i>
                        &nbsp;
                        Contenido del capítulo:
                        <span class="modal_chapter_title_content">
                        </span>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">

                <form action="" id="updateContentChapterForm" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="modal-body-content-chapter">

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
</div>
