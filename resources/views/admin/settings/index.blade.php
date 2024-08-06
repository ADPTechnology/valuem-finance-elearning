@extends('admin.common.layouts.masterpage')


@section('content')
    <div class="row content">

        <div class="main-container-page">
            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>Configuraciones</h4>
                    </div>
                </div>
            </div>


            <div class="card-body card z-index-2 principal-container">

                {{------- CONFIGURACIÓN GENERAL ------}}

                <div class="card page-title-container">
                    <div class="card-header">
                        <div class="total-width-container">
                            <h4> Configuración general</h4>
                        </div>
                    </div>
                </div>

                {{------- LOGO ------}}

                <div class="principal-splitted-container mt-1 mb-2">
                    <div class="principal-inner-container total-width">
                        <div class="inner-title-container">
                            <div id="" class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-images not-rotate"></i> &nbsp;
                                    Logo
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

                            <div id="logo-list-container" class="related-dropdown-container" style="display: none;">
                                @include('admin.settings.partials.components._logo_list')
                            </div>

                        </div>
                    </div>
                </div>

                {{------- WSP ------}}

                <div class="principal-splitted-container mt-1 mb-2">

                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">
                            <div id="" class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-brands fa-whatsapp not-rotate"></i> &nbsp;
                                    WhatsApp
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
                        </div>

                        <div id="config-list-container" class="related-dropdown-container" style="display: none;">
                            <form action="{{ route('admin.settings.config.update', $config) }}" id="editConfigForm"
                                method="POST" enctype="multipart/form-data">

                                @include('admin.settings.partials._form_config_edit')

                            </form>
                        </div>

                    </div>

                </div>

                {{------- CONFIGURACIÓN DEL LOGIN ------}}

                <div class="card page-title-container">
                    <div class="card-header">
                        <div class="total-width-container">
                            <h4> Configuración del login</h4>
                        </div>
                    </div>
                </div>

                    {{------- CARRUSEL DEL LOGIN ------}}

                <div class="principal-splitted-container mt-1 mb-2">

                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">
                            <div id="" class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-images not-rotate"></i> &nbsp;
                                    Carrusel del Login
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
                        </div>

                        <div id="sliderImages-list-container" class="related-dropdown-container" style="display: none;">
                            @include('admin.sliderImages.partials.boxes._images_list')
                        </div>
                    </div>
                </div>

                {{------- CONFIGURACIÓN PÁGINA PRINCIPAL ------}}

                <div class="card page-title-container">
                    <div class="card-header">
                        <div class="total-width-container">
                            <h4> Configuración página principal</h4>
                        </div>
                    </div>
                </div>

                {{------- CARRUSEL PÁGINA PRINCIPAL ------}}

                <div class="principal-splitted-container mt-1 mb-2">
                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">
                            <div id="" class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-images not-rotate"></i>  &nbsp;
                                    Carrusel de la página principal
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
                        </div>

                        <div id="p-banners-list-container" class="related-dropdown-container" style="display: none;">
                            @include('admin.settings.partials._p_carrousel_list')
                        </div>

                    </div>
                </div>


                {{------- CONFIGURACIÓN AULA ------}}

                <div class="card page-title-container">
                    <div class="card-header">
                        <div class="total-width-container">
                            <h4> Configuración aula</h4>
                        </div>
                    </div>
                </div>

                    {{------- CARRUSEL DEL AULA ------}}

                <div class="principal-splitted-container mt-1 mb-2">

                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">
                            <div id="" class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-sliders not-rotate"></i> &nbsp;
                                    Carrusel del Aula
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
                        </div>

                        <div id="banners-list-container" class="related-dropdown-container" style="display: none;">

                            @include('admin.announcements.partials.boxes._banners_list')

                        </div>

                    </div>

                </div>

                    {{------- ANUNCIOS ------}}

                <div class="principal-splitted-container mt-1 mb-2">

                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">
                            <div class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-newspaper not-rotate"></i> &nbsp;
                                    Anuncios
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
                        </div>

                        <div id="publishings-list-container" class="related-dropdown-container table-container"
                            style="display: none;">

                            <div class="mb-4">
                                <button class="btn btn-primary" id="btn-register-publication-modal" data-toggle="modal"
                                    data-target="#registerCardModal" data-url="">
                                    <i class="fa-solid fa-square-plus"></i> &nbsp; Crear publicación
                                </button>
                            </div>

                            <table id="publishings-table" class="table table-hover"
                                data-url="{{ route('admin.announcements.index') }}">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Título</th>
                                        <th>Usuario</th>
                                        <th>Fecha de publicación</th>
                                        <th>Estado</th>
                                        <th>Creado el</th>
                                        <th>Actualizado el</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                            </table>

                        </div>

                    </div>

                </div>

                    {{------- NOTICIAS EN PERFIL ------}}

                <div class="principal-splitted-container mt-1 mb-2">

                    <div class="principal-inner-container total-width">

                        <div class="inner-title-container">
                            <div id="" class="btn-dropdown-container">
                                <h5 class="title-header-show">
                                    <i class="fa-solid fa-newspaper not-rotate"></i> &nbsp;
                                    Noticias en perfil
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
                        </div>

                        <div id="news-list-container" class="related-dropdown-container" style="display: none;">

                            @include('admin.settings.partials._news')

                        </div>

                    </div>


                </div>

            </div>


        </div>

    </div>
@endsection

@section('modals')

    {{-- * LOGO --}}
    @include('admin.settings.partials.modals._logo_store')

    {{-- * CARRUSEL LOGIN --}}
    @include('admin.sliderImages.partials.modals._sliderImage_store')
    @include('admin.sliderImages.partials.modals._sliderImage_edit')

    {{-- * CARRUSEL PAGINA PRINCIPAL --}}
    @include('admin.settings.partials.modals._p_carrousel_store', ['type' => 'store'])
    @include('admin.settings.partials.modals._p_carrousel_edit', ['type' => 'edit'])

    {{-- * CARRUSEL AULA --}}
    @include('admin.announcements.partials.modals._banner_store')
    @include('admin.announcements.partials.modals._banner_edit')

    {{-- * ANUNCIOS AULA --}}
    @include('admin.announcements.partials.modals._card_store')
    @include('admin.announcements.partials.modals._card_edit')

    {{-- * NOTICIAS --}}
    @include('admin.settings.partials.modals._news_store')
    @include('admin.settings.partials.modals._news_edit')


@endsection


@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/page/settings/news.js') }}"></script>
    <script type="module" src="{{ asset('assets/admin/js/page/settings/config.js') }}"></script>
@endsection
