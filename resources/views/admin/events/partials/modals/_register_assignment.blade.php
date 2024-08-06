<div class="modal fade" id="registerAssignmentModal" tabindex="-1" aria-labelledby="registerAssignmentModal"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable ">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="registerParticipantsModalTitle">
                    <div class="title-header-show mt-0">
                        <i class="fa-solid fa-plus fa-xs"></i> &nbsp;
                        <span id="txt-context-element" class="text-bold">
                            Registrar Asignación o tarea
                        </span>
                    </div>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"> &times; </span>
                </button>
            </div>

            <form action="{{ route('admin.specCourses.events.assignments.store', $event) }}"
                enctype="multipart/form-data"
                id="register-assignment-form" method="POST">
                @csrf

                <div class="modal-body">

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label>Titulo *</label>
                            <input type="text" name="title" class="form-control title"
                                placeholder="Ingresa el titulo de la asignación">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label>Descripción *</label>
                            <input type="text" name="description" class="form-control description"
                                placeholder="Ingresa la asignación de la asignación">
                        </div>
                    </div>


                    <div class="form-group d-flex mb-0">
                        <div class="form-group col-6">
                            <label>Evaluable</label>
                            <div>
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="flg_evaluable" checked=""
                                        class="custom-switch-input evaluable-status-checkbox">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description txt-evaluable-status">Si</span>
                                </label>
                            </div>
                        </div>
                        <div class="form-group col-6">
                            <label>Grupal</label>
                            <div>
                                <label class="custom-switch mt-2">
                                    <input type="checkbox" name="flg_groupal"
                                        class="custom-switch-input groupal-status-checkbox">
                                    <span class="custom-switch-indicator"></span>
                                    <span class="custom-switch-description txt-groupal-status">No</span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-12 p-0 select-group-container">
                        <label class="form-label">Grupos del evento: &nbsp;</label>
                        <div>
                            <select name="group_participants[]" class="form-control select2 select-group"
                                multiple="multiple" id="group_select_participants">
                                <option disabled></option>
                                @foreach ($event->groupEvent->participantGroups as $group)
                                    <option value="{{ $group->id }}"> {{ $group->id }} -
                                        {{ $group->title }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label>Valor % *</label>
                            <input type="text" name="value" class="form-control value"
                                placeholder="Ingresa el valor">
                        </div>
                    </div>


                    <div class="form-row">
                        <div class="form-group col-12">
                            <label>Fecha limite *</label>
                            <input type="text" name="date_limit" class="form-control datepicker">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group col-12">
                            <label>Archivo (Opcional)</label>
                            <input type="file" multiple name="files[]" class="form-control input-file-folder">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="custom-switch mt-2">
                            <input type="checkbox" name="active" checked=""
                                class="custom-switch-input assignment-status-checkbox">
                            <span class="custom-switch-indicator"></span>
                            <span class="custom-switch-description txt-assignment-description-status">Activo</span>
                        </label>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
                    <button class="btn btn-primary btn-save">
                        Registrar asignación
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                    </button>
                </div>

            </form>

        </div>



    </div>
</div>
