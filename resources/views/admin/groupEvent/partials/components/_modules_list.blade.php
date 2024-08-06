@foreach ($groupEvent->specCourse->modules as $module)
    <div class="course-section-box {{ setSectionActive($module, $moduleActive) }}"
        data-active="{{ setSectionActive($module, $moduleActive) }}"
        data-table="{{ route('admin.specCourses.events.getDataTable', ['module' => $module, 'groupEvent' => $groupEvent]) }}"
        id="module-box-{{ $module->id }}" data-id="{{ $module->id }}">
        <div class="order-info">
            <span class="text-bold">
                {{ $module->order }}
            </span>
        </div>
        <div class="title-container">
            <div>
                <div class="little-text">Título: </div>
                <span class="text-bold">
                    {{ $module->title }}
                </span>
            </div>
            <div>
                <div class="little-text mt-1">Subtítulo: </div>
                <span class="font-italic">
                    {{ $module->subtitle }}
                </span>
            </div>
        </div>

        <div class="order-select-container">
            <div>
                <span class="little-text">Orden: </span>

                <span class="text-bold">
                    {{ $module->order }}
                </span>
            </div>

            <div>
                <div class="status-cont text-no-wrap d-flex justify-content-between align-items-center gap-1 mb-2">
                    <span class="text-info-stat little-text"> Estado: &nbsp;</span>
                    {!! getStatusButton($module->active) !!}
                </div>
            </div>
        </div>

    </div>
@endforeach
