@extends('admin.common.layouts.masterpage')

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

                <div class="principal-splitted-container">

                    <div class="principal-inner-container left">
                        <div class="inner-title-container">
                            <div id="btn-drowdown-category-list" class="btn-dropdown-container show">
                                <h5 class="title-header-show"> Categorías </h5>
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


                        <div id="categories-list-container"
                            class="categories-list-container related-dropdown-container little-left show">

                            @include('admin.free-courses.partials.categories-list')

                        </div>
                    </div>

                    <div class="principal-inner-container right">
                        <div class="inner-title-container">

                            <div class="btn-dropdown-container vertical show">
                                <h5 class="title-header-show"> Lista de Cursos </h5>
                                <div>
                                    <span class="text-dropdown-cont">
                                        Ocultar
                                    </span>
                                    <i class="fa-solid fa-chevron-down ms-2"></i>
                                </div>
                            </div>

                            <div class="mt-4 action-btn-dropdown-container vertical outside show top-container-inner-box">
                                <button class="btn btn-primary" id="btn-register-freecourse-modal"
                                    data-url="{{ route('admin.freeCourses.getCategoriesRegister') }}">
                                    <i class="fa-solid fa-plus"></i> &nbsp;
                                    Registrar
                                    <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                                </button>
                            </div>

                            <div class="related-dropdown-container table-container show">

                                <table id="freeCourses-table" class="table table-hover"
                                    data-url="{{ route('admin.freeCourses.index') }}">
                                    <thead>
                                        <tr>
                                            <th>N°</th>
                                            <th>Nombre</th>
                                            <th>Subtítulo</th>
                                            <th>Categoría</th>
                                            <th>N° de secciones</th>
                                            <th>N° de capítulos</th>
                                            <th>Duración total</th>
                                            <th>Estado</th>
                                            <th class="text-center">Recomendado</th>
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
    {{-- CATEGORY --}}
    @include('admin.free-courses.partials.modals._store_category')
    @include('admin.free-courses.partials.modals._edit_category')

    {{-- FREE COURSE --}}
    @include('admin.free-courses.partials.modals._store_free_course')
@endsection
