@extends('aula.common.layouts.masterpage')

@section('content')

<div class="row content">

    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>Eventos</h4>
                </div>
            </div>
        </div>

        <div class="card-body body-global-container card z-index-2 principal-container">

            <h5 class="title-header-show">
                <i class="fa-solid fa-chevron-left fa-xs"></i>
                <a href="{{ route('aula.supervisor.events.index') }}">Inicio</a>
                / Evento:
                <span id="event-description-text-principal" class="to-capitalize">
                    {{ mb_strtolower($event->description, 'UTF-8') }}
                </span>
            </h5>


            <div id="event-box-container" class="info-element-box event-box mt-4 mb-3">

                @include('aula.supervisor.events.partials.components._box_event')

            </div>


            <h5 class="title-header-show mb-4 mt-4"> Lista de participantes: </h5>

            <div class="group-filter-buttons-section flex-wrap">
                <div class="form-group col-2 p-0 select-group">
                    <label class="form-label">Filtrar por estado: &nbsp;</label>
                    <div>
                        <select name="status" class="form-control select2 select-filter-certifications" id="search_from_status_select">
                            <option value="">Todos</option>
                            <option value="approved">Aprobados</option>
                            <option value="suspended">Desaprobados</option>
                        </select>
                    </div>
                </div>

            </div>

            <table id="certifications-table" class="table table-hover"
                data-url="{{ route('aula.supervisor.events.show', $event) }}">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>DNI</th>
                        <th>Participante</th>
                        <th>Empresa</th>
                        <th>Nota</th>
                        <th>Estado</th>
                        <th>Habilitado</th>
                        <th>Asistencia</th>
                        <th>Ver</th>
                    </tr>
                </thead>
            </table>

        </div>

    </div>

</div>

@endsection

@section('modals')
    @include('admin.events.partials._modal_show_certification')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/aula/js/pages/supervisor/events.js') }}"></script>
@endsection
