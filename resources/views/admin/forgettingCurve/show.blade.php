@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">

            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>CURVA DEL OLVIDO</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.forgettingCurve.index') }}">Inicio</a>
                    /
                    <span id="forgettingCurve-description-text-principal" class="to-capitalize">
                        {{ mb_strtolower($forgettingCurve->title, 'UTF-8') }}
                    </span>
                </h5>

                <div id="forgettingCurve-box-container" class="info-element-box mt-4 mb-4">

                    @include('admin.forgettingCurve.partials.components._forgettingCurve_box')

                </div>

                <div class="principal-splitted-container">

                    <div class="principal-inner-container left">

                        <div class="inner-title-container">
                            <div id="btn-drowdown-sections-list" class="btn-dropdown-container show">
                                <h5 class="title-header-show"> Instancias </h5>
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

                            @include('admin.forgettingCurve.partials.components._instances')
                        </div>

                    </div>

                    <div class="principal-inner-container right">
                        <div class="inner-title-container">
                            <div id="btn-drowdown-chapters-list" class="btn-dropdown-container vertical show">
                                <h5 class="title-header-show">
                                    Pasos
                                    <span id="top-steps-table-title-info">
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
                            <div id="steps-list-container" class="related-dropdown-container table-container show">

                                @include('admin.forgettingCurve.partials.components._steps_list_empty')

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('admin.forgettingCurve.partials.modals._edit', ['place' => 'edit'])
    @include('admin.forgettingCurve.fcStep.partials.modals._edit')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/instances.js') }}"></script>
@endsection
