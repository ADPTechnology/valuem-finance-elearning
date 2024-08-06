<div class="modal-body">

    @if (!$fcStep->video()->exists() || $fcStep->video->questions->isEmpty())
        <div class="alert alert-danger">
            <i class="fas fa-circle-exclamation"></i>
            &nbsp;
            Aún no se ha subido el video o no hay preguntas para este paso. Vuelva más tarde.
        </div>
    @else
        <div class="subtitle">
            Lea las siguientes instrucciones antes de comenzar la evaluación:
        </div>

        <ul>
            <li>
                Tipo de evaluación: <strong class="text-italic">{{ verifyCurveStepsType($fcStep->type) }}</strong>
            </li>
            <li>
                Tiempo de examen: indefinido.
            </li>
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
            @if ($fcStep->type == 'video')
                Al ser una evaluación de video, se le presentará un video y luego tendrá que responder las preguntas
                que se
                le presente.
            @endif
        </div>
    @endif

</div>
