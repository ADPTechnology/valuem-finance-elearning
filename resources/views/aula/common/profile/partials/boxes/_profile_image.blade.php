<div class="outside-container-image-avatar">
    <div class="img-profile-page-box">
        <img src="{{ verifyUserAvatar(Auth::user()->avatar()) }}" alt="">
    </div>
    <div class="edit-avatar-btn" data-url="{{ route('aula.profile.userAvatar.edit', Auth::user()) }}">
        {{-- <label for="user-avatar-input"> --}}
        <i class="fa-solid fa-pencil"></i>
        {{-- </label> --}}
    </div>
</div>
