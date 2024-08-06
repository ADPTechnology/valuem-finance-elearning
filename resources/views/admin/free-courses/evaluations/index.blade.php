@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>CURSOS LIBRES</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.freeCourses.index') }}">Inicio</a>
                    / Categoría:
                    <a href="{{ route('admin.freeCourses.categories.index', ['category' => $course->courseCategory]) }}"
                        class="to-capitalize">
                        {{ mb_strtolower($course->courseCategory->description, 'UTF-8') }}</a>
                    / Curso:
                    <a href="{{ route('admin.freeCourses.courses.index', $course) }}">
                        {{ mb_strtolower($course->description, 'UTF-8') }} </a>
                    / <span class="to-capitalize fcEvaluationTitle">
                        {{ $fcEvaluation->title }}
                    </span>

                </h5>


                <div id="free-course-exam-box-container" class="info-element-box mt-4 mb-3">

                    @include('admin.free-courses.partials.evaluation-box')

                </div>

                <hr>

                <div id="evaluation-box-container">
                    <form action="{{ route('admin.freeCourses.evaluations.update', $fcEvaluation) }}" id="fcEvaluationForm"
                        method="POST" enctype="multipart/form-data">

                        @include('admin.free-courses.partials.components._evaluation_edit')

                    </form>
                </div>

            </div>

        </div>

    </div>
@endsection


@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/page/freeCourses/evaluations.js') }}"></script>
@endsection
