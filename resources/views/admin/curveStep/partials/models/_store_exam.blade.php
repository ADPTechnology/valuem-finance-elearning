<div class="modal fade" id="RegisterExamModal" tabindex="-1" aria-labelledby="RegisterExamModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="RegisterCompanyModalLabel">
                    <div class="section-title mt-0">
                        <i class="fa-solid fa-square-plus"></i> &nbsp;
                        Registrar Examen
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('admin.forgettingCurve.steps.registerExam', $step) }}" id="registerExamForm"
                method="POST">
                @csrf

                <div class="modal-body">

                    {{-- <div class="form-row">
                        <div class="form-group col-12">
                            <label>Curso (elegido anteriormente)</label>
                            <div class="input-group">
                                <p class="form-control course badge badge-pill badge-light">
                                    @foreach ($step->instance->forgettingCurve->courses as $course)
                                        {{ $course->description }} <br>
                                    @endforeach
                                </p>
                            </div>
                        </div>
                    </div> --}}

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label>Título *</label>
                            <input type="text" name="title" class="form-control title"
                                placeholder="Ingresa el título del examen">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label>Duración (Minutos) *</label>
                            <input type="number" name="exam_time" class="form-control exam_time"
                                placeholder="Ingresa la duración del examen">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="custom-switch mt-2">
                            <input type="checkbox" name="active" id="register-exam-status-checkbox" checked
                                class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span id="txt-register-description-exam" class="custom-switch-description">Activo</span>
                        </label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary btn-save btn-exam-register" value="index">
                        Guardar
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
