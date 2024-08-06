<div class="modal fade" id="storeFileModal" tabindex="-1" aria-labelledby="storeFileModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="RstoreFileModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-file-import"></i> &nbsp;
                        Subir archivo
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.filesManagement.store') }}" id="storeFileForm" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputEmail">Subir archivo (PDF)*</label>
                            <div class="input-group">
                                <input name="files[]" multiple type="file" class="form-control"
                                    accept=".pdf"
                                    placeholder="Seleccione un archivo">
                                <div class="input-group-prepend">
                                    <div class="input-group-text">
                                        <i class="fa-solid fa-file-import"></i>
                                    </div>
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