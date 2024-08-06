@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.freeCourseLive.index') }}">
                            <i class="fa-solid fa-circle-chevron-left"></i> Cursos libres en vivo
                        </a>
                        <span> / {{ $event->course->description }} </span> /
                        <a href="{{ route('aula.freeCourseLive.show', $event->course) }}">
                            MENÚ
                        </a>
                        <span> / {{ $event->description }} </span>
                    </h4>
                </div>
            </div>
        </div>

        <div class="card page-title-container">

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
                                <th>Asistencia</th>
                                <th>Perfil</th>
                                <th>Nota</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                    </table>

                </div>

            </div>

        </div>
    </div>
@endsection

@section('modals')
    @include('aula.common.live-free-courses.modals._participant_score')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/aula/js/pages/instructor/freeCourseLive.js') }}"></script>
@endsection
