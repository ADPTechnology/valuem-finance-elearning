@extends('admin.common.layouts.masterpage')

@section('extra-head')
    <!-- VIDEO.JS ---->
    <link href="https://vjs.zencdn.net/8.3.0/video-js.css" rel="stylesheet" />
@endsection

@section('content')
    <div class="row content">

        <div class="main-container-page">

            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>CURSOS LIBRES</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.freeCourses.index') }}">Inicio</a>
                    / Categoría:
                    <a href="{{ route('admin.freeCourses.categories.index', ['category' => $course->courseCategory]) }}"
                        class="to-capitalize">
                        {{ mb_strtolower($course->courseCategory->description, 'UTF-8') }}</a>
                    / Curso:
                    <span id="course-description-text-principal" class="to-capitalize">
                        {{ mb_strtolower($course->description, 'UTF-8') }}
                    </span>
                </h5>

                <div id="course-box-container" class="info-element-box mt-4 mb-4">
                    @include('admin.free-courses.partials.course-box')
                </div>


                <div class="principal-splitted-container">

                    <div class="principal-inner-container left">

                        <div class="inner-title-container">
                            <div id="btn-drowdown-sections-list" class="btn-dropdown-container show">
                                <h5 class="title-header-show"> Secciones </h5>
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
                            <button class="btn btn-primary" id="btn-register-section-modal" data-toggle="modal"
                                data-target="#registerSectionModal">
                                <i class="fa-solid fa-plus"></i> &nbsp; Añadir sección
                            </button>
                        </div>

                        <div id="sections-list-container"
                            class="sections-list-container related-dropdown-container mt-0 show">
                            @include('admin.free-courses.partials.sections-list')
                        </div>

                    </div>

                    <div class="principal-inner-container right">

                        <div class="inner-title-container">
                            <div id="btn-drowdown-chapters-list" class="btn-dropdown-container vertical show">
                                <h5 class="title-header-show">
                                    Capítulos
                                    <span id="top-chapter-table-title-info">

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

                            <div id="chapters-list-container" class="related-dropdown-container table-container show">

                                @include('admin.free-courses.partials.chapter-list-empty')

                            </div>
                        </div>

                    </div>


                </div>

                <hr>

                <div class="principal-splitted-container">

                    <div class="principal-inner-container right">
                        <div class="inner-title-container">
                            <div id="btn-drowdown-exams-list" class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-user not-rotate"></i> &nbsp;
                                    Usuarios:
                                </h5>
                                <div class="btn-row-container">
                                    <div>
                                        <span class="text-dropdown-cont">
                                            Mostrar
                                        </span>
                                        <i class="fa-solid fa-chevron-down ms-2"></i>
                                    </div>
                                </div>
                            </div>

                            <div id="exams-list-container" class="related-dropdown-container table-container"
                                style="display: block;">

                                <div class="group-filter-buttons-section flex-wrap">

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

                                    <div class="form-group col-2 p-0 select-group">
                                        <label class="form-label">Filtrar por estado de solicitud: &nbsp;</label>
                                        <div>
                                            <select name="type"
                                                class="form-control select2 select-status-course-habilitation"
                                                id="search_from_status_certification_select">
                                                <option value=""> Todos </option>
                                                <option value="approved"> Aprobados
                                                </option>
                                                <option value="pending"> Pendientes
                                                </option>
                                            </select>
                                        </div>
                                    </div>

                                </div>

                                <div class="action-btn-dropdown-container top-container-inner-box">
                                    <button class="btn btn-primary" id="btn-register-participant-on-course">
                                        <i class="fa-solid fa-plus"></i> &nbsp;
                                        Registrar usuario
                                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                                    </button>
                                </div>

                                <div class="table-border-style">
                                    <div class="table-responsive">
                                        <table id="users-free-courses-table" class="table table-hover"
                                            data-url='{{ route('admin.freeCourses.users.getDataTable', $course) }}'>
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>DNI</th>
                                                    <th>Participante</th>
                                                    <th>Empresa</th>
                                                    <th>Estado</th>
                                                    <th>Nota</th>
                                                    <th>Habilitar curso</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="principal-splitted-container">

                    <div class="principal-inner-container right">
                        <div class="inner-title-container">
                            <div id="btn-drowdown-exams-list" class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-folder-tree not-rotate"></i> &nbsp;
                                    Exámenes:
                                </h5>
                                <div class="btn-row-container">
                                    <div>
                                        <span class="text-dropdown-cont">
                                            Mostrar
                                        </span>
                                        <i class="fa-solid fa-chevron-down ms-2"></i>
                                    </div>
                                </div>
                            </div>

                            <div id="exams-list-container" class="related-dropdown-container table-container"
                                style="display: none;">

                                <div class="action-btn-dropdown-container top-container-inner-box">
                                    <button class="btn btn-primary" id="btn-register-exams" data-toggle="modal"
                                        data-target="#RegisterExamModal">
                                        <i class="fa-solid fa-plus"></i> &nbsp;
                                        Añadir Examen
                                        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                                    </button>
                                </div>

                                <div class="table-border-style">
                                    <div class="table-responsive">
                                        <table id="exams-free-courses-table" class="table table-hover"
                                            data-url='{{ route('admin.freeCourses.exams.getDataTable', $course) }}'>
                                            <thead>
                                                <tr>
                                                    <th>N°</th>
                                                    <th>Titulo</th>
                                                    <th>Duración (minutos)</th>
                                                    <th>Estado</th>
                                                    <th>Acciones</th>
                                                </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <hr>

                <div class="principal-splitted-container mt-1 mb-2">

                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">

                            <div id="" class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-folder-open not-rotate"></i> &nbsp;
                                    Archivos:
                                </h5>

                                <div class="btn-row-container">
                                    <div>
                                        <span class="text-dropdown-cont">
                                            Mostrar
                                        </span>
                                        <i class="fa-solid fa-chevron-down ms-2"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="related-dropdown-container table-container" style="display: none;">

                                <div class="files-section-container">

                                    <div>
                                        <h6> Añadir Archivo(s) <strong>(Max 40MB)</strong> </h6>
                                    </div>

                                    <div>

                                        <form action="{{ route('admin.freeCourses.files.store', $course) }}"
                                            method="POST" id="store-free-courses-files-form"
                                            enctype="multipart/form-data">
                                            @csrf

                                            <div class="form-row">
                                                <div class="form-group col-12">
                                                    <input type="file" class="store-free-courses-files-input">
                                                </div>
                                            </div>

                                            <div class="form-row">
                                                <div class="form-group col-12">
                                                    <button type="submit"
                                                        class="btn btn-save btn-primary waves-effect waves-light">
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
                                            <table id="files-free-courses-table" class="table table-hover"
                                                data-url='{{ route('admin.freeCourses.files.index', $course) }}'>
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

            </div>

        </div>

    </div>
