<div class="part-assignments-table-container p-3">

    <h5 class="assign__name__dynamic">
        {{ $assignment->title }}
    </h5>

    <div class="mb-3 d-flex justify-content-between">
        <div>
            <div class="little-text">
                Descripción:
            </div>
            <div>
                {{ $assignment->description ?? '-' }}
            </div>
        </div>

        <div class="download_btn_file_assign__cont">

            <div class="little-text">
                Archivo (s):
            </div>

            @forelse ($assignment->files as $file)
                <a href="{{ route('aula.specCourses.files.download', $file) }}">
                    <div class="file-name btn btn-primary m-1 w-100">
                        {{ $file->name }}
                        &nbsp;
                        <i class="fa-solid fa-download"></i>
                    </div>
                </a>
            @empty
                <div class="little-text font-italic">
                    No hay archivos adjuntos
                </div>
            @endforelse

        </div>
    </div>

    <div class="mb-2">
        <h6>
            @if ($assignment->flg_groupal == 1)
                Información del grupo:
            @else
                Información:
            @endif
        </h6>
    </div>

    <table class="table table-hover">
        <thead>

            @if ($assignment->flg_groupal == 1)
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nombre del grupo</th>
                    <th scope="col">Participantes</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Nota</th>
                    <th scope="col">Entregar</th>
                </tr>
            @else
                <tr>
                    <th scope="col">Código</th>
                    <th scope="col">Nombre</th>
                    <th scope="col">Estado</th>
                    <th scope="col">Nota</th>
                    <th scope="col">Entregar</th>
                </tr>
            @endif
        </thead>
        <tbody>
            @php
                $assignable = $assignment->assignables->first();
            @endphp

            @if ($assignment->flg_groupal == 1)

                @php
                    $participantGroup = $assignment->participantGroups->first();
                @endphp

                <tr>
                    <td scope="row">
                        {{ $participantGroup->id }}
                    </td>
                    <td>
                        {{ $participantGroup->title }}
                    </td>
                    <td>
                        <ul>
                            @foreach ($participantGroup->participants as $participant)
                                <li> {{ $participant->full_name_complete }} </li>
                            @endforeach
                        </ul>
                    </td>

                    <td>
                        {{ mb_strtoupper($participantGroup->pivot->status, 'UTF-8') }}
                    </td>
                    <td>
                        {{ $participantGroup->pivot->points ?? '-' }}
                    </td>
                    <td>
                        <div class="view_part_assignment-bnt">
                            <div class="view_part_assignment enable active"
                                data-send="{{ route('aula.specCourses.assignment.showAssignable', $assignment) }}"
                                data-url="{{ route('aula.specCourses.assignment.storeAssignment', $assignment) }}">
                                <i class="fa-solid fa-file-arrow-up" style="font-size: x-large"></i>
                            </div>
                        </div>
                    </td>
                <tr>
                @else
                    @php
                        $certification = $assignment->certifications->first();
                    @endphp
                <tr>
                    <td scope="row">
                        {{ $certification->id }}
                    </td>
                    <td>
                        {{ $certification->user->full_name_complete }}
                    </td>
                    <td>
                        {{ mb_strtoupper($certification->pivot->status, 'UTF-8') }}
                    </td>
                    <td>
                        {{ $certification->pivot->points ?? '-' }}
                    </td>
                    <td>
                        <div class="view_part_assignment-bnt">
                            <div class="view_part_assignment enable active"
                                data-send="{{ route('aula.specCourses.assignment.showAssignable', $assignment) }}"
                                data-url="{{ route('aula.specCourses.assignment.storeAssignment', $assignment) }}">
                                <i class="fa-solid fa-file-arrow-up" style="font-size: x-large"></i>
                            </div>
                        </div>

                    </td>
                </tr>
            @endif


        </tbody>
    </table>

</div>
