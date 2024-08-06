@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">


        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>CURSOS DE ESPECIALIZACIÓN</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <div class="mb-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#RegisterSpecCourseModal">
                        <i class="fa-solid fa-square-plus"></i> &nbsp; Registrar
                    </button>
                </div>

                <form action="{{ route('admin.specCourses.exportExcel') }}" id="form-specCourses-export" method="GET">
                    <div class="mb-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-download"></i> &nbsp; Descargar Excel
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </div>
                </form>

                <table id="spec-courses-table" class="table table-hover" data-url="{{ route('admin.specCourses.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Título</th>
                            <th>Subtitulo</th>
                            <th>Fecha</th>
                            <th>Hora de inicio</th>
                            <th>Hora de fin</th>
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
    @include('admin.specCourses.partials.modals._register', ['place' => 'index'])
    @include('admin.specCourses.partials.modals._edit', ['place' => 'index'])
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/spec-courses.js') }}"></script>
@endsection
