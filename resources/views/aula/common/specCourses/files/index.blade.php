@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        <a href="{{ route('aula.specCourses.index') }}">
                            <i class="fa-solid fa-circle-chevron-left"></i> Cursos de especialización
                        </a>
                        <span> / {{ $specCourse->title }} </span> /
                        <a href="{{ route('aula.specCourses.show', $specCourse) }}">
                            MENÚ
                        </a> /
                        RECURSOS
                    </h4>
                </div>
            </div>
        </div>

        <div class="mt-3 card-body body-global-container card z-index-2 principal-container">

            <div class="resources-cards-container">

                @forelse ($filesCourse as $file)
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

        </div>

    </div>
@endsection
