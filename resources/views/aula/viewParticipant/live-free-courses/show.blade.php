@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.freeCourseLive.index') }} ">
                            <i class="fa-solid fa-circle-chevron-left"></i> Cursos libre en vivo
                        </a>
                        <span> / {{ $course->description }} </span> / MENÚ
                    </h4>
                </div>
            </div>
        </div>

        <div class="card-body body-global-container card z-index-2 principal-container">


            <div class="row navigation-boxes-container">


                <a href="{{ route('aula.freeCourseLive.onlinelesson.index', $course) }}" class="link-box-navigation-course">
                    <div class="navigation-box online-lesson card">
                        <div class="img-container">
                            <img src="{{ asset('assets/aula/img/courses/online-lesson.png') }}" alt="">
                        </div>
                        <div class="text-nav-container">
                            <span>
                                Clase Virtual
                            </span>
                            <span class="bg-nav-course-box"></span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('aula.freeCourseLive.resources.index', $course) }}" class="link-box-navigation-course">
                    <div class="navigation-box online-lesson card">
                        <div class="img-container">
                            <img src="{{ asset('assets/aula/img/courses/content.png') }}" alt="">
                        </div>
                        <div class="text-nav-container">
                            <span>
                                Recursos
                            </span>
                            <span class="bg-nav-course-box"></span>
                        </div>
                    </div>
                </a>

                @can('denyInstructor')
                    <a href="{{ route('aula.freeCourseLive.evaluations.index', $course) }}" class="link-box-navigation-course">
                        <div class="navigation-box evaluation card">
                            <div class="img-container">
                                <img src="{{ asset('assets/aula/img/courses/quiz.png') }}" alt="">
                            </div>
                            <div class="text-nav-container">
                                <span>
                                    Evaluaciones
                                </span>
                                <span class="bg-nav-course-box"></span>
                            </div>
                        </div>
                    </a>
                @endcan
            </div>

            @can('allowInstructor')
                <div class="principal-splitted-container mt-1 mb-2">

                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">
                            <div id="" class="btn-dropdown-container show">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-layer-group not-rotate"></i> &nbsp;
                                    Eventos
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

                        <div id="groups-list-container" class="related-dropdown-container" style="">

                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Código</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Sala</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @foreach ($course->events as $event)
                                        <tr>
                                            <th scope="row">{{ $event->id }}</th>
                                            <td>
                                                <a href="{{ route('aula.freeCourseLive.events.show', $event) }}">
                                                    {{ $event->description }}
                                                </a>
                                            </td>
                                            <td> {{ config('parameters.event_types')[$event->type] }} </td>
                                            <td> {{ $event->date }} </td>
                                            <td>
                                                <a href="{{ route('aula.freeCourseLive.onlinelesson.show', $event) }}">
                                                    {{ $event->room->description }} </a>
                                            </td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>

                        </div>

                    </div>

                </div>
            @endcan

        </div>

    </div>
@endsection
