@extends('aula.common.layouts.masterpage')

@section('extra-head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/common/css/components.css') }}">
    <style>
        .select2-container--default .select2-selection--multiple .select2-selection__choice,
        .select2-container--default .select2-results__option[aria-selected=true],
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #cc0707;
            color: #fff;

        }

        .select2-container.select2-container--open .select2-selection--single {
            background-color: #fefeff;
            border-color: #cc0707;
        }
    </style>
@endsection

@section('content')
    <div class="row content">
        <div class="main-container-page w-100">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>KPIs</h4>
                    </div>
                </div>
                <div class="card-header">
                    <div class="total-width-container">
                        <img style="object-fit: cover" width="100%" height="450px"
                            src="{{ asset('assets/login/img/img_login.jpg') }}" alt="">
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <div class="row">

                    <!-- Gráfico Aprobado/Desaprobado -->

                    <div class="col-12 col-md-12 col-lg-6">
                        <div class="card card-primary card-back">
                            <div class="card-header card-header-chart">
                                <h4 class="title-chart">
                                    Aprobados y desaprobados en el mes de: {{ getCurrentMonth() }} del
                                    {{ getCurrentYear() }}
                                </h4>
                            </div>
                            <div class="group-filter-buttons-section" style="gap: 1.5em; padding: 0 25px">
                                <div class="form-group col-12 p-0 select-group">
                                    <label class="form-label">Filtrar por curso: &nbsp;</label>
                                    <div>
                                        <select name="course" class="form-control select2 select-filter-certification"
                                            data-url="{{ route('aula.kpisCompany.index') }}" id="search_from_course_select">
                                            <option value=""> Todos </option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}"> {{ $course->id }} -
                                                    {{ $course->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body" id="card-body-first-chart">
                                <h6 id="student-status" class="student-status">Oops... Los alumnos de la empresa aún no han
                                    dado alguna
                                    evaluación este mes.</h6>
                                <canvas data-status-certifications="{{ $statusCertifications }}" id="chart-student-status"
                                    style="height: 270px;">
                                </canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Gráfico tipos de aprendizaje de la empresa -->
                    <div class="col-12 col-md-12 col-lg-6">
                        <div class="card card-primary card-back">
                            <div class="card-header card-header-chart">
                                <h4 class="title-chart">Usuarios por tipos de aprendizaje de la empresa</h4>
                            </div>
                            <div class="card-body">
                                <canvas data-types="{{ $profile }}" id="chart-types-of-users">
                                </canvas>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <!-- Gráfico de satisfacción -->
                    <div class="col-12 col-md-12 col-lg-6">
                        <div class="card card-primary card-back">
                            <div class="card-header card-header-chart">
                                <h4 class="title-chart">Gráfico de satisfacción con el curso</h4>
                            </div>
                            <div class="group-filter-buttons-section" style="gap: 1.5em; padding: 0 25px">
                                <div class="form-group col-12 p-0 select-group">
                                    <label class="form-label">Filtrar por curso: &nbsp;</label>
                                    <div>
                                        <select name="course_satisfaction" class="form-control select2 select-filter-satisfaction"
                                            data-url="{{ route('aula.kpisCompany.getSatisfactionKpi') }}"
                                            id="search_from_course_select_satisfaction">
                                            <option value=""> Todos </option>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}"> {{ $course->id }} -
                                                    {{ $course->description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <h6 id="satisfaction-status" class="satisfaction-status">
                                    Ningún usuario de la empresa dio información sobre su satisfacción.
                                </h6>
                                <canvas data-satisfaction="{{ $satisfaction }}" id="chart-satisfaction" style="height: 270px;">
                                </canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>
@endsection

@section('extra-script')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('assets/aula/js/pages/kpis.js') }}" type="module"></script>
    <script src="{{ asset('assets/aula/js/pages/keyPerformanceIndicators.js') }}" type="module"></script>
    <script src="{{ asset('assets/aula/js/pages/kpiSatisfaction.js') }}" type="module"></script>
    <script src="{{ asset('assets/aula/js/pages/kpiSatisfactionChange.js') }}" type="module"></script>
@endsection
