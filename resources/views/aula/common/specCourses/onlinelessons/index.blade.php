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
                        </a> /
                        CLASE VIRTUAL
                    </h4>
                </div>
            </div>
        </div>


        <div class="card-body body-global-container card z-index-2 principal-container">
            <div class="course-container online-lessons">

                <div class="message-lesson-container">
                    <div class="message-title">
                        Salas Asignadas
                    </div>
                    <div class="message-content">
                        <i class="fa-solid fa-triangle-exclamation"></i> &nbsp;
                        Estimado usuario, si no visualiza el curso deseado, asegúrese de tener una sala asignada.
                    </div>
                </div>


                <div class="rooms-general-container">

                    <div class="room-row-container lessons-table-head">
                        <div class="row-data">
                            Nombre del Evento
                        </div>
                        <div class="row-data">
                            Tipo
                        </div>
                        <div class="row-data">
                            Instructor
                        </div>
                        <div class="row-data">
                            Fecha
                        </div>
                        <div class="row-data">
                            Sala
                        </div>

                    </div>

                    @foreach ($eventGroups as $groupEvent)
                        @foreach ($groupEvent->events as $event)
                            @php
                                $instructor = $event->user;
                            @endphp

                            <div class="room-row-container">
                                <div class="row-data">
                                    {{ $event->description }}
                                </div>
                                <div class="row-data">
                                    {{ config('parameters.event_types')[verifyEventType($event->type)] ?? '-' }}
                                </div>
                                <div class="row-data">
                                    @if (Auth::user()->role === 'participants')
                                        <a href="{{ route('aula.specCourses.instructor.index', ['instructor' => $instructor, 'specCourse' => $specCourse]) }}">
                                            {{ $instructor->full_name }}
                                        </a>
                                    @else
                                        {{ $instructor->full_name }}
                                    @endif
                                </div>
                                <div class="row-data">
                                    {{ $event->date }}
                                </div>

                                <div class="row-data room-link">
                                    <a href="{{ route('aula.specCourses.onlinelesson.show', $event) }}">
                                        <i class="fa-solid fa-chalkboard-user"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    @endforeach


                </div>



            </div>

        </div>

    </div>
@endsection
