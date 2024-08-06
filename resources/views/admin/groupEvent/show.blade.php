@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">

            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>GRUPO DE EVENTO</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.specCourses.index') }}">Inicio</a>
                    /
                    <span id="specCourse-description-text-principal" class="to-capitalize">
                        <a href="{{ route('admin.specCourses.show', $groupEvent->specCourse) }}">
                            {{ mb_strtolower($groupEvent->specCourse->title, 'UTF-8') }} </a>
                    </span>
                    /
                    <span id="specCourse-description-text-principal" class="to-capitalize">
                        {{ mb_strtolower($groupEvent->title, 'UTF-8') }}
                    </span>

                </h5>

                <div id="specCourse-box-container" class="info-element-box mt-4 mb-4">

                    @include('admin.groupEvent.partials.components._groupEvent')

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

                        <div id="modules-list-container"
                            class="sections-list-container related-dropdown-container mt-0 show">

                            @include('admin.groupEvent.partials.components._modules_list')
                        </div>

                    </div>

                    <div class="principal-inner-container right">

                        <div class="inner-title-container">
                            <div id="btn-drowdown-chapters-list" class="btn-dropdown-container vertical show">
                                <h5 class="title-header-show">
                                    Eventos
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

                            <div id="events-list-container" class="related-dropdown-container table-container show">

                                @include('admin.specCourses.partials.components._events_list_empty')

                            </div>
                        </div>

                    </div>

                </div>

                <div class="folder-inner-container">

                    <h5 class="title-header-show mb-4"> Grupos de Participantes: </span> </h5>

                    <div class="table-border-style">
                        <div class="table-responsive">

                            <div class="action-btn-dropdown-container top-container-inner-box">
                                <button class="btn btn-primary" id="btn-register-groupParticipant-modal"
                                    {{-- data-url="{{ route('admin.specCourses.events.create') }}" --}}
                                    data-store="{{ route('admin.specCourses.groupEvents.groupParticipants.store', $groupEvent) }}"
                                    data-toggle="modal" data-target="#registerGroupParticipantModal">
                                    <i class="fa-solid fa-plus"></i> &nbsp;
                                    Crear Grupo de Participantes
                                    <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                                </button>
                            </div>

                            <table id="group-participants-table" class="table table-hover"
                                data-url='{{ route('admin.specCourses.groupEvents.groupParticipants.getDataTable', $groupEvent) }}'>
                                <thead>
                                    <tr>
                                        <th>N°</th>
                                        <th>Titulo</th>
                                        <th>Descripción</th>
                                        <th>Creado el</th>
                                        <th>Actualizado el</th>
                                        <th>Estado</th>
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
    @include('admin.specCourses.partials.modals._edit_group_event', ['place' => 'index'])

    @include('admin.groupEvent.partials.modals._register_module')
    @include('admin.specCourses.partials.modals._edit_module')

    @include('admin.specCourses.partials.modals._register_event', ['place' => 'index'])
    @include('admin.specCourses.partials.modals._edit_event', ['place' => 'index'])

    @include('admin.groupEvent.partials.modals._register_group_participant')
    @include('admin.groupEvent.partials.modals._edit_group_participant');
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/spec-courses.js') }}"></script>
    <script type="module" src="{{ asset('assets/admin/js/group-participants.js') }}"></script>
@endsection
