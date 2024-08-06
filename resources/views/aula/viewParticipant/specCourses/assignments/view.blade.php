@extends('aula.common.layouts.masterpage')

@section('extra-head')
    <link rel="stylesheet" href="{{ asset('assets/common/modules/summernote/summernote-bs4.css') }}">
@endsection

@section('navbar-extra-content')
    <nav class="navbar navbar-expand-lg main-navbar @yield('navbarClass')">
        <form class="form-inline mr-auto">
            <ul class="navbar-nav mr-3" style="display: block">
                <li>
                    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <div class="navbar-container-elearning">
                <div class="my-courses">
                    MIS ASIGNACIONES
                </div>
                <span class="hashtag">
                    #HAMAVirtual
                </span>
            </div>
        </form>
    </nav>
@endsection

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.specCourses.index') }}">
                            <i class="fa-solid fa-circle-chevron-left"></i> Cursos de especialización
                        </a>
                        <span> / {{ $event->groupEvent->specCourse->title }} </span> /
                        <a href="{{ route('aula.specCourses.show', $event->specCourse) }}">
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
                        <a href="{{ route('aula.specCourses.assignment.index', $event->groupEvent->specCourse) }}">Grupos de
                            eventos</a> /
                        <span> {{ $event->groupEvent->title }}</span>
                        /
                        <span>{{ $event->description }}</span>
                        / Asignaciones
                    </h4>
                </div>
            </div>
        </div>

        <div class="mt-3 card-body body-global-container card z-index-2 principal-container">


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

                    <div id="assignments-global-container" class="related-dropdown-container assignments-global-container">

                        <div class="assignments-list-container w-25 p-2">

                            @forelse ($assignments as $assignment)
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
                                        <span class="font-weight-bold font-italic" style="font-weight: 300">
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
                                        <span class="inner-btn-view"
                                            data-url="{{ route('aula.specCourses.assignment.showAssignmentInfo', $assignment) }}">
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
                                Selecciona una asignación para mostrar su información.
                            </div>

                        </div>


                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('aula.viewParticipant.specCourses.assignments.components._view_assignable')
@endsection

@section('extra-script')
    <script src="{{ asset('assets/common/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/common/modules/summernote/lang/summernote-es-ES.js') }}"></script>
    <script type="module" src="{{ asset('assets/aula/js/pages/participant/assignment/assignments.js') }}"></script>
@endsection
