<div class="modal-body">

    <h6>
        Información de participante:
    </h6>

    <table class="table table-hover">

        @php
            $pivotAssignable = $model->assignments->first()->pivot;
        @endphp


        <thead>
            <tr>
                <th>
                    {{ $model->id }}
                </th>
                <th>
                    @if ($flg_group)
                        {{ $model->title }}
                    @else
                        {{ $model->user->full_name_complete }}
                    @endif
                </th>
                <th>
                    {{ mb_strtoupper($assignable->status, 'UTF-8') }}
                </th>
            </tr>
        </thead>

    </table>

    <div class="part_assign_body">

        <div class="note-participants mb-3">

            <h6>
                Notas / Comentarios:
            </h6>

            <div>
                {!! $assignable->notes ?? '-' !!}
            </div>

        </div>

        <div>

            <h6>
                Archivos entregados:
            </h6>


            @foreach ($assignable->files as $file)
                <a href="{{ route('aula.specCourses.files.download', $file) }}">
                    <div class="assign_btn_file_download_cont mt-3">

                        <span class="download_btn_file_assign">
                            {{ $file->name }}
                            &nbsp;
                            <i class="fa-solid fa-download"></i>
                        </span>

                    </div>
                </a>
            @endforeach


        </div>

    </div>

    <div class="mt-4 col-4 pl-0">

        <label>
            Nota:
        </label>

        <input type="number" class="form-control @if ($assignment->flg_evaluable != 1) disabled @endif" name="points"
            @if ($assignment->flg_evaluable != 1) disabled @endif value="{{ $assignable->points }}">

    </div>

</div>


<div class="modal-footer">
    <button type="button" class="btn btn-secondary btn-close" data-dismiss="modal">Cerrar</button>
    <button type="submit" class="btn btn-primary btn-save" value=""
    @if ($assignment->flg_evaluable != 1)
    disabled
    @endif>
        Guardar
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
    </button>
</div>
