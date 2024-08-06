@extends('admin.common.layouts.masterpage')

@section('content')
    <div class="row content">

        <div class="main-container-page">

            <div class="card page-title-container">
                <div class="card-header">
                    <div class="total-width-container">
                        <h4>PASOS</h4>
                    </div>
                </div>
            </div>

            <div class="card-body card z-index-2 principal-container">

                <h5 class="title-header-show">
                    <i class="fa-solid fa-chevron-left fa-xs"></i>
                    <a href="{{ route('admin.forgettingCurve.index') }}">Inicio</a>
                    /
                    <a
                        href="{{ route('admin.forgettingCurve.show', $step->instance->forgettingCurve) }}">{{ $step->instance->forgettingCurve->title }}</a>
                    /
                    <span id="forgettingCurve-description-text-principal" class="to-capitalize">
                        {{ $step->instance->title }}
                    </span>
                    /
                    <span id="forgettingCurve-description-text-principal" class="to-capitalize">
                        {{ mb_strtolower($step->title, 'UTF-8') }}
                    </span>
                </h5>

                <div id="forgettingCurve-box-container" class="info-element-box mt-4 mb-4">

                    @include('admin.curveStep.partials.components._step_box')

                </div>

                @if ($step->type == 'video')
                    <div class="" id="container-video">
                        @include('admin.curveStep.partials.components._stepVideo')
                    </div>
                @else
                    <div class="principal-splitted-container">

                        <div class="principal-inner-container right">
                            <div class="inner-title-container">
                                <div id="btn-drowdown-chapters-list" class="btn-dropdown-container vertical show">
                                    <h5 class="title-header-show">
                                        Examen de tipo: {{ verifyCurveStepsType($step->type) }}
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

                                <div id="exams-list-container" class="related-dropdown-container table-container show">

                                    @include('admin.curveStep.partials.components._evaluation_main')

                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection

@section('modals')
    @include('admin.curveStep.partials.models._store_exam')
    @include('admin.curveStep.partials.models._edit_exam')
    @include('admin.curveStep.partials.models._edit_question')
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/admin/js/fcStep.js') }}"></script>
@endsection
