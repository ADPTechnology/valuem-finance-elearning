<div class="modal-body">
    @if ($evaluation->exam->questions->isEmpty())
        <div class="alert alert-danger">
            <i class="fas fa-circle-exclamation"></i>
            &nbsp;
            Aún no hay preguntas para esta evaluación. Vuelva más tarde.
        </div>
    @else
        <div class="subtitle">
            Lea las siguientes instrucciones antes de comenzar la evaluación:
        </div>
        <ul>
            <li>
                Tiempo de examen {{ $evaluation->exam->exam_time }} minutos.
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
    @endif
</div>