@endsection

@section('modals')
    {{-- FREE COURSE --}}
    @include('admin.free-courses.partials.modals._edit_free_course')

    {{-- SECTIONS --}}

    @include('admin.free-courses.partials.modals._store_section')
    @include('admin.free-courses.partials.modals._edit_section')

    {{-- CHAPTERS --}}

    @include('admin.free-courses.partials.modals._store_chapter')
    @include('admin.free-courses.partials.modals._edit_chapter')

    {{-- -- PREVIEW VIDEO CHAPTER - --}}
    @include('admin.free-courses.partials.modals._preview_chapter')

    {{-- EXAM --}}
    @include('admin.free-courses.partials.modals._store_exam')
    @include('admin.free-courses.partials.modals._edit_exam')

    {{-- EXAM TO SECTION --}}
    @include('admin.free-courses.partials.modals._assignExam')

    {{-- REGISTER PARTICIPANT --}}
    @include('admin.free-courses.partials.modals._register-participant')
@endsection

@section('extra-script')
    <script src="https://vjs.zencdn.net/8.3.0/video.min.js"></script>
    <script type="module" src="{{ asset('assets/admin/js/page/freeCourses/files.js') }}"></script>
    <script type="module" src="{{ asset('assets/admin/js/page/freeCourses/exams.js') }}"></script>
    <script type="module" src="{{ asset('assets/admin/js/page/freeCourses/participants.js') }}"></script>
@endsection
