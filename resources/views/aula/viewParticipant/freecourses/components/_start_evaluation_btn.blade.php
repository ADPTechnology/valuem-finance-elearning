@if ($section->section_chapters_count != getFinishedChaptersCountBySection($section))

    <div class="text-center badge badge-pill badge-info" style="white-space: normal;"
        id="incomplete-flag-{{ $section->id }}">
        <span><i class="fa-solid fa-lock"></i></span> &nbsp;
        Completa todos los videos para realizar tu evaluación.
    </div>
@else
    @php
        $productCertificationWithPivot = $evaluation
            ->userEvaluations->first();
    @endphp

    @if ($productCertificationWithPivot && $productCertificationWithPivot->pivot->status === 'finished')
        <div>
            <span><i class="fa-solid fa-circle-check"></i></span> &nbsp;
            <span>
                Calificación:
                {{ $productCertificationWithPivot->pivot->points }}
            </span>
        </div>
    @elseif ($productCertificationWithPivot && $productCertificationWithPivot->pivot->status === 'in_progress')
        <a class="button btn mt-4" href=""
            onclick="event.preventDefault(); document.getElementById('quiz-start-form-{{ $evaluation->id }}').submit();"
            style="background-color: rgb(250, 135, 68); color: white">
            Reanudar evaluación
        </a>
        <form id="quiz-start-form-{{ $evaluation->id }}" method="POST"
            action="{{ route('aula.freecourse.evaluations.start', ['evaluation' => $evaluation, 'productCertification' => $productCertification_id]) }}">
            @csrf
        </form>
    @else
        <span class="button btn mt-4 button-evaluation-start"
            data-url="{{ route('aula.freecourse.evaluations.start', ['evaluation' => $evaluation, 'productCertification' => $productCertification_id]) }}"
            data-send="{{ route('aula.freecourse.evaluations.information', $evaluation) }}"
            style="background-color: rgb(223, 31, 17); color: white">
            Iniciar evaluación
        </span>
    @endif

@endif
