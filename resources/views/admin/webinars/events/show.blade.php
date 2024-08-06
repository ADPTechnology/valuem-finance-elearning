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
                <a href="{{route('admin.webinars.all.index')}}">Inicio</a>
                /
                <a href="{{ route('admin.webinars.all.show', ["webinar" => $webinarEvent->webinar]) }}"
                    class="to-capitalize">
                    {{ mb_strtolower($webinarEvent->webinar->title, 'UTF-8') }}
                </a>
                /
                <span id="webinar-event-description-text-principal" class="to-capitalize">
                    {{ mb_strtolower($webinarEvent->description, 'UTF-8') }}
                </span>
            </h5>


            <div id="webinar-event-box-container" class="info-element-box event-box mt-4 mb-3">

                @include('admin.webinars.events.partials.components._event_box')

            </div>


            <h5 class="title-header-show mb-1 mt-4"> Lista de participantes: </h5>

            <hr>

            <h4 class="title-header-show mb-4">
                Participantes Internos
            </h4>

            <div class="mb-4">
                <button class="btn btn-primary" id="btn-register-participant-modal"
                    data-url="">
                    <i class="fa-solid fa-square-plus"></i> &nbsp; Registrar participantes
                    <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                </button>
            </div>

            <table id="wb_event_inner_certifications_table" class="table table-hover"
                data-url="{{ route('admin.webinars.all.events.certifications.index', $webinarEvent) }}">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>DNI</th>
                        <th>Participante</th>
                        <th>Empresa</th>
                        <th>Habilitar certificado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>


            <h4 class="title-header-show mb-4 mt-3">
                Participantes Externos
            </h4>

            <table id="wb_event_ext_certifications_table" class="table table-hover"
                data-url="{{ route('admin.webinars.all.events.certifications.index', $webinarEvent) }}">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>DNI</th>
                        <th>Participante</th>
                        <th>Habilitar certificado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>

        </div>

    </div>

</div>

@endsection

@section('modals')
    @include('admin.webinars.events.partials.modals._edit', ["place" => 'show'])
    @include('admin.webinars.events.certifications.partials.modals._register')
@endsection

@section('extra-script')
<script type="module" src="{{ asset('assets/admin/js/webinarEvents.js') }}"></script>
<script type="module" src="{{ asset('assets/admin/js/wb_certifications.js') }}"></script>
@endsection
