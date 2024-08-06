@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>Webinars</h4>
                </div>
            </div>
        </div>

        <div class="card-body body-global-container card z-index-2 principal-container">

            <div class="info-count-courses">
                Hay <span> {{ count($webinars) }} </span> webinar(s) en total
            </div>

            <div class="courses-cards-container">

                @forelse($webinars as $webinar)
                    @php
                        $instructors = getWebinarInstructors($webinar);
                    @endphp

                    <div class="card course-card">

                        @if ($webinar->events_max_date == getCurrentDate())
                            <div class="disclaimer-current-event">
                                <span class="text-white font-italic">
                                    <i class="fa-regular fa-calendar-check me-2"></i>
                                    Tiene eventos programados para hoy
                                </span>
                            </div>
                        @endif

                        <div class="course-img-container">
                            <img class="card-img-top course-img" src="{{ verifyImage($webinar->file) }}"
                                alt="Card image cap">
                        </div>

                        <div class="card-body">

                            <div class="start-button-container">
                                <a href="{{ route('aula.webinar.show', $webinar) }}">
                                    Ingresar &nbsp;
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            </div>

                            <div class="course-title-box">
                                {{ $webinar->title }}
                            </div>

                            <div class="instructor-name-box">
                                <div class="instructor-icon">
                                    <i class="fa-solid fa-user-tie"></i>
                                </div>
                                <div class="instructor-name">
                                    @foreach ($instructors as $instructor)
                                        <div>
                                            {{ strtolower($instructor->full_name) }}
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="course-info-box">
                                <div class="students-box">
                                    <i class="fa-solid fa-graduation-cap"></i>
                                    {{ $webinar->participants_count }} Estudiantes
                                </div>
                            </div>

                        </div>

                    </div>

                @empty

                    <h4 class="text-center empty-records-message"> No hay webinars que mostrar aún </h4>
                @endforelse
            </div>


        </div>

    </div>
@endsection
