<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Fecha de creación</th>
            <th>Fecha de actualización</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($courseTypes as $course)
            <tr>
                <td> {{ $course->id }} </td>
                <td> {{ $course->name }} </td>
                <td> {{ $course->description }} </td>
                <td> {{ $course->created_at }} </td>
                <td> {{ $course->updated_at }} </td>
            </tr>
        @endforeach

    </tbody>

</table>
