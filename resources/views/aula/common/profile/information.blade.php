@extends('aula.common.layouts.masterpage')

@section('extra-head')
    <link rel="stylesheet" href="{{ asset('assets/common/modules/summernote/summernote-bs4.css') }}">
@endsection

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>
                        Mi información como instructor
                    </h4>
                </div>
            </div>
        </div>

        <div class="card-body body-global-container card z-index-2 principal-container">

            <div class="information-instructor-container">

                @include('aula.common.profile.partials.boxes._profile_information')

            </div>

            <div class="container-button-update-information">

                <button class="btn btn-primary showInformationInstructor-btn"
                    data-send="{{ route('aula.profile.instructor.information.edit') }}"
                    data-url="{{ route('aula.profile.instructor.information.update') }}">
                    Actualizar información
                    &nbsp;
                    <i class="fa-solid fa-pen-to-square"></i>
                </button>

            </div>

        </div>

    </div>
@endsection

@section('extra-script')
    <script src="{{ asset('assets/common/modules/summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('assets/common/modules/summernote/lang/summernote-es-ES.js') }}"></script>
    <script type="module" src="{{ asset('assets/aula/js/pages/profile/instructorInformation.js') }}"></script>
@endsection

@section('modals')
    @include('aula.common.profile.models._viewInstructorInformation')
@endsection
