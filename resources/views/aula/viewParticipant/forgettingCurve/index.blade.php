@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>Curva del olvido</h4>
                </div>
            </div>
        </div>

        <div class="card-body body-global-container card z-index-2 principal-container">

            <div class="info-count-courses">
                Hay <span> {{ count($certifications) }} </span> certificacion(es) de especialización en total para la curva
                del olvido
            </div>

            <div class="courses-cards-container">

                @forelse($certifications as $certification)
                    <div class="card course-card">

                        <div class="course-img-container">
                            <img class="card-img-top course-img"
                                src="{{ verifyImage($certification->course->forgettingCurves->first()->file) }}"
                                alt="{{ $certification->course->forgettingCurves->first()->title }}">
                        </div>

                        <div class="card-body">

                            <div class="start-button-container">
                                <a
                                    href="{{ route('aula.forgettingCurve.show', ['forgettingCurve' => $certification->course->forgettingCurves->first(), 'certification' => $certification]) }}">
                                    Ingresar &nbsp;
                                    <i class="fa-solid fa-chevron-right"></i>
                                </a>
                            </div>

                            <div class="course-title-box">
                                CURVA: {{ $certification->course->forgettingCurves->first()->title }}
                            </div>
                            <div class="course-title-box">
                                Curso: {{ $certification->course->description }}
                            </div>

                            <div class="instructor-name-box">
                                @foreach ($certification->course->forgettingCurves->first()->instances as $instance)
                                    <div>
                                        <span>Día {{ $instance->days_count }}</span>
                                        <br>
                                        @php
                                            $complete = 0;
                                            foreach ($instance->steps as $step) {
                                                $stepProgress = $step->fcStepProgress->where('certification_id', $certification->id)->first();
                                                if ($stepProgress) {
                                                    $stepProgress->status == 'finished' ? $complete++ : '';
                                                }
                                            }
                                        @endphp
                                        <span>Ev. completadas: {{ $complete }}/3</span>
                                    </div>
                                @endforeach
                            </div>

                        </div>

                    </div>
                @empty
                    <h4 class="text-center empty-records-message"> No hay certificaciones para la curva del olvido aún </h4>
                @endforelse
            </div>


        </div>

    </div>
@endsection
