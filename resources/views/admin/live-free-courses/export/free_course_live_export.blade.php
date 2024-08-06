<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>Tipo</th>
            <th>Nombre</th>
            <th>Subtitulo</th>
            <th>Fecha</th>
            <th>Hora de inicio</th>
            <th>Hora de fin</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($freeCoursesLive as $course)
            <tr>
                <td> {{ $course->id }} </td>
                <td> {{ config('parameters.course_types')[$course->course_type] ?? '-' }} </td>
                <td> {{ $course->description }} </td>
                <td> {{ $course->subtitle ?? '-' }} </td>
                <td> {{ $course->date }} </td>
                <td> {{ $course->time_start }} </td>
                <td> {{ $course->time_end }} </td>
                <td> {{ $course->active == 'S' ? 'Activo' : 'Inactivo' }} </td>
            </tr>
        @endforeach

    </tbody>

</table>