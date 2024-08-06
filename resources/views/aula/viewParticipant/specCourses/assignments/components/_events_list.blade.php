<div class="part-assignments-table-container p-3">
    <div class="mb-2">
        <h6>
            Eventos
        </h6>
    </div>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Código</th>
                <th scope="col">Descripción</th>
                <th scope="col">Tipo</th>
                <th scope="col">Fecha</th>
                <th scope="col">Instructor</th>
                <th scope="col">Asignaciones</th>
            </tr>
        </thead>
        <tbody>

            @foreach ($events as $event)
                <tr>
                    <td scope="row">
                        {{ $event->id }}
                    </td>
                    <td>
                        {{ $event->description }}
                    </td>
                    <td>
                        {{ config('parameters.event_types')[$event->type] }}
                    </td>
                    <td>
                        {{ $event->date }}
                    </td>
                    <td>
                        {{ $event->user->full_name_complete }}
                    </td>
                    <td>
                        <div class="view_part_assignment-bnt text-center">
                            <a class="view_part_assignment enable active" style="color: #de1a2b"
                                href="{{ route('aula.specCourses.assignment.show', $event) }}">
                                <i class="fa-solid fa-eye fa-lg"></i>
                            </a>
                        </div>

                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

</div>
