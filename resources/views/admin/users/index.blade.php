@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">


        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>USUARIOS</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <div class="group-filter-buttons-section flex-wrap">

                    <div class="form-group col-2 p-0 select-group">
                        <label class="form-label">Filtrar por estado: &nbsp;</label>
                        <div>
                            <select name="active" class="form-control select2 select-filter-users"
                                id="search_from_status_select">
                                <option value=""> Todos </option>
                                <option value="S"> Activos </option>
                                <option value="N"> Inactivos </option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-2 p-0 select-group">
                        <label class="form-label">Filtrar por empresa: &nbsp;</label>
                        <div>
                            <select name="company" class="form-control select2 select-filter-users"
                                id="search_from_company_select">
                                <option value=""> Todos </option>
                                @foreach ($companies as $company)
                                    <option value="{{ $company->id }}"> {{ $company->id }} - {{ $company->description }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-2 p-0 select-group">
                        <label class="form-label">Filtrar por rol: &nbsp;</label>
                        <div>
                            <select name="rol" class="form-control select2 select-filter-users"
                                id="search_from_rol_select">
                                <option value=""> Todos </option>
                                @foreach ($roles as $key => $rol)
                                    <option value="{{ $key }}"> {{ $rol }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group col-2 p-0 select-group">
                        <label class="form-label">Filtrar por tipo: &nbsp;</label>
                        <div>
                            <select name="type" class="form-control select2 select-filter-users"
                                id="search_from_type_select">
                                <option value=""> Todos </option>
                                <option value="external"> Usuarios externos
                                </option>
                                <option value="internal"> Usuarios internos
                                </option>
                            </select>
                        </div>
                    </div>
                </div>


                <div class="mb-4">
                    <button class="btn btn-primary" id="btn-register-user-modal"
                        data-url='{{ route('admin.users.registerGetCompanies') }}'>
                        <i class="fa-solid fa-user-plus"></i> &nbsp; Registrar
                    </button>

                    <button class="btn btn-primary ms-4" id="btn-register-user-modal" data-toggle='modal'
                        data-target="#RegisterUserMassiveModal">
                        <i class="fa-solid fa-file-import"></i> &nbsp; Registro masivo
                    </button>
                </div>

                <form action="{{ route('admin.users.exportExcel') }}" id="form-users-export" method="GET">

                    <div class="mb-4">
                        <button type="submit" class="btn btn-success">
                            <i class="fa-solid fa-download"></i> &nbsp; Descargar Excel
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    </div>
                </form>

                <table id="users-table" class="table table-hover" data-url="{{ route('admin.users.index') }}">
                    <thead>
                        <tr>
                            <th>N°</th>
                            <th>DNI</th>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Rol</th>
                            <th>Empresa</th>
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
    <!-- USER -->
    @include('admin.users.partials.modals._register_user')
    @include('admin.users.partials.modals._edit_user')
    @include('admin.users.partials.modals._register_massive')
    <!-- DOCS THAT PARTICIPANT -->
    @include('admin.users.partials.modals._viewDocs')
    <!-- INSTRUCTOR INFORMATION -->
    @include('admin.users.partials.modals._viewInstructorInformation')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/page/users/docs.js') }}"></script>
    <script type="module" src="{{ asset('assets/admin/js/page/users/instructorInformation.js') }}"></script>
@endsection
