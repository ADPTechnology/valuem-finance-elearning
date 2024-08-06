<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>Nombre</th>
            <th>Fecha de registro</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($ownerCompanies as $company)
            <tr>
                <td> {{ $company->id }} </td>
                <td> {{ $company->name }} </td>
                <td> {{ $company->created_at }} </td>
            </tr>
        @endforeach

    </tbody>

</table>
