<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>Descripción</th>
            <th>Titular</th>
            <th>Distrito</th>
            <th>Provincia</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($miningUnits as $miningUnit)
            <tr>
                <td> {{ $miningUnit->id }} </td>
                <td> {{ $miningUnit->description }} </td>
                <td> {{ $miningUnit->owner ?? '-' }} </td>
                <td> {{ $miningUnit->district ?? '-' }} </td>
                <td> {{ $miningUnit->Province ?? '-' }} </td>
            </tr>
        @endforeach

    </tbody>

</table>
