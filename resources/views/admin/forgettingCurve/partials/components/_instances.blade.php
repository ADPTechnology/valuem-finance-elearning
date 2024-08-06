@php
    $i = 1;
@endphp
@foreach ($forgettingCurve->instances as $instance)
    <div class="course-section-box instances-section-box" {{ setSectionActive($instance, $instanceActive) }}
        data-active="{{ setSectionActive($instance, $instanceActive) }}"
        data-table="{{ route('admin.forgettingCurve.getDatatableSteps', $instance) }}" id="instance-box-{{ $instance->id }}"
        data-id="{{ $instance->id }}">
        <div class="order-info">
            <span class="text-bold">
                {{ $i++ }}
            </span>
        </div>
        <div class="title-container">
            <div>
                <div class="little-text">Titulo: </div>
                <span class="text-bold">
                    {{ $instance->title }}
                </span>
            </div>
            <div>
                <div class="little-text">Dia del conteo: </div>
                <span class="text-bold">
                    {{ $instance->days_count }}
                </span>
            </div>
        </div>

    </div>
@endforeach
