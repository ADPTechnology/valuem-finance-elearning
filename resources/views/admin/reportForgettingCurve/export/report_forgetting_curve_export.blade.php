<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>Curva del Olvido</th>
            <th>Nota mínima</th>
            <th>Curso</th>
            <th>N° de participante</th>
            <th>Participante</th>
            <th>Pasos completados (7mo)</th>
            <th>Fecha de finalización (7mo)</th>
            <th>Puntuación (7mo)</th>
            <th>Pasos completados (15to)</th>
            <th>Fecha de finalización (15to)</th>
            <th>Puntuación (15to)</th>
            <th>Calificación</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($forgettingCurves as $certification)
            @php
                $forgettingCurve = $certification->course->forgettingCurves->first();
            @endphp
            <tr>
                <td> {{ $certification->id }} </td>
                <td> {{ $forgettingCurve->title }} </td>
                <td> {{ $forgettingCurve->min_score }} </td>
                <td> {{ $certification->course->description }} </td>
                <td> {{ $certification->user->id }} </td>
                <td> {{ $certification->user->full_name_complete }} </td>
                <td> {{ getStepsCompleted($certification, getFcInstance($forgettingCurve, 7)) }} </td>
                <td> {{ getFcStepProgressEndDate($forgettingCurve, $certification, 7) }} </td>
                <td> {{ getFcInstanceStepProgressScore($forgettingCurve, $certification, 7) }} </td>

                <td> {{ getStepsCompleted($certification, getFcInstance($forgettingCurve, 15)) }} </td>
                <td> {{ getFcStepProgressEndDate($forgettingCurve, $certification, 15) }} </td>
                <td> {{ getFcInstanceStepProgressScore($forgettingCurve, $certification, 15) }} </td>
                @php
                    $minScore = $certification->course->forgettingCurves->first()->min_score;
                    $instances = $certification->course->forgettingCurves->first()->instances;
                @endphp

                <td> {{ addQualificationForgettingCurve($certification, $minScore, $instances) }} </td>
            </tr>
        @endforeach

    </tbody>

</table>
