<div class="modal fade" id="storeCertificationScoreFin" tabindex="-1" aria-labelledby="storeCertificationScoreFin"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-md">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="registerSpecEventModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-square-plus"></i> &nbsp;
                        Nota de certificación
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="registerScoreFinCertification" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Nota *</label>
                            <div class="input-group">
                                <input type="text" name="score_fin" class="form-control"
                                    placeholder="Ingrese la nota del certificado">
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

                </div>

            </form>

        </div>
    </div>
</div>
