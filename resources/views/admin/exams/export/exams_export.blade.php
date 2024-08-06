<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>Titulo</th>
            <th>Empresa titular</th>
            <th>Curso</th>
            <th>Duración</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($exams as $exam)
            <tr>
                <td> {{ $exam->id }} </td>
                <td> {{ $exam->title }} </td>

                <td> {{ $exam->ownerCompany->name ?? '-' }} </td>
                <td> {{ $exam->course->description }} </td>

                <td> {{ $exam->exam_time . ' min.' }} </td>
                <td> {{ $exam->active == 'S' ? 'Activo' : 'Inactivo' }} </td>
            </tr>
        @endforeach

    </tbody>

</table>
