@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.webinar.index') }}">
                            <i class="fa-solid fa-circle-chevron-left"></i> Webinars
                        </a>
                        <span> / {{ $event->webinar->title }} </span> /
                        <a href="{{ route('aula.webinar.show', $event->webinar) }}">
                            MENÚ
                        </a>
                        <span> / {{ $event->description }} </span>
                    </h4>
                </div>
            </div>
        </div>

        <div class="card page-title-container">
            {{-- <div class="card-header">
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
        </div> --}}

            <div class="card-body body-global-container card z-index-2 principal-container">

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
                                <th>Perfil</th>
                            </tr>
                        </thead>
                    </table>

                </div>

            </div>

        </div>
    @endsection

    @section('modals')
        {{-- @include('aula.instructor.specCourses.groupEvents.partials.modals._participants_assign_view') --}}
    @endsection

    @section('extra-script')
        {{-- <script type="module" src="{{ asset('assets/aula/js/pages/spec_modules.js') }}"></script> --}}
        {{-- <script type="module" src="{{ asset('assets/aula/js/pages/instructor/sc_event.js') }}"></script> --}}
        <script type="module" src="{{ asset('assets/aula/js/pages/instructor/webinar.js') }}"></script>
    @endsection
