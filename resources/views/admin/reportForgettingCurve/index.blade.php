@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">


        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>REPORTE DE LOS PARTICIPANTE DE LA CURVA DEL OLVIDO</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <form action="{{ route('admin.reportForgettingCurve.exportExcel') }}" id="form-reportForgettingCurve-export"
                    method="GET">
                    <div class="mb-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-download"></i> &nbsp; Descargar Excel
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </div>
                </form>

                <table id="reportForgettingCurve-table" class="table table-hover"
                    data-url="{{ route('admin.reportForgettingCurve.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Curva del Olvido</th>
                            <th>Nota miníma</th>
                            <th>Curso</th>
                            <th>N° de participante</th>
                            <th>Participante</th>
                            <th>Pasos completados 7mo</th>
                            <th>Fecha de finalización 7mo</th>
                            <th>Puntuación 7mo</th>
                            <th>Pasos completados 15to</th>
                            <th>Fecha de finalización 15to</th>
                            <th>Puntuación 15to</th>
                            <th>Calificación</th>
                        </tr>
                    </thead>
                </table>

            </div>

        </div>

    </div>
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/reportForgettingCurve.js') }}"></script>
@endsection
