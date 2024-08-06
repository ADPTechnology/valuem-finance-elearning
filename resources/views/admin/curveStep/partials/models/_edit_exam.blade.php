<div class="modal fade" id="editExamModal" tabindex="-1" aria-labelledby="editExamModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editExamModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-pen-to-square"></i> &nbsp;
                        Editar Examen
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="editExamForm" method="POST">
                @csrf
                <input type="hidden" name="place" value="index">

                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label>Título *</label>
                            <input type="text" name="title" class="form-control title"
                                placeholder="Ingresa el título del examen">
                        </div>
                    </div>

                    {{-- <div class="form-row">
                        <div class="form-group col-12">
                            <label>Curso *</label>
                            <div class="input-group">
                                <p class="form-control" id="course">

                                </p>
                            </div>
                        </div>
                    </div> --}}

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Duración (Minutos) *</label>
                            <input type="number" name="exam_time" class="form-control exam_time"
                                placeholder="Ingresa la duración del examen">
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="custom-switch mt-2">
                            <input type="checkbox" name="active" id="edit-exam-status-checkbox" checked
                                class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span id="txt-edit-description-exam" class="custom-switch-description">Activo</span>
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
