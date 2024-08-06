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

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.webinars.all.index') }}">Inicio</a>
                    /
                    <span id="webinar-description-text-principal" class="to-capitalize">
                        {{ mb_strtolower($webinar->title, 'UTF-8') }}
                    </span>
                </h5>

                <div id="webinar-box-container" class="info-element-box mt-4 mb-4">
                    @include('admin.webinars.partials.components._webinar_box')
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

                                        <form action="{{ route('admin.webinars.all.files.store', $webinar) }}"
                                            method="POST" id="store-webinar-files-form" enctype="multipart/form-data">
                                            @csrf

                                            <div class="form-row">
                                                <div class="form-group col-12">
                                                    <input type="file" class="store-webinar-files-input">
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
                                            <table id="files-webinar-table" class="table table-hover"
                                                data-url='{{ route('admin.webinars.all.files.index', $webinar) }}'>
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

                <hr>

                <div>

                    <h5 class="title-header-show mb-4"> Eventos: </span> </h5>

                    <div class="table-border-style">
                        <div class="table-responsive">

                            <div class="action-btn-dropdown-container top-container-inner-box">
                                <button class="btn btn-primary" id="btn-register-event-modal" data-toggle="modal"
                                    data-target="#RegisterWebinarEventModal">
                                    <i class="fa-solid fa-plus"></i> &nbsp;
                                    Registrar Evento
                                    <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                                </button>
                            </div>

                            <table id="webinar-events-table" class="table table-hover"
                                data-url="{{ route('admin.webinars.all.events.index', $webinar) }}">
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Descripción</th>
                                        <th>Fecha</th>
                                        <th>Hora de inicio</th>
                                        <th>Hora de finalización</th>
                                        <th>Webinar</th>
                                        <th>Instructor</th>
                                        <th>Estado</th>
                                        <th class="action-with">Acciones</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </div>
@endsection

@section('modals')
    @include('admin.webinars.partials.modals._edit', ['place' => 'show'])
    @include('admin.webinars.events.partials.modals._register', ['place' => 'index'])
    @include('admin.webinars.events.partials.modals._edit', ['place' => 'index'])
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/webinars.js') }}"></script>
    <script type="module" src="{{ asset('assets/admin/js/webinarEvents.js') }}"></script>
@endsection
