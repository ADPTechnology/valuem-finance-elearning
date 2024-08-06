<div class="resources-cards-container">

    @forelse ($files as $file)
        <div class="resources-card">
            <a href="{{ route('aula.specCourses.files.download', $file) }}">

                <div class="resources-card-body-box">

                    @php
                        $svg = getFileExtension($file) . '.svg';
                    @endphp

                    <div class="info-container-resources">
                        <div class="resources-image-cont-box p-3">
                            <img src="{{ asset('assets/common/images/file-types/' . $svg) }}">
                        </div>
                        <div class="resources-text-cont-box p-3">
                            <span class="resources-content-text text-truncate">
                                {{ basename($file->file_path) }}
                            </span>
                            <i class="resources-date-text">
                                {{ ucfirst(getDateForHummans($file->created_at)) }}
                            </i>
                        </div>
                    </div>
                </div>
            </a>

        </div>

    @empty
        <h4 class="text-center">
            Aún no hay recursos
            <img src="{{ asset('assets/common/images/emptyfolder.png') }}" alt="">
        </h4>
    @endforelse

</div>
