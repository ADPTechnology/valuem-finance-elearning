@extends('aula.common.layouts.masterpage')

@section('main-content-extra-class', 'fixed-padding')

@section('navbarClass', 'free-course-view')

@section('content')

    <div class="content global-container free-courses" id="chapter-title-head">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4> Cursos libres: {{ $course->description }} </h4>
                </div>
            </div>
        </div>

        <input type="hidden" id="url-input-video" data-id='{{ $current_chapter->id }}' data-time='{{ $current_time }}'
            data-section='{{ $current_chapter->section_id }}'
            data-prodcert='{{ $productCertification->id }}'
            value='{{ route('aula.freecourse.saveTime', $current_chapter) }}'>

        <div class="card-body body-global-container freecourse-view card z-index-2 principal-container">

            <div class="video-container">
                <div class="sub-video-container">
                    <video id="chapter-video" controls preload='auto' class="video-js"
                        data-setup='{
                    "fluid": true,
                    "playbackRates": [0.5, 1, 1.5, 2]
                }'>
                        {{-- <source src="http://commondatastorage.googleapis.com/gtv-videos-bucket/sample/ElephantsDream.mp4"> --}}

                        <source src="{{ verifyFile($current_chapter->file) }}">

                    </video>

                    <div class="video-label-top">
                        {{ $current_section->title }} -
                        {{ $current_chapter->title }}
                    </div>

                    @if ($previous_chapter != null)
                        <div class="btn-previous-chapter-video btn-navigation-chapter">
                            <a class="inner-btn-previous-chapter" href=""
                                onclick="event.preventDefault();
                        document.getElementById('previous-chapter-video-form').submit();">
                                <div class="info-previous-chapter">
                                    <div class="extra-txt-nc mb-1">
                                        Capítulo anterior:
                                    </div>
                                    <div class="txt-title-nc" style="line-height: 1em;">
                                        {{ $previous_chapter->title }}
                                    </div>
                                </div>
                                <i class="fa-solid fa-angles-right fa-flip-horizontal"></i>
                            </a>
                            <form method="POST"
                                action="{{ route('aula.freecourse.update', [$current_chapter, 'new_chapter' => $previous_chapter, $course]) }}"
                                id="previous-chapter-video-form">
                                @method('PATCH')
                                @csrf
                            </form>
                        </div>
                    @endif

                    @php
                        $current_evaluation = $current_section->fcEvaluations->isNotEmpty() ? ($current_section->fcEvaluations->first() ?? null) : null;

                        if ($current_evaluation) {
                            $prodCertificationWithPivot = $current_evaluation->userEvaluations->first() ?? null;
                            $validEvaluation = $prodCertificationWithPivot &&
                                                    ($prodCertificationWithPivot->pivot->status === 'finished') ? true : false;
                        }else {
                            $validEvaluation = true;
                        }

                    @endphp

                    @if (
                        ($next_chapter != null && !$itsLastChapterOfSection) ||
                        ($next_chapter != null && $itsLastChapterOfSection && $validEvaluation)
                    )

                        <div class="btn-next-chapter-video btn-navigation-chapter">
                            <a class="inner-btn-next-chapter" href=""
                                onclick="event.preventDefault();
                        document.getElementById('next-chapter-video-form').submit();">
                                <i class="fa-solid fa-angles-right"></i>
                                <div class="info-next-chapter">
                                    <div class="extra-txt-nc mb-1">
                                        Siguiente capítulo:
                                    </div>
                                    <div class="txt-title-nc" style="line-height: 1em;">
                                        {{ $next_chapter->title }}
                                    </div>
                                </div>
                            </a>
                            <form method="POST"
                                action="{{ route('aula.freecourse.update', [$current_chapter, 'new_chapter' => $next_chapter, $course]) }}"
                                id="next-chapter-video-form">
                                @method('PATCH')
                                @csrf
                            </form>
                        </div>
                    @endif
                </div>


                <div class="card page-title-container free-courses">
                    <div class="card-header chapter-info-box">
                        <div class="total-width-container chapter-title-info">
                            <h4>{{ $current_chapter->title }} </h4>
                        </div>

                        <div class="chapter-desc-info">
                            {{ $current_chapter->description }}
                        </div>

                        @if ($files->isNotEmpty())

                        <div class="mb-4 mt-4">
                            <h4>Recursos:</h4>
                        </div>

                        <div class="resources-cards-container w-100">

                            @foreach ($files as $file)
                                <div class="resources-card">
                                    <a href="{{ route('aula.freecourse.files.download', $file) }}">

                                        <div class="resources-card-body-box">

                                            @php
                                                $svg = getFileExtension($file) . '.svg';
                                            @endphp

                                            <div class="info-container-resources">
                                                <div class="resources-image-cont-box p-3">
                                                    <img src="{{ asset('assets/common/images/file-types/' . $svg) }}">
                                                </div>
                                                <div class="resources-text-cont-box p-3">
                                                    <span class="resources-content-text text-truncate">
                                                        {{ basename($file->file_path) }}
                                                    </span>
                                                    <i class="resources-date-text">
                                                        {{ ucfirst(getDateForHummans($file->created_at)) }}
                                                    </i>
                                                </div>
                                            </div>
                                        </div>
                                    </a>

                                </div>

                                {{--
                                <h4 class="text-center">
                                    Aún no hay recursos
                                    <img src="{{ asset('assets/common/images/emptyfolder.png') }}" alt="">
                                </h4> --}}
                            @endforeach

                        </div>

                        @endif



                        <span id="show-time"></span>
                    </div>
                </div>
            </div>


            <div class="lateral-menu">

                <div class="course-header">

                    <div class="img-container">
                        <img src="{{ verifyImage($course->file) }}" alt="{{ $course->description }}">
                    </div>

                </div>

                <div class="info-head-freecourse">
                    {{ $course->description }}
                </div>

                <div class="accordion" id="lateral-menu-sections">

                    @php
                        $chapter_count = 1;
                    @endphp

                    @foreach ($sections as $section)

                        <div class="card section-accordion">

                            <div class="card-header" id="heading-{{ $section->id }}">

                                <button class="btn btn-link btn-block text-left button-section-tab" type="button"
                                    data-toggle="collapse" data-target="#collapse-{{ $section->id }}"
                                    aria-expanded="false" aria-controls="collapse-{{ $section->id }}">
                                    <div class="info-section-txt">
                                        <span>
                                            {{ $loop->iteration }}. {{ $section->title }}
                                        </span>
                                        <i class="fa-solid fa-chevron-down"></i>
                                    </div>

                                    <div class="info-count">
                                        {{ getNFinishedChapters($section, $allProgress) }}/{{ $section->section_chapters_count }}
                                        | {{ $section->section_chapters_sum_duration + $section->fcEvaluations->sum(function ($evaluation) {
                                            return $evaluation->exam->exam_time;
                                        }) ?? 0 }} min
                                    </div>
                                </button>

                            </div>

                            <div id="collapse-{{ $section->id }}"
                                class="collapse collapse-sections {{ getShowSection($current_section, $section) }}"
                                aria-labelledby="heading-{{ $section->id }}" data-parent="#lateral-menu-sections">

                                @php
                                    $prev_section = $sections->where('section_order', $section->section_order - 1)->first();
                                    $prev_evaluation = $prev_section != null ?
                                                        ($prev_section->fcEvaluations->first() ?? null) :
                                                        null;

                                    $productCertification_id = $productCertification->id;

                                    if ($prev_evaluation) {
                                        $prev_prodCertificationWithPivot = $prev_evaluation->userEvaluations->first() ?? null;
                                        $evaluation_finished = $prev_prodCertificationWithPivot && ($prev_prodCertificationWithPivot->pivot->status === 'finished') ? true : false;
                                    }
                                    else {
                                        $evaluation_finished = true;
                                    }

                                @endphp

                                @if (
                                    $prev_section &&
                                    (($prev_section->section_chapters_count != getFinishedChaptersCountBySection($prev_section)) ||
                                    !$evaluation_finished)
                                )

                                    <div class="invalid-video-start">
                                        <span><i class="fa-solid fa-lock"></i></span>
                                        <p>
                                            Completa el módulo anterior para desbloquear
                                        </p>
                                    </div>

                                @endif

                                @foreach ($section->sectionChapters->sortBy('chapter_order') as $chapter)

                                <div class="card-body @if ($chapter->id == $current_chapter->id) active @endif">

                                    @if ($chapter->id != $current_chapter->id)
                                        <form method="POST"
                                            action="{{ route('aula.freecourse.update', [$current_chapter, 'new_chapter' => $chapter, $course]) }}">
                                            @method('PATCH')
                                            @csrf
                                        @else
                                        <form action=''>
                                    @endif

                                    <button class="btn-next-chapter" type="submit">

                                        <div class="check-chapter-icon" id="check-chapter-icon-{{ $chapter->id }}">
                                            @if (getItsChapterFinished($chapter, $allProgress))
                                                <i class="fa-solid fa-circle-check"></i>
                                            @else
                                                <i class="fa-regular fa-circle"></i>
                                            @endif
                                        </div>

                                        <div>
                                            <div class="chapter-title">
                                                <span><i class="fa-solid fa-circle fa-2xs"></i></span> &nbsp;
                                                <span>{{ $chapter_count }}. </span>
                                                {{ $chapter->title }}
                                            </div>

                                            <div>
                                                <span><i class="fa-solid fa-desktop"></i></span> &nbsp;
                                                {{ $chapter->duration }} min.
                                            </div>
                                        </div>

                                    </button>

                                    </form>

                                </div>
                                @php
                                    $chapter_count++;
                                @endphp
                                @endforeach

                                @if ($section->fcEvaluations->count())
                                    @php
                                        $evaluation = $section->fcEvaluations->first();
                                    @endphp

                                    <div class="card-body card-body-evaluation" onclick="event.preventDefault();">

                                        <button class="no-before" type="">

                                            <div>
                                                <div class="chapter-title">
                                                    <span><i class="fa-solid fa-circle fa-2xs"></i></span> &nbsp;
                                                    {{ $evaluation->title }}
                                                    |
                                                    {{ $evaluation->exam->exam_time ?? 0 }} min.
                                                </div>

                                                <div>
                                                    <span><i class="fa-regular fa-file-lines"></i></span> &nbsp;
                                                    {{ $evaluation->value }}%
                                                </div>
                                                <div>
                                                    {{ $evaluation->description ?? '' }}
                                                </div>

                                                <div class="btn-start-evaluation-container mt-1">

                                                   @include('aula.viewParticipant.freecourses.components._start_evaluation_btn')

                                                </div>

                                            </div>

                                        </button>

                                    </div>
                                @endif


                            </div>
                        </div>

                    @endforeach
                </div>
            </div>

        </div>
    </div>
@endsection

@section('modals')
    <div class="modal fade" id="fcInstructions-modal" tabindex="-1" aria-labelledby="fcInstructions-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="instructionsModalLabel">
                        CARACTERÍSTICAS DE LA EVALUACIÓN
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div id="container-modal-ev">

                </div>

                <div class="modal-footer">

                    <form method="POST" class="fcEvaluation-start-form">
                        @csrf
                        <button type="button" class="btn btn-close" data-dismiss="modal">Cerrar</button>

                        <button type="submit" id="btn-start-evaluation" class="btn btn-send">Comenzar
                            Evaluación</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/aula/js/pages/freeCourses/evaluations.js') }}"></script>
    <script>
        $(".collapse-sections .card-body-evaluation").each(function() {
            let parentPrev = $(this).prev(".card-body");
            let checkIcon = parentPrev.find("button");
            checkIcon.addClass("no-after");
        });
    </script>
@endsection
