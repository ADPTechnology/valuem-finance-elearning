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

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.freeCourseLive.index') }}">Inicio</a>
                    /
                    <span id="live-free-course-description-text-principal" class="to-capitalize">
                        {{ mb_strtolower($course->description, 'UTF-8') }}
                    </span>
                </h5>

                <div id="liveFreeCourse-box-container" class="info-element-box mt-4 mb-4">
                    @include('admin.live-free-courses.partials.components._course_box')
                </div>

                <div class="folder-inner-container">

                    <h5 class="title-header-show mb-4"> Archivos: </span> </h5>

                    <div class="files-section-container">

                        <div>
                            <h6> Añadir Archivo(s) <strong>(Max 40MB)</strong> </h6>
                        </div>

                        <div>

                            <form action="{{ route('admin.freeCourseLive.files.store', $course) }}" method="POST"
                                id="store-live-free-course-files-form" enctype="multipart/form-data">
                                @csrf

                                <div class="form-row">
                                    <div class="form-group col-12">
                                        <input type="file" class="store-live-free-course-files-input">
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
                                <table id="files-live-free-courses-table" class="table table-hover"
                                    data-url='{{ route('admin.freeCourseLive.files.index', $course) }}'>
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
    @include('admin.live-free-courses.partials.modals._edit', ['place' => 'show'])

    @include('admin.specCourses.partials.modals._register_event', ['place' => 'index'])
    @include('admin.specCourses.partials.modals._edit_event', ['place' => 'index'])
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/live-freecourses.js') }}"></script>
@endsection
