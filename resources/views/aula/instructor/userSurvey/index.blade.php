@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content">
        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>ENCUESTAS DE PERFIL DE USUARIOS</h4>
                    </div>
                </div>
            </div>


            <div class="card-body card z-index-2 principal-container">

                <form action="{{ route('aula.userSurveysInstructor.download.excel.user.profile') }}"
                    id="form-user-profile-report-export" method="GET">

                    <div class="mb-4">
                        <button type="submit" class="btn btn-success" id="btn-export-user-surveys">
                            <i class="fa-solid fa-download"></i> &nbsp; Exportar Excel
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </div>

                </form>

                <table id="profileUserSurveys_table" class="table table-hover"
                    data-url="{{ route('aula.userSurveysInstructor.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>DNI</th>
                            <th>A. Paterno</th>
                            <th>A. Materno</th>
                            <th>Nombres</th>
                            <th>Empresa</th>
                            <th>Encuesta</th>
                            <th>Fecha de finalización</th>
                            <th>EC</th>
                            <th>OR</th>
                            <th>CA</th>
                            <th>EA</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>

    </div>
@endsection


@section('extra-script')
    <script type="module" src="{{ asset('assets/aula/js/pages/instructor/userSurveys.js') }}"></script>
@endsection
