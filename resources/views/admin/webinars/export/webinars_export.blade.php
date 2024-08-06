<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>Titulo</th>
            <th>Descripción</th>
            <th>Fecha</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($webinars as $webinar)
            <tr>
                <td> {{ $webinar->id }} </td>
                <td> {{ $webinar->title }} </td>
                <td> {{ $webinar->description ?? '-' }} </td>
                <td> {{ $webinar->date }} </td>
                <td> {{ $webinar->active == 'S' ? 'Activo' : 'Inactivo' }} </td>
            </tr>
        @endforeach

    </tbody>

</table>
