<div class="modal-body">

    <input type="hidden" name="place" value="{{ $place }}">

    <div class="d-flex form-row modal-multiple-columns">

        <div class="col-6">

            <div class="form-row">
                <div class="form-group col-12">
                    <label>Descripciòn *</label>
                    <input type="text" name="description" class="form-control description"
                        placeholder="Ingresa la descripción del evento">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label>Tipo *</label>
                    <div class="input-group">
                        <select name="type" class="form-control select2 typeSelect">
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="form-group col-6">
                    <label>Fecha *</label>
                    <input type="text" name="date" class="form-control datepicker">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-12">
                    <label>Instructor *</label>
                    <div class="input-group">
                        <select name="user_id" class="form-control select2 instructorSelect">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-12">
                    <label>Responsable *</label>
                    <div class="input-group">
                        <select name="responsable_id" class="form-control select2 responsableSelect">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label>Sala *</label>
                    <div class="input-group">
                        <select name="room_id" class="form-control select2 roomSelect">
                            <option></option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-6">
                    <label>Empresa Titular (opcional)</label>
                    <div class="input-group">
                        <select name="owner_companies_id" class="form-control select2 ownerCompanySelect">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>

        </div>

        <div class="col-6">

            <div class="form-row">
                <div class="form-group col-12">
                    <label>URL de grabación (opcional)</label>
                    <input type="text" name="record_url" class="form-control record_url"
                        placeholder="Ingresa una url">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-12">
                    <label>Examen *</label>
                    <div class="input-group">
                        <select name="exam_id" class="form-control select2 examSelect"
                            data-url="{{ route('admin.events.validateQuestionsScore') }}">
                            <option></option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="col-6">
                    <span class="little-text info-qty-questions">

                    </span>
                </div>
                <div class="col-6">
                    <span class="little-text info-min-score">
                    </span>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-6">
                    <label>Cantidad de enunciados *</label>
                    <input type="number" class="form-control input-qty-questions text-left" disabled
                            name="questions_qty"
                            data-url="{{ route('admin.events.validateQuestionsScore') }}"
                            onkeypress="return(event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                </div>
                <div class="form-group col-6">
                    <label>Puntuación mínima *</label>
                    <input type="number" class="form-control input-min-score text-left" disabled
                            name="min_score"
                            onkeypress="return(event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))">
                </div>
            </div>

            <div class="form-row">

                <div class="form-group col-6">
                    <label class="custom-switch mt-2">
                        <input type="checkbox" name="active" checked
                            class="custom-switch-input event-status-checkbox">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description txt-event-status">Activo</span>
                    </label>
                </div>

                <div class="form-group col-6">
                    <label class="custom-switch mt-2">
                        <input type="checkbox" name="flg_asist" checked
                            class="custom-switch-input flg-assist-checkbox">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description txt-register-flg-assist">Asistencias</span>
                    </label>
                </div>

            </div>

            <div class="form-row">

                <div class="form-group col-6">
                    <label class="custom-switch mt-2">
                        <input type="checkbox" name="flg_survey_course"
                            class="custom-switch-input flg-survey-course">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description txt-flg-survey-course">
                            Encuesta ficha sintomatológica
                        </span>
                    </label>
                </div>

                <div class="form-group col-6">
                    <label class="custom-switch mt-2">
                        <input type="checkbox" name="flg_survey_evaluation"
                            class="custom-switch-input flg-survey-evaluation">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description txt-flg-survey-evaluation">
                            Encuesta de satisfacción
                        </span>
                    </label>
                </div>

            </div>

        </div>

    </div>

</div>
