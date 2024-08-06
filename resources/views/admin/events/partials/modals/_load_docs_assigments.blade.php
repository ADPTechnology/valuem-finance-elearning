@if ($files->count() > 0)
    <table id="docsInCompany" class="table table-striped table-hover">
        <thead>
            <th>N°</th>
            <th>Documento</th>
            <th>Fecha de carga</th>
            <th>Descargar</th>
            <th>Eliminar</th>
        </thead>
        <tbody>
            @foreach ($files as $file)
                <tr>
                    <td>
                        {{ $file->id }}
                    </td>
                    <td>
                        {{ $file->name }}
                    </td>
                    <td>
                        {{ $file->created_at }}
                    </td>
                    <td class="text-center">
                        <a data-id="{{ $file->id }}"
                            href="{{ route('admin.specCourses.events.assignments.dowloadFile', $file) }}"
                            data-original-title="download" class="edit btn btn-primary btn-sm downloadFile"><i
                                class="fa-solid fa-download"></i></a>
                    </td>
                    <td class="text-center">
                        <a href="javascript:void(0)" data-id="{{ $file->id }}"
                            data-url="{{ route('admin.specCourses.events.assignments.destroyFile', ['file' => $file, 'assignment' => $assignment]) }}"
                            data-original-title="delete" class="deleteFile btn btn-danger btn-sm">
                            <i class="fa-solid fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@else
    <div class="alert alert-warning w-100">
        No hay documentos cargados.
    </div>
@endif
