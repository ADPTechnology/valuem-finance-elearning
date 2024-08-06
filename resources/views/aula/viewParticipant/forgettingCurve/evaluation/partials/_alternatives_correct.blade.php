<div class="group-title mb-3">

    Visualizar las respuestas correctas
</div>

<div class="questions-container">
    @foreach ($questions as $question)
        <div class="question-box select-multiple">

            <div class="question-description">
                {{ $question->statement }}
            </div>

            <div class="question-options-group">

                @if ($question->question_type_id == 5)
                    @foreach ($question->alternatives as $alternative)
                        <div class="question-option">

                            <span>
                                {{ $alternative->description }} ----------->
                                {{ $alternative->droppableOption->description }}
                            </span>
                        </div>
                    @endforeach
                @elseif ($question->question_type_id == 4)
                    @foreach ($question->alternatives as $key => $alternative)
                        <div class="question-option">
                            <span>
                                {{ $key + 1 }}. {{ $alternative->description }}
                            </span>
                        </div>
                    @endforeach
                @else
                    @foreach ($question->alternatives as $alternative)
                        <div class="question-option forgettingCurve-question-option">
                            <input class="input-radio-survey" {{ $alternative->is_correct ? 'checked' : '' }} disabled
                                type="radio" id="{{ $alternative->id }}" name="option-{{ $alternative->id }}"
                                value='{{ $alternative->description }}'>
                            <label for="{{ $alternative->id }}">
                                <span class="circle-label-option">
                                </span>
                            </label>
                            <span>
                                {{ $alternative->description }}
                            </span>
                        </div>
                    @endforeach
                @endif

            </div>

        </div>
    @endforeach
</div>

<div class="btn-survey-container">

    <a href="{{ route('aula.forgettingCurve.instances.show', ['fcInstance' => $fcInstance, 'certification' => $certification]) }}"
        id="btn-submit-survey" class="btn btn-danger btn-survey-submit" style="color:#fff">
        Finalizar
    </a>

</div>
