@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>Cursos de Especialización</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.specCourses.index') }}">Inicio</a>
                    / Curso:
                    <a href="{{ route('admin.specCourses.show', ['specCourse' => $event->courseModule->specCourse]) }}">
                        {{ $event->courseModule->specCourse->title }}
                    </a>
                    /Grupo de evento:
                    <a href="{{ route('admin.specCourses.groupEvents.show', $event->groupEvent) }}">
                        {{ $event->groupEvent->title }}
                    </a>
                    / Evento:
                    <span id="event-description-text-principal" class="to-capitalize">
                        {{ mb_strtolower($event->description, 'UTF-8') }}
                    </span>
                </h5>


                <div id="event-box-container" class="info-element-box event-box mt-4 mb-3">

                    @include('admin.specCourses.partials.components._event_box')

                </div>

                <h5 class="title-header-show mb-4 mt-4"> Tareas y Asignaciones: </h5>

                <div class="mb-4">
                    <button type="button" class="btn btn-primary" data-toggle="modal"
                        data-target="#registerAssignmentModal">
                        <i class="fa-solid fa-square-plus"></i> &nbsp; Crear nueva tarea o asignación
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                    </button>
                </div>

                <table id="assignments-table" class="table table-hover"
                    data-url="{{ route('admin.specCourses.events.assignments.getDataTable', $event) }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Titulo</th>
                            <th>Descripción</th>
                            <th>Valor</th>
                            <th>Grupal</th>
                            <th>Evaluable</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>


                <h5 class="title-header-show mb-4 mt-4"> Lista de participantes: </h5>

                <div class="mb-4">
                    <button class="btn btn-primary" id="btn-register-participant-modal" data-url="">
                        <i class="fa-solid fa-square-plus"></i> &nbsp; Registrar participantes
                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                    </button>

                    <button class="btn btn-primary ms-3" id="btn-register-massive-participant-modal" data-toggle="modal"
                        data-target="#RegisterParticipantsMassiveModal">
                        <i class="fa-solid fa-file-import"></i> &nbsp; Registro masivo
                    </button>

                    <button class="btn btn-primary ms-3" id="btn-register-participants-score-modal" data-toggle="modal"
                        data-target="#RegisterParticipantsScoreModal">
                        <i class="fa-solid fa-file-import"></i> &nbsp; Subir notas
                    </button>

                    <button class="btn btn-primary ms-3" id="btn-register-area-observations" data-toggle="modal"
                        data-target="#RegisterParticipantsAreaModal">
                        <i class="fa-solid fa-file-import"></i> &nbsp; Área / Observaciones
                    </button>
                </div>

                <div class="group-filter-buttons-section flex-wrap">
                    <div class="form-group col-2 p-0 select-group">
                        <label class="form-label">Filtrar por estado: &nbsp;</label>
                        <div>
                            <select name="status" class="form-control select2 select-filter-certifications"
                                id="search_from_status_select">
                                <option value="">Todos</option>
                                <option value="approved">Aprobados</option>
                                <option value="suspended">Desaprobados</option>
                            </select>
                        </div>
                    </div>

                </div>

                <table id="certifications-table" class="table table-hover"
                    data-url="{{ route('admin.specCourses.events.show', $event) }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>DNI</th>
                            <th>Participante</th>
                            <th>Empresa</th>
                            <th>Nota</th>
                            <th>Estado</th>
                            <th>Habilitado</th>
                            <th>Asistencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>

            </div>

        </div>

    </div>
@endsection

@section('modals')
    @include('admin.events.partials._modal_edit_event', ['show' => 'specCourse'])
    @include('admin.events.partials._modal_store_participant')
    @include('admin.events.partials._modal_show_certification')
    @include('admin.events.partials._modal_edit_certification')

    @include('admin.events.partials.modals._register_participants_massive')
    @include('admin.events.partials.modals._register_participants_score')
    @include('admin.events.partials.modals._register_participants_area')

    @include('admin.events.partials.modals._register_assignment')
    @include('admin.events.partials.modals._edit_assignment')

    @include('admin.companies.partials.modals._viewDocs')

@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/spec-courses.js') }}"></script>
    <script type="module" src="{{ asset('assets/admin/js/assignment.js') }}"></script>
@endsection
