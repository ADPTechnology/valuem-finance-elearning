<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>Abreviatura</th>
            <th>Rubro</th>
            <th>RUC</th>
            <th>Dirección</th>
            <th>Teléfono</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($companies as $company)

        <tr>
            <td> {{ $company->id }} </td>
            <td> {{ $company->description }} </td>
            <td> {{ $company->abbreviation ?? '-' }} </td>
            <td> {{ $company->rubric ?? '-' }} </td>
            <td> {{ $company->ruc }} </td>
            <td> {{ $company->address }} </td>
            <td> {{ $company->telephone ?? '-' }} </td>
            <td> {{ $company->active == 'S' ? 'Activo' : 'Inactivo' }} </td>
        </tr>

        @endforeach

    </tbody>

</table>
