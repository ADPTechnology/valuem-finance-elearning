<div class="modal fade" id="editQuestionModal" tabindex="-1" aria-labelledby="editQuestionModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editExamModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-pen-to-square"></i> &nbsp;
                        Editar pregunta
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="" id="editQuestionForm" method="POST">
                @csrf

                <input type="hidden" name="place" value="index">

                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label>Pregunta *</label>
                            <input type="text" name="statement" class="form-control title"
                                placeholder="Ingresa la pregunta">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label>Respuesta correcta *</label>
                            <input type="text" name="correct_answer" class="form-control title"
                                placeholder="Ingresa la respuesta correcta">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Puntos *</label>
                            <input type="number" name="points" class="form-control exam_time"
                                placeholder="Ingresa los puntos">
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
