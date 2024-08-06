@extends('aula.common.layouts.masterpage')

@php
    $specCourse = $groupEvent->specCourse;
@endphp

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">

            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.specCourses.index') }}">
                            <i class="fa-solid fa-circle-chevron-left"></i> Cursos de especialización
                        </a>
                        <span> / {{ $specCourse->title }} </span> /
                        <a href="{{ route('aula.specCourses.show', $specCourse) }}">
                            MENÚ
                        </a>
                    </h4>
                </div>
            </div>


            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        {{ $groupEvent->title }} /
                        Módulos
                    </h4>
                </div>
            </div>

        </div>

        <div class="card-body body-global-container card z-index-2 principal-container">

            @foreach ($specCourse->modules as $module)
                <div class="principal-splitted-container mt-1 mb-5">

                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">
                            <div id="" class="btn-dropdown-container show">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-layer-group not-rotate"></i> &nbsp;
                                    {{ $module->title }}: Eventos
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


                        <div id="events-list-container" class="related-dropdown-container" style="">

                            @if ($module->files_count != 0)
                                <div class="view-module-btn-cont mb-4">
                                    <button class="btn btn-primary btn-view-modules-files"
                                        data-url="{{ route('aula.specCourses.getModuleFiles', $module) }}">
                                        <i class="fa-solid fa-folder-open me-2"></i>
                                        Ver Archivos
                                    </button>
                                </div>
                            @endif


                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Código</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Fecha</th>
                                        <th>Sala</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($module->events as $event)
                                        <tr>
                                            <td scope="row">
                                                {{ $event->id }}
                                            </td>
                                            <td>
                                                <a href="{{ route('aula.specCourses.modules.showParticipants', $event) }}">
                                                    {{ $event->description }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ config('parameters.event_types')[verifyEventType($event->type)] ?? '-' }}
                                            </td>
                                            <td>
                                                {{ $event->date }}
                                            </td>
                                            <td>
                                                <a href="{{ route('aula.specCourses.onlinelesson.show', $event) }}">
                                                    {{ $event->room->description }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>
            @endforeach


        </div>

    </div>
@endsection

@section('modals')
    @include('aula.common.specCourses.modules.partials.modals._view_files')
@endsection

@section('extra-script')
    <script src="{{ asset('assets/aula/js/specCourses.js') }}"></script>
@endsection
