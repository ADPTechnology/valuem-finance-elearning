<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>Titulo</th>
            <th>Descripción</th>
            <th>Nota mínima</th>
            <th>Tipo de curso</th>
            <th>Cursos</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($forgettingCurves as $curve)
            <tr>
                <td> {{ $curve->id }} </td>
                <td> {{ $curve->title }} </td>
                <td> {{ $curve->description ?? '-' }} </td>
                <td> {{ $curve->min_score }} </td>
                <td> {{ $curve->courses->first()->type->name ?? '-' }} </td>
                <td>
                    <ul>
                        @foreach ($curve->courses as $course)
                            <li>- {{ $course->description }} </li>
                        @endforeach
                    </ul>
                </td>
                <td> {{ $curve->active == 'S' ? 'Activo' : 'Inactivo' }} </td>
            </tr>
        @endforeach

    </tbody>

</table>
