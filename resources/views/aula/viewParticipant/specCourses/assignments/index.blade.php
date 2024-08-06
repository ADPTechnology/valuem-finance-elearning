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
                        <span> / {{ $specCourse->title }} </span> /
                        <a href="{{ route('aula.specCourses.show', $specCourse) }}">
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
                        Grupos de eventos:
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
                                Grupos de eventos
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

                            @forelse ($groupEvents as $group)
                                <div class="assignment-box">

                                    <div class="assign__title d-flex flex-column">
                                        <span class="little-text">
                                            Titulo:
                                        </span>
                                        <span class="font-weight-bold">
                                            {{ $group->title }}
                                        </span>
                                    </div>

                                    <div class="assign__value d-flex flex-column">
                                        <span class="little-text">
                                            Descripción:
                                        </span>
                                        <span class="font-weight-bold">
                                            {{ $group->description ?? '-' }}
                                        </span>
                                    </div>

                                    <div class="assign__status d-flex flex-column">
                                        <span class="status @if ($group->active === 'S') active @endif">
                                            {{ $group->active === 'S' ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </div>

                                    <div class="assign-btn-view d-flex">
                                        <span class="inner-btn-view"
                                            data-url="{{ route('aula.specCourses.assignment.showEventList', $group) }}">
                                            <i class="fa-solid fa-chevron-right fa-lg"></i>
                                        </span>
                                    </div>

                                </div>
                            @empty
                                <h4 class="text-center empty-records-message">
                                    No hay grupos de eventos para mostrar
                                </h4>
                            @endforelse
                        </div>
                        <span class="separator-assignments-container">
                        </span>


                        <div class="participants_assign_container w-75">

                            <div class="emptyparticipants_assign_message p-4">

                                <i class="fa-solid fa-circle-exclamation"></i>&nbsp;
                                Selecciona un grupo para mostrar sus eventos.
                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection


@section('extra-script')
    <script type="module" src="{{ asset('assets/aula/js/pages/participant/groupEvents.js') }}"></script>
@endsection
