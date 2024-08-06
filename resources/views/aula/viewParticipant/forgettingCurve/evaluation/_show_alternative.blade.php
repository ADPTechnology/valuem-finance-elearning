@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>Evaluación de la curva del olvido:
                        {{ $step->title }}</h4>
                </div>
            </div>
        </div>


        <div class="card-body body-global-container surveys card z-index-2 principal-container">

            <div class="step-survey-container">

                @include('aula.viewParticipant.forgettingCurve.evaluation.partials._alternatives_correct')

            </div>

        </div>

    </div>
@endsection
