@foreach ($specCourse->modules as $module)
    <div class="course-section-box {{ setSectionActive($module, $moduleActive) }}"
        data-active="{{ setSectionActive($module, $moduleActive) }}"
        {{-- data-table="{{ route('admin.specCourses.events.getDataTable', ['module' => $module, 'groupEvent' => $groupEvent]) }}" --}}
        id="module-box-{{ $module->id }}" data-id="{{ $module->id }}">
        <div class="order-info">
            <span class="text-bold">
                {{ $module->order }}
            </span>
        </div>
        <div class="title-module">
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

        <div class="chapters-count">
            <div class="little-text"> Eventos: </div>
            <span class="text-bold">
                {{ $module->events_count }}
            </span>
        </div>

        <div class="order-select-container">
            <div>
                <span class="little-text">Orden: </span>
                <div class="input-group">
                    <select name="order" data-url="{{ route('admin.specCourses.modules.updateOrder', $module) }}"
                        class="form-control select2 editOrderSelect">
                        @foreach ($specCourse->modules as $order)
                            <option {{ getGlobalSelectedOption($module, $order->order) }} value="{{ $order->order }}">
                                {{ $order->order }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <div class="status-cont text-no-wrap d-flex justify-content-between align-items-center gap-1 mb-2">
                    <span class="text-info-stat little-text"> Estado: &nbsp;</span>
                    {!! getStatusButton($module->active) !!}
                </div>
            </div>
        </div>

        <div class="action-box clean-list">
            <div class="btn-action-container">
                <span class="module-edit-btn edit-btn"
                    data-send="{{ route('admin.specCourses.modules.edit', $module) }}"
                    data-url="{{ route('admin.specCourses.modules.update', $module) }}">
                    <i class="fa-solid fa-pen-to-square"></i>
                </span>
                @if ($module->events_count == 0)
                    <span class="delete-btn delete-module-btn"
                        data-url="{{ route('admin.specCourses.modules.destroy', $module) }}">
                        <i class="fa-solid fa-trash-can"></i>
                    </span>
                @else
                    <span class="delete-btn disabled">
                        <i class="fa-solid fa-trash-can"></i>
                    </span>
                @endif

            </div>

            <div class="modules-files-cont">

                <button class="btn btn-modules-file"
                data-send="{{ route('admin.specCourses.modules.getFiles', $module) }}"
                data-url="{{ route('admin.specCourses.modules.storeFiles', $module) }}">
                    <i class="fa-solid fa-folder-open"></i>
                </button>

            </div>
        </div>

    </div>
@endforeach
