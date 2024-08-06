@extends('aula.common.layouts.masterpage')

@section('navbar-extra-content')
    <nav class="navbar navbar-expand-lg main-navbar @yield('navbarClass')">
        <form class="form-inline mr-auto">
            <ul class="navbar-nav mr-3" style="display: block">
                <li>
                    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <div class="navbar-container-elearning">
                <div class="my-courses">
                    MIS EVALUACIONES
                </div>
                <span class="hashtag">
                    #HAMAVirtual
                </span>
            </div>
        </form>
    </nav>
@endsection

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.forgettingCurve.index') }}">
                            <i class="fa-solid fa-circle-chevron-left"></i> CURVAS DEL OLVIDO
                        </a>
                        <span> / {{ $fcInstance->forgettingCurve->title }} </span> /
                        <a
                            href="{{ route('aula.forgettingCurve.show', ['forgettingCurve' => $fcInstance->forgettingCurve, 'certification' => $certification]) }}">
                            EVALUACIONES
                        </a> /
                        EV. DEL {{ $fcInstance->days_count }} DÍA
                    </h4>
                </div>
            </div>
        </div>

        <div class="mt-3 card-body body-global-container card z-index-2 principal-container">

            @php
                $min_score = $fcInstance->forgettingCurve->min_score;
            @endphp

            <div class="index-module-title mb-3">
                Evaluaciones:
            </div>

            <div class="courses-cards-container">


                @foreach ($fcInstance->steps as $step)
                    @php
                        $stepProgress = $step->fcStepProgress->where('certification_id', $certification->id)->first();
                        $status = $stepProgress ? $stepProgress->status : 'pending';
                    @endphp

                    <div class="card evaluation-card">

                        <div class="evaluation-forgettingCurve-box">
                            <div class="course-img-container">
                                <img src="{{ verifyImage($step->file) }}">
                            </div>
                            @if ($status == 'finished')
                                <span class="evaluation-bg">
                                </span>
                            @endif
                        </div>

                        <div class="evaluation-card-body-box">

                            <div class="info-container">

                                <div class="evaluation-text-cont-box">
                                    <span class="subtitle-text">
                                        <i class="fa-regular fa-file-lines"></i>
                                        &nbsp;
                                        Titulo
                                    </span>
                                    <span class="content-text">
                                        {{ $step->title }}
                                    </span>
                                </div>

                                <div class="evaluation-text-cont-box">
                                    <span class="subtitle-text">
                                        <i class="fa-regular fa-file-lines"></i>
                                        &nbsp;
                                        Descripción
                                    </span>
                                    <span class="content-text">
                                        {{ $step->description ?? '-' }}
                                    </span>
                                </div>

                                <div class="evaluation-text-cont-box">
                                    <span class="subtitle-text">
                                        <i class="fa-regular fa-file-audio"></i>
                                        &nbsp;
                                        Tipo
                                    </span>
                                    <span class="content-text">
                                        {{ verifyCurveStepsType($step->type) }}
                                    </span>

                                </div>

                                <div class="evaluation-text-cont-box">
                                    <span class="subtitle-text">
                                        <i class="fa-solid fa-file-invoice" aria-hidden="true"></i>
                                        &nbsp;
                                        Estado
                                    </span>

                                    <span class="content-text">
                                        @if ($status == 'finished')
                                            Finalizado
                                        @elseif($status == 'in_progress')
                                            En progreso
                                        @elseif($status == 'pending')
                                            Pendiente
                                        @endif
                                    </span>

                                </div>

                                @if ($status == 'finished' && $step->type != 'reinforcement')
                                    <div class="evaluation-text-cont-box">
                                        <span class="subtitle-text">
                                            <i class="fa-solid fa-star"></i>
                                            &nbsp;
                                            Nota obtenida
                                        </span>
                                        <span class="content-text">
                                            {{ $stepProgress->score }}
                                        </span>
                                    </div>
                                @endif

                            </div>

                            <div class="state-start-info">

                                @if ($status == 'pending')
                                    <button type="button" class="btn variable-info btn-evaluation-start"
                                        data-send='{{ route('aula.forgettingCurve.instances.getInfoEvaluation', $step) }}'
                                        data-url="{{ route('aula.forgettingCurve.instances.evaluations.start', ['step' => $step, 'fcStepProgress' => $stepProgress]) }}">
                                        Iniciar &nbsp;
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </button>
                                @elseif ($status == 'in_progress')
                                    <a style="background-color: rgb(250, 135, 68)" class="variable-info" href=""
                                        onclick="event.preventDefault(); document.getElementById('quiz-start-form-{{ $step->id }}').submit();">
                                        Continuar &nbsp;
                                        <i class="fa-solid fa-chevron-right"></i>
                                    </a>
                                    <form id="quiz-start-form-{{ $step->id }}" method="POST"
                                        action="{{ route('aula.forgettingCurve.instances.evaluations.start', ['step' => $step, 'fcStepProgress' => $stepProgress]) }}">
                                        @csrf
                                    </form>
                                @elseif ($status == 'finished')
                                    <div class="variable-info" style="color: rgb(48, 189, 20)">
                                        Completado &nbsp;
                                        <i class="fa-regular fa-circle-check"></i>
                                    </div>
                                @endif

                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

        </div>
    </div>
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/aula/js/pages/forgettingCurveEv/forgettingCurve.js') }}"></script>
@endsection

@section('modals')
    <!-- START QUIZ MODAL -->

    <div class="modal fade" id="fcInstructions-modal" tabindex="-1" aria-labelledby="fcInstructions-modalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="instructionsModalLabel">
                        CARACTERÍSTICAS DE LA EVALUACIÓN
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div id="container-modal-ev">

                </div>

                <div class="modal-footer">

                    <form method="POST" class="fcEvaluation-start-form">
                        @csrf
                        <button type="button" class="btn btn-close" data-dismiss="modal">Cerrar</button>

                        <button type="submit" id="btn-start-evaluation" class="btn btn-send">Comenzar Examen</button>

                    </form>
                </div>

            </div>
        </div>
    </div>
@endsection
