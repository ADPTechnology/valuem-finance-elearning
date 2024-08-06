<div class="img-element-container">
    <img src="{{verifyImage($specCourse->file)}}">
</div>
<div class="info-element-general-container">
    <div class="info-name-element-container">
        <div class="name-info mb-2">
            {{$specCourse->title}}
        </div>

        <div class="subtitle-cont">
            <div class="subt-text little-text">Subtítulo: </div>
            <span>
                {{ $specCourse->subtitle ?? '-' }}
            </span>
        </div>

        <div class="counts-cont text-no-wrap">
            <div class="sections-cont">
                <span class="little-text little-text-width">Módulos: </span>
                <span class="text-bold">
                    {{$specCourse->modules_count}}
                </span>
            </div>
            <div class="chapters-cont">
                <span class="little-text little-text-width">Grupos de Eventos: </span>
                <span class="text-bold">
                    {{$specCourse->group_events_count}}
                </span>
            </div>
            <div class="files-cont">
                <span class="little-text little-text-width">Archivos: </span>
                <span class="text-bold">
                    {{$specCourse->files_count}}
                </span>
            </div>
        </div>
    </div>
    <div class="element-status-cont ">

        <div class="status-icon-container border-0">
            <div class="status-cont text-no-wrap">
                <span class="text-info-stat little-text"> Estado: &nbsp;</span>
                {!! getStatusButton($specCourse->active) !!}
            </div>
        </div>

        <div class="pt-2">
            <div class="d-flex justify-content-between align-items-center gap-1">
                <span class="little-text text-bold">
                    Fecha de creación:
                </span>
                <span>
                    {{ $specCourse->created_at }}
                </span>
            </div>
            <div class="d-flex justify-content-between align-items-center gap-1 mt-1">
                <span class="little-text text-bold">
                    Fecha de actualización:
                </span>
                <span>
                    {{ $specCourse->updated_at }}
                </span>
            </div>

        </div>

    </div>

    <div class="action-box info-element-box">
        <div class="btn-action-container">
            <span id="specCourse-edit-btn" class="edit-btn editSpecCourse"
                data-url="{{ route('admin.specCourses.update', $specCourse) }}"
                data-send="{{ route('admin.specCourses.edit', $specCourse) }}">
                <i class="fa-solid fa-pen-to-square"></i>
            </span>
            @if($specCourse->modules_count == 0 && $specCourse->group_events_count == 0 && $specCourse->files_count == 0)
                <span class="delete-btn deleteSpecCourse"
                        data-place="show"
                        data-url="{{ route('admin.specCourses.destroy', $specCourse) }}">
                    <i class="fa-solid fa-trash-can"></i>
                </span>
            @else
                <span class="delete-btn disabled">
                    <i class="fa-solid fa-trash-can"></i>
                </span>
            @endif

        </div>
    </div>

</div>
