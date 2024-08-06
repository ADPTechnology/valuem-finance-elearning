@php
    $freeCourseDetail = $freeCourse->freecourseDetail;
@endphp

<div class="card-header-information">

    <div class="image-instructor">
        <img src="{{ verifyImage($freeCourse->file) }}" alt="{{ $freeCourse->description }}">
    </div>

    <div class="information-basic">
        <div class="full-name-instructor">
            <u>
                <h4>{{ $freeCourse->description }}</h4>
            </u>
        </div>
    </div>

</div>

<div class="card-body-information">
    <span class="font-weight-bold about-me" style="font-weight: 700 !important">Descripción del curso:</span>
    <div class="information-instructor-content">
        {!! $freeCourseDetail->description ?? '-' !!}
    </div>
    <div class="count-courses">

        <span class="font-italic" style="font-weight: bold !important; font-size: 1.4rem; color: #de1a2b">S/
            {{ $freeCourseDetail->price }}</span>
    </div>
</div>
