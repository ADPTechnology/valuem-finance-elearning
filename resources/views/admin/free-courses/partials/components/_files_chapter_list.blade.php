@if ($files->count() == 0)
    <section class="mt-1 mb-1">
        <div class="alert alert-warning">
            <i class="fa-solid fa-circle-exclamation"></i>
            &nbsp;
            Aún no se han cargado documentos para este capítulo.
        </div>
    </section>
@else
    <table id="table_chapter_files" class="table table-striped table-hover">

        <thead>
            <th>N°</th>
            <th>Documento</th>
            <th>Fecha de carga</th>
            <th>Eliminar</th>
        </thead>

        <tbody>

            @foreach ($files as $file)
                <tr>
                    <td>
                        {{ $file->id }}
                    </td>
                    <td>
                        <a data-id="{{ $file->id }}" data-original-title="download"
                            href="{{ route('admin.freeCourses.chapters.downloadFile', $file) }}">
                            {{ $file->name }}
                        </a>
                    </td>
                    <td>
                        {{ $file->created_at }}
                    </td>
                    <td class="text-center">
                        <button data-id="{{ $file->id }}" data-original-title="delete" type="button"
                            data-url="{{ route('admin.freeCourses.chapters.deleteFile', [$file, $chapter]) }}"
                            class="me-3 edit btn btn-danger btn-sm deleteChapterFile">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif
