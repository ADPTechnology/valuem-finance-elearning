<div class="modal-body">
    @if ($fcStep->exams->isEmpty() || $fcStep->exams->first()->questions->isEmpty())
        <div class="alert alert-danger">
            <i class="fas fa-circle-exclamation"></i>
            &nbsp;
            Aún no hay evaluación o preguntas para este paso. Vuelva más tarde.
        </div>
    @else
        <div class="subtitle">
            Lea las siguientes instrucciones antes de comenzar la evaluación:
        </div>

        <ul>
            <li>
                Tipo de evaluación: <strong class="text-italic">{{ verifyCurveStepsType($fcStep->type) }}</strong>
            </li>
            @if ($fcStep->type == 'evaluation')
                <li>
                    Tiempo de examen {{ $fcStep->exams->first()->exam_time }} minutos.
                </li>
            @else
                <li>
                    Tiempo de examen: indefinido.
                </li>
            @endif
            <li>
                Una vez comenzado el examen debe permanecer en la página.
            </li>
            <li>
                No abrir o visualizar otras páginas mientras está desarrollando la evaluación, ya que la
                plataforma lo detectará como inactividad y cerrará la sesión automáticamente.
            </li>
            <li>
                Trabaje individualmente y no use su teléfono celular o páginas web para responder esta
                evaluación.
            </li>
        </ul>
        <div class="alert alert-warning">
            <i class="fas fa-circle-exclamation"></i>
            &nbsp;
            <strong>Importante:</strong>
            <br>
            @if ($fcStep->type == 'reinforcement')
                Al ser una evaluación de reforzamiento, luego de responder cada pregunta se verá la respuesta correcta.
            @endif
            @if ($fcStep->type == 'evaluation')
                Al ser una evaluación normal, no se podrá ver las respuestas correctas. Tómelo como una evaluación
                habitual.
            @endif
        </div>
    @endif
</div>
