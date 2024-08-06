<table>

    <thead>
        <tr>
            <th>N°</th>
            <th>DNI</th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Empresa</th>
            <th>Estado</th>
        </tr>
    </thead>

    <tbody>

        @foreach ($users as $user)

        <tr>
            <td> {{ $user->id }} </td>
            <td> {{ $user->dni }} </td>
            <td> {{ $user->full_name_complete }} </td>
            <td> {{ $user->email }} </td>
            <td> {{ config('parameters.roles')[$user->role] ?? '-' }} </td>
            <td> {{ $user->company->description ?? '-' }} </td>
            <td> {{ $user->active == 'S' ? 'Activo' : 'Inactivo' }} </td>
        </tr>

        @endforeach

    </tbody>

</table>
