@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>CURVA DEL OLVIDO</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <div class="mb-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#RegisterForgettingCurveModal">
                        <i class="fa-solid fa-square-plus"></i> &nbsp; Registrar
                    </button>
                </div>

                <form action="{{ route('admin.forgettingCurve.exportExcel') }}" id="form-forgettingCurve-export"
                    method="GET">
                    <div class="mb-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-download"></i> &nbsp; Descargar Excel
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </div>
                </form>

                <table id="forgetting-curve-table" class="table table-hover"
                    data-url="{{ route('admin.forgettingCurve.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Nota mínima</th>
                            <th>Tipo de curso</th>
                            <th>Cursos</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>

    </div>
@endsection

@section('modals')
    @include('admin.forgettingCurve.partials.modals._register', ['place' => 'register'])
    @include('admin.forgettingCurve.partials.modals._edit')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/forgettingCurve.js') }}"></script>
@endsection
