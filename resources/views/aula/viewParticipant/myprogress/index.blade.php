@extends('aula.common.layouts.masterpage')

@section('content')

    <div class="content global-container">

        <div class="card page-title-container free-courses">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>MI PROGRESO</h4>
                </div>
            </div>
        </div>

        <div class="card-body body-global-container progress-container card z-index-2 principal-container">

            {{-- @can('denyExternal') --}}
                {{-- ----------- CURSOS REGULARES -------------- --}}

                <div class="progress-section-title-container">
                    Mis Cursos
                </div>

                <div class="course-progress-container mb-6">
                    @forelse ($courses as $course)
                        @php
                            $certifications = getProgressCertificationsFromCourse($course);
                        @endphp

                        <div class="course-box">
                            <div class="course-progress-innerbox">
                                <div class="course-progress-img">
                                    <img src="{{ verifyImage($course->file) }}" alt="">
                                </div>
                                <div class="course-progress-info">
                                    <div class="title">
                                        {{ $course->description }}
                                    </div>
                                    <a class="btn-start" href="{{ route('aula.course.show', $course) }}" class="btn-start">
                                        Ingresar
                                    </a>
                                    <div class="extra-info">
                                        <i class="fa-regular fa-clock"></i> &nbsp;
                                        Duración: {{ $course->hours }} hrs.
                                    </div>
                                </div>
                            </div>

                            <div class="progress-bar-line-box course-progress-assist">
                                <div class="info-progress-txt info-assist">
                                    Tienes {{ $certifications->where('assist_user', 'S')->count() }} de
                                    {{ $certifications->count() }} asistencias
                                </div>
                                <div class="progress-line assist">
                                    @foreach ($certifications as $certification)
                                        @if ($certification->assist_user == 'S')
                                            <div class="progress-bar assist"></div>
                                        @else
                                            <div class="progress-bar no-assist"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="progress-bar-line-box course-progress-evaluations">
                                <div class="info-progress-txt info-evaluations">
                                    @php
                                        $pe_count = $certifications->whereIn('status', ['pending', 'in_progress'])->count();
                                    @endphp
                                    Tienes <span> {{ $pe_count }} </span>
                                    @if ($pe_count == 1)
                                        evaluación pendiente
                                    @else
                                        evaluaciones pendientes
                                    @endif
                                </div>
                                <div class="progress-line assist">
                                    @foreach ($certifications->sortBy('id') as $evaluation)
                                        @if ($evaluation->status == 'finished')
                                            <div class="progress-bar finished"></div>
                                        @elseif(in_array($evaluation->status, ['pending', 'in_progress']))
                                            <div class="progress-bar pending"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div id='chart-{{ $course->id }}' class="course-progress-results"
                                data-approved='{{ $certifications->where('status', 'finished')->filter(function ($certification, $key) use ($course) {
                                        $event = getEventFromCourseAndCertification($course, $certification);
                                        return $certification->score >= $event->min_score;
                                    })->count() }}'
                                data-suspended='{{ $certifications->where('status', 'finished')->filter(function ($certification, $key) use ($course) {
                                        $event = getEventFromCourseAndCertification($course, $certification);
                                        return $certification->score < $event->min_score;
                                    })->count() }}'>
                                <div class="progress-evaluation-title">Resultados</div>
                                <span class="progress-evaluation-subtitle">
                                    @php
                                        $evaluations_count = $certifications->where('status', 'finished')->count();
                                    @endphp
                                    @if ($evaluations_count == 1)
                                        {{ $evaluations_count }} Evaluación total
                                    @else
                                        {{ $evaluations_count }} Evaluaciones totales
                                    @endif
                                </span>
                                <canvas class="canva-progress" id="progress-chart-{{ $course->id }}"></canvas>
                            </div>


                            @if (count($course->courseCertifications))
                                <span class="progress-evaluation-subtitle">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            Descargar certificados
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            @foreach ($course->courseCertifications as $certification)
                                                <a class="dropdown-item"
                                                    href="{{ route('pdf.export.certification', $certification) }}"
                                                    target="_BLANK">
                                                    <img src="{{ asset('assets/certificates/images/certificado.png') }}"
                                                        title="CERTIFICADO" width='25' height='25' />
                                                    Certificado - {{ $certification->event->date }}
                                                </a>
                                            @endforeach

                                        </div>
                                    </div>
                                </span>
                            @endif

                        </div>

                        <hr>

                    @empty
                        <h4 class="text-center empty-records-message"> Aún no tienes Cursos registrados </h4>
                    @endforelse

                </div>

            {{-- @endcan --}}

                {{-- ----------- CURSOS LIBRES -------------- --}}

                <div class="progress-section-title-container">
                    Mis Cursos libres
                </div>

                <div class="course-progress-container mb-6">
                    @forelse ($freeCourses as $freeCourse)
                        @php
                            $totalChapters = $freeCourse->course_chapters_count;
                            $totalEvaluations = $freeCourse->courseEvaluations->count();

                            $completedChapters = getCompletedChapters($freeCourse->courseChapters);
                            $completedEvaluations = getCompletedEvaluations($freeCourse->courseEvaluations);
                        @endphp
                        <div class="course-box">

                            <div class="course-progress-innerbox">
                                <div class="course-progress-img">
                                    <img src="{{ verifyImage($freeCourse->file) }}" alt="">
                                </div>
                                <div class="course-progress-info">
                                    <div class="title">
                                        {{ $freeCourse->description }}
                                    </div>
                                    <a class="btn-start" href=""
                                        onclick="event.preventDefault();
                                        document.getElementById('freecourse-start-form').submit();"
                                        class="btn-start">
                                        Ingresar
                                    </a>
                                    <form id='freecourse-start-form' method="POST"
                                        action="{{ route('aula.freecourse.start', ['course' => $freeCourse]) }}">
                                        @csrf
                                    </form>
                                    <div class="extra-info">
                                        <i class="fa-regular fa-clock"></i> &nbsp;
                                        Duración: {{ $freeCourse->hours }} hrs.
                                    </div>
                                </div>
                            </div>

                            <div class="freecourse-progress-results">

                                <div id='chart-{{ $freeCourse->id }}' class="freecourse-progress-chart-box"
                                    data-total='{{ ($totalChapters + $totalEvaluations)  - ($completedChapters + $completedEvaluations) }}'
                                    data-completed='{{ $completedChapters + $completedEvaluations  }}'>
                                    <canvas class="freecourse-chart" id="freecourse-chart-{{ $freeCourse->id }}"></canvas>
                                </div>

                                <div class="freecourse-progress-percentage-box">
                                    <div class="txt-perc-info">
                                        <span>
                                            {{ round((($completedChapters + $completedEvaluations) / ($totalChapters + $totalEvaluations)) * 100) }}%
                                        </span>
                                        &nbsp; COMPLETADO
                                    </div>
                                </div>
                            </div>


                            <div class="progress-bar-line-box course-progress-assist">
                                <div class="info-progress-txt info-assist">
                                    {{ $completedChapters + $completedEvaluations }} de {{ $totalChapters + $totalEvaluations}} capítulos finalizados
                                </div>
                                <div class="freecourse-progress-line-container">
                                    @for ($i = 0; $i < ($totalChapters + $totalEvaluations); $i++)
                                        @if ($i < ($completedChapters + $completedEvaluations))
                                            <div class="freecourse-progress-line completed">
                                            </div>
                                        @else
                                            <div class="freecourse-progress-line pending">
                                            </div>
                                        @endif
                                    @endfor
                                </div>
                            </div>


                            <div class="specCourse-results">

                                <div class="progress-evaluation-title">
                                    <i class="fa-solid fa-file-signature"></i>&nbsp;
                                    Nota Final
                                </div>

                                <div class="d-flex h-100 mt-1">

                                    <div class="w-100 d-flex flex-column inner-box-spec-results position-relative">

                                        <div class="h-100 d-flex align-items-center justify-content-center spec-points-result">

                                            @if (
                                                $totalEvaluations == $completedEvaluations &&
                                                $totalChapters == $completedChapters
                                            )

                                            {{ $freeCourse->productCertifications->first()->score ?? '-' }}

                                            @else
                                            -
                                            @endif
                                        </div>

                                    </div>
                                </div>

                            </div>


                            @if (
                                ($freeCourse->productCertifications->first()->score ?? 0) >= $freeCourse->min_score
                                &&
                                $totalChapters == $completedChapters
                            )

                            <span class="progress-evaluation-subtitle">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Descargar certificados
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        @foreach ($freeCourse->productCertifications as $certification)
                                            <a class="dropdown-item" href="{{ route('pdf.export.freecoursecertification', $certification) }}" target="_BLANK">
                                                <img src="{{ asset('assets/certificates/images/certificado.png') }}"
                                                    title="CERTIFICADO" width='25' height='25' />
                                                Certificado - {{ formatUpdateAt($certification->updated_at) }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </span>
                            @endif

                        </div>
                    @empty
                        <h4 class="text-center empty-records-message"> Aún no tienes Cursos Libres registrados </h4>
                    @endforelse

                </div>

            {{-- @can('denyExternal') --}}


                {{-- ----------- CURSOS LIBRES EN VIVO -------------- --}}

                <div class="progress-section-title-container">
                    Mis Cursos libres en vivo
                </div>

                <div class="course-progress-container mb-6">
                    @forelse ($liveFreeCourses as $course)
                        @php
                            $certifications = $course->courseCertifications;
                        @endphp

                        <div class="course-box">
                            <div class="course-progress-innerbox">
                                <div class="course-progress-img">
                                    <img src="{{ verifyImage($course->file) }}" alt="">
                                </div>
                                <div class="course-progress-info">
                                    <div class="title">
                                        {{ $course->description }}
                                    </div>
                                    <a class="btn-start" href="{{ route('aula.freeCourseLive.show', $course) }}"
                                        class="btn-start">
                                        Ingresar
                                    </a>
                                    <div class="extra-info">
                                        <i class="fa-regular fa-clock"></i> &nbsp;
                                        Duración: {{ $course->hours }} hrs.
                                    </div>
                                </div>
                            </div>

                            <div class="progress-bar-line-box course-progress-assist">
                                <div class="info-progress-txt info-assist">
                                    Tienes {{ $certifications->where('assist_user', 'S')->count() }} de
                                    {{ $certifications->count() }} asistencias
                                </div>
                                <div class="progress-line assist">
                                    @foreach ($certifications as $certification)
                                        @if ($certification->assist_user == 'S')
                                            <div class="progress-bar assist"></div>
                                        @else
                                            <div class="progress-bar no-assist"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="progress-bar-line-box course-progress-evaluations">
                                <div class="info-progress-txt info-evaluations">
                                    @php
                                        $pe_count = $certifications->whereIn('status', ['pending', 'in_progress'])->count();
                                    @endphp
                                    Tienes <span> {{ $pe_count }} </span>
                                    @if ($pe_count == 1)
                                        evaluación pendiente
                                    @else
                                        evaluaciones pendientes
                                    @endif
                                </div>
                                <div class="progress-line assist">
                                    @foreach ($certifications->sortBy('id') as $evaluation)
                                        @if ($evaluation->status == 'finished')
                                            <div class="progress-bar finished"></div>
                                        @elseif(in_array($evaluation->status, ['pending', 'in_progress']))
                                            <div class="progress-bar pending"></div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            @php
                                $certificationsApp = getApprSuspFclCertifications($certifications, 'approved');
                                $certificationsSusp = getApprSuspFclCertifications($certifications, 'suspended');
                            @endphp

                            <div id='chart-{{ $course->id }}' class="course-progress-results"
                                data-approved='{{ $certificationsApp->count() }}'
                                data-suspended='{{ $certificationsSusp->count() }}'>
                                <div class="progress-evaluation-title">Resultados</div>
                                <span class="progress-evaluation-subtitle">
                                    @php
                                        $evaluations_count = $certifications->where('status', 'finished')->count();
                                    @endphp
                                    @if ($evaluations_count == 1)
                                        {{ $evaluations_count }} Evaluación total
                                    @else
                                        {{ $evaluations_count }} Evaluaciones totales
                                    @endif
                                </span>
                                <canvas class="canva-progress" id="progress-chart-{{ $course->id }}"></canvas>
                            </div>

                            @if ($certificationsApp->count() > 0)
                                <span class="progress-evaluation-subtitle">
                                    <div class="dropdown">
                                        <button class="btn btn-primary dropdown-toggle" type="button"
                                            id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Descargar certificados
                                        </button>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                            @foreach ($course->courseCertifications as $certification)
                                                <a class="dropdown-item"
                                                    href="{{ route('pdf.export.certification', $certification) }}"
                                                    target="_BLANK">
                                                    <img src="{{ asset('assets/certificates/images/certificado.png') }}"
                                                        title="CERTIFICADO" width='25' height='25' />
                                                    Certificado - {{ $certification->event->date }}
                                                </a>
                                            @endforeach

                                        </div>
                                    </div>
                                </span>
                            @endif

                        </div>

                        <hr>

                    @empty
                        <h4 class="text-center empty-records-message"> Aún no tienes Cursos libres en vivo registrados </h4>
                    @endforelse

                </div>

                {{-- ----------- CURSOS DE ESPECIALIZACION -------------- --}}

                <div class="progress-section-title-container">
                    Mis Cursos de Especialización
                </div>

                <div class="course-progress-container mb-6">
                    @forelse ($specCourses as $specCourse)
                        <div class="course-box">

                            <div class="course-progress-innerbox">
                                <div class="course-progress-img">
                                    <img src="{{ verifyImage($specCourse->file) }}" alt="">
                                </div>
                                <div class="course-progress-info pb-3">
                                    <div class="title">
                                        {{ $specCourse->title }}
                                    </div>
                                    <a class="btn-start" href="{{ route('aula.specCourses.show', $specCourse) }}"
                                        class="btn-start">
                                        Ingresar
                                    </a>
                                </div>
                            </div>


                            <div class="freecourse-progress-results">

                                @php
                                    $specTotalEvents = $specCourse->total_events_count;
                                    $specCompletedEvents = $specCourse->completed_events_count;
                                @endphp

                                <div id='chart-{{ $specCourse->id }}' class="freecourse-progress-chart-box"
                                    data-total='{{ $specTotalEvents - $specCompletedEvents }}'
                                    data-completed='{{ $specCompletedEvents }}'>
                                    <canvas class="freecourse-chart" id="freecourse-chart-{{ $specCourse->id }}"></canvas>
                                </div>

                                <div class="freecourse-progress-percentage-box">
                                    <div class="txt-perc-info">
                                        <span>
                                            @if (in_array(0, [$specTotalEvents]))
                                                0%
                                            @else
                                                {{ round(($specCompletedEvents / $specTotalEvents) * 100) }}%
                                            @endif
                                        </span>
                                        &nbsp; COMPLETADO
                                    </div>
                                </div>

                            </div>

                            @php
                                $assignmentScore = getSpecCourseAssignmentsScore($specCourse);
                                $evaluationScore = getSpecCourseEvaluationScore($specCourse);
                                $finalScore = ($assignmentScore + $evaluationScore) / 2;
                            @endphp


                            <div class="specCourse-results">
                                <div class="progress-evaluation-title">Resultados</div>

                                <div class="d-flex h-100 mt-1">
                                    <div class="w-100 d-flex flex-column inner-box-spec-results position-relative">
                                        <div class="title-spec-result">
                                            Nota de Asignaciones
                                        </div>
                                        <div class="h-100 d-flex align-items-center justify-content-center spec-points-result">
                                            {{ $assignmentScore }}
                                        </div>
                                    </div>
                                    <div class="w-100 d-flex flex-column inner-box-spec-results position-relative">
                                        <div class="title-spec-result">
                                            Nota de Evaluaciones
                                        </div>
                                        <div class="h-100 d-flex align-items-center justify-content-center spec-points-result">

                                            {{ $evaluationScore }}

                                        </div>
                                    </div>

                                    <div class="w-100 d-flex flex-column inner-box-spec-results position-relative">
                                        <div class="title-spec-result">
                                            Nota Final
                                        </div>
                                        <div class="h-100 d-flex align-items-center justify-content-center spec-points-result">

                                            {{ $finalScore }}

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        @if ($finalScore >= 14)
                            <span class="progress-evaluation-subtitle d-flex justify-content-center">
                                <div class="dropdown">
                                    <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Descargar certificados
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                        <a class="dropdown-item" href="" target="_BLANK">
                                            <img src="{{ asset('assets/certificates/images/certificado.png') }}"
                                                title="CERTIFICADO" width='25' height='25' />
                                            Certificado -
                                        </a>

                                    </div>
                                </div>
                            </span>
                        @endif

                        <hr>

                    @empty
                        <h4 class="text-center empty-records-message"> Aún no tienes Cursos de especialización asignados. </h4>
                    @endforelse
                </div>

            {{-- @endcan --}}

            {{-- ------------- WEBINAR -------------- --}}

            <div class="progress-section-title-container">
                Mis Webinars
            </div>

            <div class="course-progress-container mb-6">
                @forelse ($webinars as $webinar)
                    <div class="course-box">

                        <div class="course-progress-innerbox">
                            <div class="course-progress-img">
                                <img src="{{ verifyImage($webinar->file) }}" alt="">
                            </div>
                            <div class="course-progress-info pb-3">
                                <div class="title">
                                    {{ $webinar->title }}
                                </div>
                                <a class="btn-start" href="{{ route('aula.webinar.show', $webinar) }}"
                                    class="btn-start">
                                    Ingresar
                                </a>
                            </div>
                        </div>


                        <div class="freecourse-progress-results">

                            @php

                                $webinarEventsTotal = $webinar->total_events_count;
                                $webinarCompletedEvents = $webinar->completed_events_count;

                            @endphp

                            <div id='chart-{{ $webinar->id }}' class="webinar-progress-chart-box"
                                data-total='{{ $webinarEventsTotal - $webinarCompletedEvents }}'
                                data-completed='{{ $webinarCompletedEvents }}'>
                                <canvas class="freecourse-chart" id="webinar-chart-{{ $webinar->id }}">
                                </canvas>
                            </div>

                            <div class="freecourse-progress-percentage-box">
                                <div class="txt-perc-info">
                                    <span>
                                        @if (in_array(0, [$webinarEventsTotal]))
                                            0%
                                        @else
                                            {{ round(($webinarCompletedEvents / $webinarEventsTotal) * 100) }}%
                                        @endif
                                    </span>
                                    &nbsp; COMPLETADO
                                </div>
                            </div>

                        </div>

                    </div>

                    @php
                        $unlockCertifications = $webinar->events
                            ->map(function ($event) {
                                return $event->certifications->filter(function ($certification) {
                                    return $certification->unlock_cert == 'S';
                                });
                            })
                            ->flatten();

                    @endphp

                    @if ($unlockCertifications->count() > 0)
                        <span class="progress-evaluation-subtitle d-flex justify-content-center">
                            <div class="dropdown">
                                <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Descargar certificados
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">

                                    @foreach ($unlockCertifications as $certification)
                                        <a class="dropdown-item"
                                            href="{{ route('pdf.export.ext_certification', $certification) }}"
                                            target="_BLANK">
                                            <img src="{{ asset('assets/certificates/images/certificado.png') }}"
                                                title="CERTIFICADO" width='25' height='25' />
                                            Certificado - {{ $certification->event->date }}
                                        </a>
                                    @endforeach


                                </div>
                            </div>
                        </span>
                    @endif

                    <hr>

                @empty
                    <h4 class="text-center empty-records-message"> Aún no tienes Webinars asignados. </h4>
                @endforelse
            </div>

        </div>

    </div>


@endsection

@section('extra-script')
    <script src="{{ asset('assets/aula/js/charts.js') }}"></script>
@endsection
