@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>ENUNCIADO</h4>
                    </div>
                </div>
            </div>

            <div id="question-box-container">
            </div>

            <div id="dropdown-questions-create" class="card-body card z-index-2 principal-container">

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.forgettingCurve.index') }}">Inicio</a> /
                    <a
                        href="{{ route('admin.forgettingCurve.show', $question->fcVideo->step->instance->forgettingCurve) }}">
                        {{ $question->fcVideo->step->instance->forgettingCurve->title }} </a> /
                    <span>{{ $question->fcVideo->step->instance->title }}</span> /
                    <span>{{ $question->fcVideo->step->title }}</span>
                    /
                    <a href="{{ route('admin.forgettingCurve.steps.show', $question->fcVideo->step) }}"
                        class="to-capitalize">
                        Video
                    </a>
                    / Enunciado:
                    <span id="question-statement-container">
                        {{ $question->statement }}
                    </span>
                </h5>

                <hr>

                <form id="updateQuestionForm"
                    action="{{ route('admin.forgettingCurve.steps.video.questionVideo.update', $question) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div id="question-type-container">

                        @if ($question->question_type_id == 1)
                            @include('admin.exams.partials.questionTypes.unique_answer')
                        @elseif($question->question_type_id == 2)
                            @include('admin.exams.partials.questionTypes.multiple_answer')
                        @elseif($question->question_type_id == 3)
                            @include('admin.exams.partials.questionTypes.true_false')
                        @elseif($question->question_type_id == 4)
                            @include('admin.exams.partials.questionTypes.fill_in_the_blank')
                        @elseif($question->question_type_id == 5)
                            @include('admin.exams.partials.questionTypes.matching')
                        @endif

                    </div>

                </form>

                <hr>

            </div>

        </div>

    </div>
@endsection
