<form class="steps" method="POST" action="{{ $routeUpdate }}" id="">

    @method('PATCH')

    @csrf

    <fieldset>

        <div class="info-question">
            <p> Escribe la respuesta correcta </p>
        </div>

        <div class="alert-container">
            <p>
                <i class="fa-solid fa-circle-exclamation fa-bounce fa-lg"></i> &nbsp;
                Debes seleccionar una opción para continuar
            </p>
        </div>

        <div class="box-quiz-head">
            <h2 class="fs-title title-fill"> {{ $question->clean_statement }} </h2>

            <input type="hidden" name="question" value="{{ $question->id }}">
        </div>

        <div class="box-quiz-body">

            @if ($prev_active)
                <div class="btn-prev">
                    @if ($num_question + 1 != '1')
                        <a href="{{ $route }}">
                            <i class="fa-solid fa-angles-left"></i>
                        </a>
                    @endif
                </div>
            @endif

            <div class="box-answers">
                @foreach ($question->alternatives as $i => $alternative)
                    <div class="hs_firstname field hs-form-field answers-colors box-fill">

                        <input id="{{ $alternative->id }}" class="input-txt" name="alternative[]" autocomplete="off"
                            placeholder="Escribe tu respuesta" required type="text"
                            value="{{ $evaluation->selected_alternatives != null ? explode(',', $evaluation->selected_alternatives)[$i] : '' }}">

                    </div>
                @endforeach

            </div>

            <div class="btn-save save-fill">
                @if ($num_question + 1 != count($evaluations))
                    <button type="submit" name="next" class="next action-button txt-submit" value="Guardar">
                        <i class="fa-solid fa-angles-right"></i>
                    </button>
                @endif
                @if ($num_question + 1 == count($evaluations))
                    <button type="submit" id="submit" class="hs-button primary large action-button next txt-submit"
                        value="Finalizar">
                        <i class="fa-solid fa-check"></i>
                    </button>
                @endif
            </div>

        </div>

    </fieldset>

</form>
