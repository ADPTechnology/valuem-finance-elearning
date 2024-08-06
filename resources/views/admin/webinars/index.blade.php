@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">


        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>WEBINARS</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <div class="mb-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#RegisterWebinarModal">
                        <i class="fa-solid fa-square-plus"></i> &nbsp; Registrar
                    </button>
                </div>

                <form action="{{ route('admin.webinars.all.exportExcel') }}" id="form-webinars-export" method="GET">
                    <div class="mb-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-download"></i> &nbsp; Descargar Excel
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </div>
                </form>

                <table id="webinars-table" class="table table-hover" data-url="{{ route('admin.webinars.all.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Fecha</th>
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
    @include('admin.webinars.partials.modals._register', ['place' => 'index'])
    @include('admin.webinars.partials.modals._edit', ['place' => 'index'])
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/webinars.js') }}"></script>
@endsection
