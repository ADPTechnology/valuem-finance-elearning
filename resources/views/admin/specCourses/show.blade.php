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

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.specCourses.index') }}">Inicio</a>
                    /
                    <span id="specCourse-description-text-principal" class="to-capitalize">
                        {{ mb_strtolower($specCourse->title, 'UTF-8') }}
                    </span>
                </h5>

                <div id="specCourse-box-container" class="info-element-box mt-4 mb-4">

                    @include('admin.specCourses.partials.components._specCourse_box')

                </div>

                <div class="principal-splitted-container">

                    <div class="principal-inner-container left">

                        <div class="inner-title-container">
                            <div id="btn-drowdown-sections-list" class="btn-dropdown-container show">
                                <h5 class="title-header-show"> Módulos </h5>
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

                        <div class="mt-4 action-btn-dropdown-container outside show top-container-inner-box">
                            <button class="btn btn-primary" id="btn-register-module-modal" data-toggle="modal"
                                data-target="#registerModuleModal">
                                <i class="fa-solid fa-plus"></i> &nbsp; Añadir Módulo
                            </button>
                        </div>

                        <div id="modules-list-container"
                            class="sections-list-container related-dropdown-container mt-0 show">

                            @include('admin.specCourses.partials.components._modules_list')
                        </div>

                    </div>

                    <div class="principal-inner-container right">

                        <div class="inner-title-container">
                            <div id="btn-drowdown-chapters-list" class="btn-dropdown-container vertical show">
                                <h5 class="title-header-show">
                                    Grupos de Eventos
                                    <span id="top-event-table-title-info">

                                    </span>
                                </h5>
                                <div class="btn-row-container">
                                    <div>
                                        <span class="text-dropdown-cont">
                                            Ocultar
                                        </span>
                                        <i class="fa-solid fa-chevron-down ms-2"></i>
                                    </div>
                                </div>
                            </div>

                            <div id="groupEvents-list-container" class="related-dropdown-container table-container show">

                                @include('admin.specCourses.partials.components._group_events_list')

                            </div>
                        </div>

                    </div>


                </div>

                <div class="folder-inner-container">

                    <h5 class="title-header-show mb-4"> Archivos: </span> </h5>


                    <div class="files-section-container">
                        <div>
                            <h6> Añadir Archivo(s) <strong>(Max 40MB)</strong></h6>
                        </div>
                        <div>

                            <form id="store-spec-courses-files-form"
                                action="{{ route('admin.specCourses.file.store', $specCourse) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf

                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <input type="file" class="store-spec-courses-files-input">
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <button type="submit" class="btn btn-save btn-primary waves-effect waves-light">
                                            Guardar
                                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                                        </button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>

                    <div>
                        <div>
                            <h6>Lista de Archivos</h6>
                        </div>

                        <div class="table-border-style">

                            <div class="table-responsive">
                                <table id="files-specCourses-table" class="table table-hover"
                                    data-url='{{ route('admin.specCourses.files.index', $specCourse) }}'>
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Nombre</th>
                                            <th>Tipo</th>
                                            <th>Categoría</th>
                                            <th>Creado el</th>
                                            <th>Actualizado el</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                </table>
                            </div>

                        </div>


                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection

@section('modals')
    @include('admin.specCourses.partials.modals._edit', ['place' => 'show'])

    @include('admin.specCourses.partials.modals._register_module')
    @include('admin.specCourses.partials.modals._edit_module')

    @include('admin.specCourses.partials.modals._register_group_event', ['place' => 'index'])
    @include('admin.specCourses.partials.modals._edit_group_event', ['place' => 'index'])
    @include('admin.specCourses.partials.modals._module_files')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/spec-courses.js') }}"></script>
@endsection
