@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container quiz">

        <div class="quiz-container quiz col-12">

            <div class="card quiz z-index-2">

                <div class="info">
                    <h3> {{ $fcStepProgress->step->instance->forgettingCurve->title }} /
                        {{ $fcStepProgress->step->instance->title }} / {{ $fcStepProgress->step->title }} </h3>

                    {{-- <span> {{ $fcStepProgress->step->instance->forgettingCurve->course->description }} </span> --}}
                </div>

                <div class="box-quiz-head">
                    <h3 class="fs-title title-fill"> Visualizar el siguiente video y responder las siguientes preguntas.</h3>
                </div>

                <fieldset class="fieldset-center">
                    <video id="chapter-video" class="embed-responsive-item video-forgettingCurve w-75" controls preload='auto'
                        class="video-js">
                        <source src="{{ verifyFile($video->file) }}" type="video/mp4">
                    </video>
                </fieldset>

                <div class="box-quiz-footer mt-4 mb-4">
                    <a href="" class="btn btn-primary"
                        onclick="event.preventDefault(); document.getElementById('quiz-start-form-{{ $step->id }}').submit();">
                        Responder preguntas
                        &nbsp;
                        <i class="fa-solid fa-person-circle-question"></i>
                    </a>
                    <form id="quiz-start-form-{{ $step->id }}" method="POST"
                        action="{{ route('aula.forgettingCurve.instances.evaluations.start', ['step' => $step, 'fcStepProgress' => $fcStepProgress]) }}">
                        @csrf
                    </form>
                </div>

            </div>

        </div>

    </div>
@endsection
