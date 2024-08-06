@php
    $assignable = $assignment->assignables->first();
@endphp

<div class="form-group container-label">
    <label>Archivos subidos por usted </label>
    <div class="form-group files-assignment-modal">
        @forelse ($assignable->files as $file)
            <div class="file-assignment">
                <div class="file-assignment-name d-flex justify-content-between">
                    <a href="{{ route('aula.specCourses.assignment.downloadFile', $file) }}"
                        class="file-name btn btn-success">{{ $file->name }}
                        &nbsp;
                        <i class="fa-solid fa-download"></i>
                    </a>
                    @if ($assignment->date_limit >= getCurrentDate() && $assignable->status != 'revisado')
                        <button type="button"
                            data-url="{{ route('aula.specCourses.assignment.deleteFileParticipant', ['file' => $file, 'assignment' => $assignment]) }}"
                            class="btn btn-primary m-1 delete-file-participant-btn">
                            <i class="fa-solid fa-trash"></i>
                            &nbsp;
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                        </button>
                    @endif

                </div>
            </div>
        @empty
            <div class="file-assignment p-1">
                <p class="mb-3 font-italic">Sin archivos subidos.</p>
            </div>
        @endforelse

    </div>
</div>

@if ($assignment->date_limit >= getCurrentDate() && $assignable->status != 'revisado')
    <div class="form-row">
        <div class="form-group col-12">
            <label>Suba sus archivo * </label>
            <input type="file" multiple name="files[]" class="form-control input-file-folder">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Comentario (opcional)</label>
            <textarea name="content" class="summernote-card-editor" id="card-content-register">
                {!! $assignable->notes !!}
            </textarea>

        </div>
    </div>

    <button type="submit" class="btn btn-primary btn-save">
        Enviar
        <i class="fa-solid fa-spinner fa-spin loadSpinner loadSpinnerClean ms-1"></i>
    </button>
@endif

@if ($assignment->date_limit < getCurrentDate() && $assignable->status == 'pendiente')
    <div class="form-group">
        <label>Comentario:</label>
        <div class="summernote-card-editor">
            {!! $assignable->notes ?? '-' !!}
        </div>
    </div>

    <div class="alert alert-danger mt-4">
        <p>La fecha límite para enviar la asignación ha llegado a su límíte.</p>
        <p>No se pueden subir más archivos.</p>
    </div>
@endif

@if ($assignment->date_limit < getCurrentDate() && $assignable->status == 'entregado')
    <div class="form-group">
        <label>Comentario:</label>
        <div class="summernote-card-editor">
            {!! $assignable->notes ?? '-' !!}
        </div>
    </div>
    <div class="alert alert-info mt-4">
        <p>La fecha límite para enviar la asignación ha llego a su límíte.</p>
        <p>En horabuena, el instructor lo calificará cuanto antes.</p>
    </div>
@endif

@if ($assignable->status == 'revisado')
    <div class="form-group">
        <label>Comentario:</label>
        <div class="summernote-card-editor">
            {!! $assignable->notes ?? '-' !!}
        </div>
    </div>
    <div class="alert alert-success mt-4">
        <p>La asignación ha sido revisada.</p>
        <p>Su nota es: {{ $assignable->points }}</p>
    </div>
@endif
