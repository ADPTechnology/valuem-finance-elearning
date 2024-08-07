@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>EVALUACIONES</h4>
                    </div>
                </div>
            </div>

            <div id="question-box-container">
            </div>

            <div id="dropdown-questions-create" class="card-body card z-index-2 principal-container">

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>

                    <a href="{{ route('admin.freeCourses.index') }}">Inicio</a> /
                    Categoria:
                    <a href="{{ route('admin.freeCourses.categories.index', $question->exam->course->courseCategory) }}">
                        {{ $question->exam->course->courseCategory->description }}
                    </a>
                    /
                    Curso:
                    <a href="{{ route('admin.freeCourses.courses.index', $question->exam->course) }}">
                        {{ $question->exam->course->description }}
                    </a>
                    /
                    Examen:
                    <a href="{{ route('admin.freeCourses.exams.show', $question->exam) }}">
                        {{ $question->exam->title }}
                    </a>
                    /
                    Enunciado:
                    <span id="question-statement-container">
                        {{ $question->statement }}
                    </span>
                </h5>

                <hr>

                <form id="updateQuestionForm" action="{{ route('admin.freeCourses.exams.questions.update', $question) }}"
                    method="POST" enctype="multipart/form-data">
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
