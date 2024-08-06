@extends('aula.common.layouts.masterpage')

@section('content')

    <div class="content global-container quiz">

        <div class="quiz-container quiz col-12">

            <div class="card quiz z-index-2">

                @php
                    $evaluation = $evaluations[$num_question];
                    $type_id = $question->question_type_id;
                @endphp

                @if (!is_null($exam))
                    <div class="info">

                        <h3> {{ $fcStepProgress->step->instance->forgettingCurve->title }} /
                            {{ $fcStepProgress->step->instance->title }} / {{ $fcStepProgress->step->title }} </h3>


                    </div>

                    <input type="hidden" name="examId" value="{{ $exam->id }}">

                    <ul id="progressbar" class="width-progress" style="--progressWidth: {{ count($evaluations) }}">

                        @foreach ($evaluations as $key => $eval)
                            @if ($selected_answers >= $key)
                                <a
                                    href="{{ route('aula.forgettingCurve.instances.evaluations.show', ['fcStepProgress' => $fcStepProgress, 'num_question' => $key + 1]) }}">
                                    <li class="{{ $key <= $num_question ? 'active' : '' }} progress-btn"
                                        style="--progressWidth: {{ count($evaluations) }}">

                                    </li>
                                </a>
                            @else
                                <li class="{{ $key <= $num_question ? 'active' : '' }} progress-btn"
                                    style="--progressWidth: {{ count($evaluations) }}">

                                </li>
                            @endif
                        @endforeach

                    </ul>

                    @if ($type_id == 1 || $type_id == 3)
                        @include('aula.viewParticipant.courses.evaluations.types.unique_answer', [
                            'route' => route('aula.forgettingCurve.instances.evaluations.show', [
                                $fcStepProgress,
                                $num_question,
                            ]),
                            'routeUpdate' => route('aula.forgettingCurve.instances.evaluations.update', [
                                $fcStepProgress,
                                $exam,
                                $num_question + 1,
                                $key + 1,
                                $evaluation->id,
                            ]),
                            'prev_active' => true,
                        ])
                    @elseif ($type_id == 2)
                        @include('aula.viewParticipant.courses.evaluations.types.multiple_answers', [
                            'route' => route('aula.forgettingCurve.instances.evaluations.show', [
                                $fcStepProgress,
                                $num_question,
                            ]),
                            'routeUpdate' => route('aula.forgettingCurve.instances.evaluations.update', [
                                $fcStepProgress,
                                $exam,
                                $num_question + 1,
                                $key + 1,
                                $evaluation->id,
                            ]),
                            'prev_active' => true,
                        ])
                    @elseif ($type_id == 4)
                        @include('aula.viewParticipant.courses.evaluations.types.fill_in_the_blank', [
                            'route' => route('aula.forgettingCurve.instances.evaluations.show', [
                                $fcStepProgress,
                                $num_question,
                            ]),
                            'routeUpdate' => route('aula.forgettingCurve.instances.evaluations.update', [
                                $fcStepProgress,
                                $exam,
                                $num_question + 1,
                                $key + 1,
                                $evaluation->id,
                            ]),
                            'prev_active' => true,
                        ])
                    @elseif ($type_id == 5)
                        @include('aula.viewParticipant.courses.evaluations.types.matching', [
                            'route' => route('aula.forgettingCurve.instances.evaluations.show', [
                                $fcStepProgress,
                                $num_question,
                            ]),
                            'routeUpdate' => route('aula.forgettingCurve.instances.evaluations.update', [
                                $fcStepProgress,
                                $exam,
                                $num_question + 1,
                                $key + 1,
                                $evaluation->id,
                            ]),
                            'prev_active' => true,
                        ])
                    @endif
                @endif

            </div>

        </div>

    </div>

@endsection
