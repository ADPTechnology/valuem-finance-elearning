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
                    / <span class="to-capitalize">
                        {{ $exam->title }}
                    </span>

                </h5>


                <div id="free-course-exam-box-container" class="info-element-box mt-4 mb-3">

                    @include('admin.free-courses.partials.exam-box')

                </div>

                <hr>

                <div class="principal-splitted-container mt-1 mb-2">

                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">
                            <div id="" class="btn-dropdown-container show">
                                <h5 class="title-header-show"> Creación de enunciados </h5>

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

                        <div id="dropdown-questions-create" class="related-dropdown-container">

                            <form id="registerQuestionForm"
                                action="{{ route('admin.freeCourses.exams.questions.store', $exam) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-row questionTypeSelect">
                                    <div class="form-group col-12">
                                        <label>Selecciona un tipo de enunciado *</label>
                                        <div class="input-group">
                                            <select name="question_type_id" class="form-control select2"
                                                id="registerQuestionTypeSelect"
                                                data-url="{{ route('admin.exams.questions.getType') }}">
                                                <option></option>
                                                @foreach ($questionTypes as $questionType)
                                                    <option value="{{ $questionType->id }}">
                                                        {{ $questionType->description }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>


                                <div id="question-type-container">

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

                <hr>

                <h5 class="title-header-show mb-4">
                    Lista de enunciados:
                </h5>

                <table id="questions-table" class="table table-hover"
                    data-url="{{ route('admin.freeCourses.exams.show', $exam) }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Tipo de enunciado</th>
                            <th>Enunciado</th>
                            <th>Puntos</th>
                            <th>Fecha de creación</th>
                            <th>Fecha de actualización</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>
@endsection
