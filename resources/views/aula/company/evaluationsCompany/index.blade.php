@extends('aula.common.layouts.masterpage')

@section('extra-head')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    {{-- Date range picker --}}
    <link rel="stylesheet" href="{{ asset('assets/common/modules/bootstrap-daterangepicker/daterangepicker.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/common/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">

    <!-- Template CSS -->
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
    <div class="content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>Evaluaciones</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <form action="{{ route('aula.userEvaluationsCompany.download.excel.userEvaluations') }}"
                    id="form-user-profile-report-export" method="GET">

                    <div class="mb-4">
                        <button type="submit" class="btn btn-success" id="btn-export-user-surveys">
                            <i class="fa-solid fa-download"></i> &nbsp; Exportar Excel
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </div>

                    <div class="group-filter-buttons-section">
                        <div class="form-group date-range-container">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <a href="javascript:;" id="daterange-btn-certifications"
                                        class="btn btn-primary icon-left btn-icon pt-2">
                                        <i class="fas fa-calendar"></i>
                                        Elegir Fecha
                                    </a>
                                </div>
                                <input type="text" name="dateRange" class="form-control date-range-input"
                                    id="date-range-input-certifications">
                            </div>
                        </div>
                    </div>

                    <div class="group-filter-buttons-section flex-wrap d-flex" style="gap: 1.5em">

                        <div class="form-group col-2 p-0 select-group">
                            <label class="form-label">Filtrar por estado: &nbsp;</label>
                            <div>
                                <select name="status" class="form-control select2 select-filter-certification"
                                    id="search_from_status_select">
                                    <option value=""> Todos </option>
                                    <option value="approved"> Aprobados </option>
                                    <option value="suspended"> Desaprobados </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-2 p-0 select-group">
                            <label class="form-label">Filtrar por curso: &nbsp;</label>
                            <div>
                                <select name="course" class="form-control select2 select-filter-certification"
                                    id="search_from_course_select">
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

                </form>

                <table id="certifications-index-table" class="table table-hover"
                    data-url="{{ route('aula.userEvaluationsCompany.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>DNI</th>
                            <th>Apellidos y nombres</th>
                            {{-- <th>Empresa</th> --}}
                            <th>Curso</th>
                            <th>Evento</th>
                            <th>Fecha</th>
                            <th>Nota</th>
                            <th>Examen</th>
                            {{-- <th>Certificado</th>
                            <th>Documentos</th> --}}
                        </tr>
                    </thead>
                </table>

            </div>

        </div>

    </div>
@endsection


@section('extra-script')
    <script src="{{ asset('assets/common/modules/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('assets/common/modules/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script type="module" src="{{ asset('assets/aula/js/pages/company/evaluations.js') }}"></script>
@endsection
