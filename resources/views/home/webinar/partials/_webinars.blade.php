<div class="container-xxl py-5 courses-container">
    <div class="container">

        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title bg-white text-center text-primary px-3">Webinars</h6>
            <h1 class="mb-5">Webinars disponibles</h1>
        </div>

        @php
            $delay = 0.1;
        @endphp

        @forelse ($webinars as $webinar)
            @php
                $openRow = ($loop->iteration - 1) % 3 == 0 || $loop->first;
                $closeRow = $loop->iteration % 3 == 0 || $loop->last;

                $instructors = getInstructorsFromWebinarHome($webinar);

                $allCapacity = getAllCapacity($webinar);
                $allParticipants = getCountAllParticipantsWebinar($webinar);
            @endphp

            @if ($openRow)
                <div class="row g-4 justify-content-center">
            @endif

            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ $delay }}s">
                <div class="course-item bg-light-primary">
                    <div class="position-relative image-inner-container overflow-hidden">
                        <img class="img-fluid" src="{{ verifyImage($webinar->file) }}" alt="">
                        <div class="w-100 d-flex justify-content-center position-absolute bottom-0 start-0 mb-4">
                            <a href="{{ route('home.webinar.show', $webinar) }}"
                                class="flex-shrink-0 btn btn-sm btn-primary px-3" style="border-radius: 30px;">
                                Ver eventos
                            </a>
                        </div>
                    </div>
                    <div class="text-center p-4 pb-0">

                        <h5 class="mb-4">
                            {{ $webinar->title }}
                        </h5>
                    </div>
                    <div class="d-flex flex-column border-top">
                        <small
                            class="flex-fill d-flex justify-content-center align-items-center text-center border-end py-2">
                            <i class="fa fa-user-tie text-primary me-2"></i>
                            <span>
                                @foreach ($instructors as $instructor)
                                    <div>
                                        {{ ucwords(mb_strtolower($instructor->full_name, 'UTF-8')) }}
                                    </div>
                                @endforeach
                            </span>
                        </small>
                    </div>
                    <div class="d-flex border-top">
                        <small class="flex-fill text-center border-end py-2">
                            <i class="fa-solid fa-calendar text-primary me-2"></i>
                            {{ $webinar->date }}
                        </small>
                        <small class="flex-fill text-center py-2">
                            <i class="fa-solid text-primary me-2 fa-user-check"></i>
                            {{ $allCapacity - $allParticipants }}
                            Vacantes disponibles
                        </small>
                    </div>
                </div>
            </div>

            @if ($closeRow)
    </div>
    @endif
    @php
        $delay = $delay + 0.2;
    @endphp
@empty

    <h4 class="text-center empty-records-message"> No hay webinars que mostrar </h4>
    @endforelse

</div>
</div>
