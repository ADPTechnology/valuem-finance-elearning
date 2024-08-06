@if ($files->count() == 0)
    <section class="mt-1 mb-1">
        <div class="alert alert-warning">
            <i class="fa-solid fa-circle-exclamation"></i>
            &nbsp;
            Aún no se han cargado documentos para esta usuario.
        </div>
    </section>
@else
    <table id="docsInParticipant" class="table table-striped table-hover">

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
                        <a data-id="{{ $file->id }}" href="{{ route('admin.participant.downloadDocument', $file) }}"
                            data-original-title="download" class="me-3 edit btn btn-primary btn-sm downloadFile"><i
                                class="fa-solid fa-download"></i></a>
                    </td>
                    <td class="text-center">
                        <button data-id="{{ $file->id }}" data-original-title="delete" type="button"
                            data-url="{{ route('admin.participant.deleteDocument', ['file' => $file, 'participant' => $participant]) }}"
                            class="me-3 edit btn btn-danger btn-sm deleteFile">
                            <i class="fa-solid fa-trash"></i>
                        </button>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
