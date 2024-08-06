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
                        <span> / {{ $webinar->title }} </span> /
                        <a href="{{ route('aula.webinar.show', $webinar) }}">
                            MENÚ
                        </a> /
                        CLASE VIRTUAL
                    </h4>
                </div>
            </div>
        </div>


        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.webinar.onlinelesson.index', $webinar) }}">
                            CLASE VIRTUAL
                        </a> / INFORMACIÓN DEL INSTRUCTOR
                    </h4>
                </div>
            </div>
        </div>


        <div class="card-body body-global-container card z-index-2 principal-container">


            @php
                $userDetail = $instructor->userDetail;
            @endphp


            {{-- * information --}}

            <div class="information-instructor-container">

                @include('aula.common.profile.partials.boxes._profile_information')

            </div>


            <hr>

            {{-- * events --}}

            <div class="description-events-course">
                Eventos del webinar: <strong>{{ $webinar->description }}</strong> pertenecientes al instructor
            </div>

            <div class="course-container online-lessons">

                <div class="rooms-general-container">

                    <div class="room-row-container lessons-table-head">
                        <div class="row-data">
                            Nombre del Evento
                        </div>
                        <div class="row-data">
                            Instructor
                        </div>
                        <div class="row-data">
                            Hora de inicio
                        </div>
                        <div class="row-data">
                            Fecha
                        </div>
                        <div class="row-data">
                            Sala
                        </div>

                    </div>

                    @foreach ($events as $event)
                        @php
                            $instructor = $event->instructor;
                        @endphp

                        <div class="room-row-container">
                            <div class="row-data">
                                {{ $event->description }}
                            </div>

                            <div class="row-data">
                                {{ $instructor->full_name }}
                            </div>
                            <div class="row-data">
                                {{ $event->time_start }}
                            </div>
                            <div class="row-data">
                                {{ $event->date }}
                            </div>

                            @php
                                $pastDateAndTime = checkPastDateTime($event->date, $event->time_start);
                            @endphp

                            <div class="row-data room-link">
                                @if ($user->role == 'participants')
                                    @if ($pastDateAndTime)
                                        <a href="{{ route('aula.webinar.onlinelesson.show', $event) }}">
                                            <i class="fa-solid fa-chalkboard-user"></i>
                                        </a>
                                    @else
                                        <span>
                                            <i class="fa-solid fa-circle-exclamation"></i> &nbsp;
                                            Espere la fecha y hora de inicio
                                        </span>
                                    @endif
                                @else
                                    <a href="{{ route('aula.webinar.onlinelesson.show', $event) }}">
                                        <i class="fa-solid fa-chalkboard-user"></i>
                                    </a>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        </div>

    </div>
@endsection
