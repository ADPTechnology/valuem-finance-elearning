@php
    $userDetail = $instructor->userDetail;
@endphp

<div class="card-header-information">

    <div class="image-instructor">
        <img src="{{ verifyUserAvatar($instructor->file) }}" alt="{{ $instructor->full_name_complete }}">
    </div>

    <div class="information-basic">
        <div class="full-name-instructor">
            <u>
                <h4>{{ $instructor->full_name_complete }}</h4>
            </u>
        </div>
        <div class="count-courses">
            <span class="font-italic font-weight-600">{{ $userDetail->courses_count }} cursos</span>
        </div>
        <div class="socials">
            @if ($userDetail->facebook_link)
                <a href="{{ $userDetail->facebook_link }}" target="_blank" class="social-red-icon icon-facebook">
                    <i class="fa-brands fa-facebook"></i>
                </a>
            @endif
            @if ($userDetail->linkedin_link)
                <a href="{{ $userDetail->linkedin_link }}" target="_blank" class="social-red-icon icon-linked">
                    <i class="fa-brands fa-linkedin"></i>
                </a>
            @endif

            @if ($userDetail->instagram_link)
                <a href="{{ $userDetail->instagram_link }}" target="_blank" class="social-red-icon icon-instagram">
                    <i class="fa-brands fa-instagram"></i>
                </a>
            @endif
            @if ($instructor->email)
                <a href="mailto:{{ $instructor->email }}" target="_blank" class="social-red-icon icon-email">
                    <i class="fa-solid fa-at"></i>
                </a>
            @endif
        </div>
        <div class="page_web_link">
            @if ($userDetail->pag_web_link)
                Página web:
                <a href="{{ $userDetail->pag_web_link }}" target="_blank">{{ $userDetail->pag_web_link }}</a>
            @endif
        </div>
    </div>

</div>

<div class="card-body-information">
    <span class="font-weight-bold about-me" style="font-weight: 700 !important">Sobre mí:</span>
    <div class="information-instructor-content">
        {!! $userDetail->content ?? '-' !!}
    </div>
</div>
