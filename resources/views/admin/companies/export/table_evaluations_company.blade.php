<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>DNI</th>
            <th>Apellidos y nombres</th>
            <th>Curso</th>
            <th>Evento</th>
            <th>Fecha</th>
            <th>Nota</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($evaluationsCompany as $evaluation)
            <tr>
                <td> {{ $evaluation->id }} </td>
                <td> {{ $evaluation->user->dni }} </td>
                <td> {{ $evaluation->user->full_name_complete }} </td>
                <td> {{ $evaluation->event->exam->course->description }} </td>
                <td> {{ $evaluation->event->description }} </td>
                <td> {{ $evaluation->event->date }} </td>
                <td> {{ $evaluation->score ?? '-' }} </td>
            </tr>
        @endforeach

    </tbody>

</table>
