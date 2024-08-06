@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">

            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>CURSOS LIBRES EN VIVO</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <div class="mb-4">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#RegisterLiveFreeCourseModal">
                        <i class="fa-solid fa-square-plus"></i> &nbsp; Registrar
                    </button>
                </div>

                <form action="{{ route('admin.freeCourseLive.exportExcel') }}" id="form-freeCourseLive-export"
                    method="GET">
                    <div class="mb-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-download"></i> &nbsp; Descargar Excel
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </div>
                </form>

                <table id="live-free-courses-table" class="table table-hover"
                    data-url="{{ route('admin.freeCourseLive.index') }}">

                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>Tipo</th>
                            <th>Nombre</th>
                            <th>Subtítulo</th>
                            <th>Fecha</th>
                            <th>Hora Inicio</th>
                            <th>Hora Fin</th>
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
    @include('admin.live-free-courses.partials.modals._register', ['place' => 'index'])
    @include('admin.live-free-courses.partials.modals._edit', ['place' => 'index'])
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/live-freecourses.js') }}"></script>
@endsection
