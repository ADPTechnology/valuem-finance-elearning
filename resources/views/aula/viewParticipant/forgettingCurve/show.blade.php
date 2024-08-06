@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.forgettingCurve.index') }}">
                            <i class="fa-solid fa-circle-chevron-left"></i> CURVAS DEL OLVIDO
                        </a>
                        /
                        {{ $forgettingCurve->title }}
                    </h4>
                </div>
            </div>
        </div>

        <div class="card-body body-global-container card z-index-2 principal-container">
            <div class="course-container online-lessons">

                <div class="message-lesson-container">
                    <div class="message-title">
                        Evaluaciones del dia 7 y 15
                    </div>
                    <div class="message-content">
                        <i class="fa-solid fa-triangle-exclamation"></i> &nbsp;
                        Estimado usuario, realize las evaluaciones del dia 7 para poder activar las evaluaciones
                        del dia 15.
                    </div>
                </div>

                <div class="row navigation-boxes-container">

                    @php
                        $evaluationDay7 = true;
                    @endphp

                    @foreach ($forgettingCurve->instances as $instance)
                        @if ($instance->days_count == 7)
                            <a href="{{ route('aula.forgettingCurve.instances.show', ['fcInstance' => $instance, 'certification' => $certification]) }}"
                                class="link-box-navigation-course">
                                <div class="navigation-box online-lesson card">
                                    <div class="img-container">
                                        <img src="{{ asset('assets/aula/img/courses/online-lesson.png') }}" alt="">
                                    </div>
                                    <div class="text-nav-container">
                                        <span>
                                            Ev. del dia {{ $instance->days_count }}
                                        </span>
                                        <span class="bg-nav-course-box"></span>
                                    </div>
                                </div>
                            </a>

                            @php
                                $steps = $instance->steps;

                                foreach ($steps as $step) {
                                    $stepProgress = $step->fcStepProgress->where('certification_id', $certification->id)->first();
                                    if ($stepProgress) {
                                        if ($stepProgress->status == 'pending' || $stepProgress->status == 'in_progress') {
                                            $evaluationDay7 = false;
                                        }
                                    }
                                }

                            @endphp
                        @endif

                        @if ($instance->days_count == 15 && $evaluationDay7)
                            @php
                                $hasProgress = true;
                                $steps = $instance->steps;
                                foreach ($steps as $step) {
                                    $stepProgress = $step->fcStepProgress->where('certification_id', $certification->id)->first();
                                    if (!$stepProgress) {
                                        $hasProgress = false;
                                    }
                                }
                            @endphp

                            @if ($hasProgress)
                                <a href="{{ route('aula.forgettingCurve.instances.show', ['fcInstance' => $instance, 'certification' => $certification]) }}"
                                    class="link-box-navigation-course">
                                    <div class="navigation-box online-lesson card">
                                        <div class="img-container">
                                            <img src="{{ asset('assets/aula/img/courses/online-lesson.png') }}"
                                                alt="">
                                        </div>
                                        <div class="text-nav-container">
                                            <span>
                                                Ev. del dia {{ $instance->days_count }}
                                            </span>
                                            <span class="bg-nav-course-box"></span>
                                        </div>
                                    </div>
                                </a>
                            @endif
                        @endif
                    @endforeach

                </div>

            </div>

        </div>

    </div>
@endsection
