<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>Capacidad</th>
            <th>Fecha de registro</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($rooms as $room)
            <tr>
                <td> {{ $room->id }} </td>
                <td> {{ $room->description }} </td>
                <td> {{ $room->capacity }} </td>
                <td> {{ $room->created_at }} </td>
                <td> {{ $room->active == 'S' ? 'Activo' : 'Inactivo' }} </td>
            </tr>
        @endforeach

    </tbody>

</table>
