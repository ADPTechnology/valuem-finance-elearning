@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.specCourses.index') }}">
                            <i class="fa-solid fa-circle-chevron-left"></i> Cursos de especialización
                        </a>
                        <span> / {{ $event->specCourse->title }} </span> /
                        <a href="{{ route('aula.specCourses.show', ['specCourse' => $event->specCourse]) }}">
                            MENÚ
                        </a>
                    </h4>
                </div>
            </div>
        </div>

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.specCourses.showGroupEvent', ['groupEvent' => $event->groupEvent]) }}">
                            {{ $event->groupEvent->title }}
                        </a>
                        /
                        Módulo: {{ $event->courseModule->title }} /
                        Evento: {{ $event->description }}
                    </h4>
                </div>
            </div>
        </div>


        <div class="card-body body-global-container card z-index-2 principal-container">

            <div class="principal-splitted-container mt-1 mb-5">

                <div class="principal-inner-container total-width">

                    <div class="inner-title-container">
                        <div id="" class="btn-dropdown-container show">
                            <h5 class="title-header-show">
                                <i class="fa-solid fa-book not-rotate"></i> &nbsp;
                                Asignaciones
                            </h5>

                            <div class="btn-row-container">
                                <div>
                                    <span class="text-dropdown-cont">
                                        Ocultar
                                    </span>
                                    <i class="fa-solid fa-chevron-down ms-2"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="assignments-global-container" class="related-dropdown-container assignments-global-container"
                        style="">

                        <div class="assignments-list-container w-25 p-2">

                            @forelse ($event->assignments as $assignment)
                                <div class="assignment-box">

                                    <div class="assign__title d-flex flex-column">
                                        <span class="little-text">
                                            Titulo:
                                        </span>
                                        <span class="font-weight-bold">
                                            {{ $assignment->title }}
                                        </span>
                                    </div>

                                    <div class="assign__value d-flex flex-column">
                                        <span class="little-text">
                                            Valor:
                                        </span>
                                        <span class="font-weight-bold">
                                            {{ $assignment->value }}%
                                        </span>
                                    </div>

                                    <div class="assign__value d-flex flex-column">
                                        <span class="little-text">
                                            Fecha límite:
                                        </span>
                                        <span class="font-weight-bold">
                                            {{ $assignment->date_limit }}
                                        </span>
                                    </div>

                                    <div class="assign__status d-flex flex-column">
                                        <div class="status-gp_ind">
                                            {{ getAssignmentType($assignment->flg_groupal) }}
                                        </div>
                                        <div class="status_evl @if ($assignment->flg_evaluable == 1) active @endif">
                                            {{ getAssignmentStatus($assignment->flg_evaluable) }}
                                        </div>

                                    </div>

                                    <div class="assign-btn-view d-flex">
                                        <span class="inner-btn-view" data-url="{{ route('aula.specCourses.assignments.getAssignablesList', $assignment) }}">
                                            <i class="fa-solid fa-chevron-right fa-lg"></i>
                                        </span>
                                    </div>

                                </div>

                            @empty

                            <h4 class="text-center empty-records-message">
                                No hay asignaciones registradas
                            </h4>

                            @endforelse

                        </div>

                        <span class="separator-assignments-container">
                        </span>


                        <div class="participants_assign_container w-75">

                            <div class="emptyparticipants_assign_message p-4">

                                <i class="fa-solid fa-circle-exclamation"></i>&nbsp;
                                Selecciona una asignación para mostrar los participantes.
                            </div>

                        </div>


                    </div>

                </div>

            </div>


            <div class="course-container">

                <div class="card page-title-container mb-4">
                    <div class="card-header pl-0">
                        <div class="total-width-container">
                            <h4>
                                Participantes
                            </h4>
                        </div>
                    </div>
                </div>

                <table id="participants-table" class="table table-hover" data-url="">
                    <thead>
                        <tr>
                            <th>Cod. Certificado</th>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Apellido Paterno</th>
                            <th>Apellido Materno</th>
                            <th>Estado</th>
                            <th>Asistencia</th>
                            <th>Perfil</th>
                        </tr>
                    </thead>
                </table>

            </div>

        </div>

    </div>
@endsection

@section('modals')
@include('aula.instructor.specCourses.groupEvents.partials.modals._participants_assign_view')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/aula/js/pages/spec_modules.js') }}"></script>
    <script type="module" src="{{ asset('assets/aula/js/pages/instructor/sc_event.js') }}"></script>
@endsection

