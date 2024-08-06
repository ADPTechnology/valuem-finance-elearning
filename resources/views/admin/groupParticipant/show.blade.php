@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">

            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>GRUPO DE PARTICIPANTES</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.specCourses.index') }}">Inicio</a>
                    /
                    <span id="specCourse-description-text-principal" class="to-capitalize">
                        <a href="{{ route('admin.specCourses.show', $groupParticipant->groupEvent->specCourse) }}">
                            {{ mb_strtolower($groupParticipant->groupEvent->specCourse->title, 'UTF-8') }} </a>
                    </span>
                    /
                    <span id="specCourse-description-text-principal" class="to-capitalize">
                        <a href="{{ route('admin.specCourses.groupEvents.show', $groupParticipant->groupEvent) }}">
                            {{ mb_strtolower($groupParticipant->groupEvent->title, 'UTF-8') }} </a>
                    </span>
                    /
                    <span id="specCourse-description-text-principal" class="to-capitalize">
                        {{ mb_strtolower($groupParticipant->title, 'UTF-8') }}
                    </span>

                </h5>

                <div id="specCourse-box-container" class="info-element-box mt-4 mb-4">

                    @include('admin.groupParticipant.partials.components._groupParticipant')

                </div>

                <div class="folder-inner-container">

                    <h5 class="title-header-show mb-4"> Participantes del grupo de: {{ $groupParticipant->title }} </span>
                    </h5>

                    <div class="table-border-style">
                        <div class="table-responsive">

                            <div class="action-btn-dropdown-container top-container-inner-box">
                                <button class="btn btn-primary" id="btn-register-participant-modal" data-url="">
                                    <i class="fa-solid fa-plus"></i> &nbsp;
                                    Añadir participantes
                                    <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                                </button>
                            </div>

                            <table id="participants-on-group-table" class="table table-hover"
                                data-url='{{ route('admin.specCourses.groupEvents.groupParticipants.show', $groupParticipant) }}'>
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Participante</th>
                                        <th>DNI</th>
                                        {{-- <th>Empresa</th> --}}
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
@endsection

@section('modals')
    @include('admin.groupParticipant.partials.modals._modal_store_participant')

@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/group-participants.js') }}"></script>
    
@endsection
