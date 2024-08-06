<div class="video-controls">
    <video controls class="video-class">
        <source src="{{ $video->file->file_url }}" type="video/mp4">
    </video>
</div>
@if ($video->questions_count === 0)
    <button class="btn btn-danger btn-sm btn-videoDelete" id="btn-delete-video"
        data-url="{{ route('admin.forgettingCurve.steps.video.destroy', $video) }}">
        <i class="fa-solid fa-trash"></i>
        &nbsp;
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
    </button>
@else
    <button class="btn btn-danger btn-sm disabled btn-videoDelete" disabled>
        <i class="fa-solid fa-trash"></i>
    </button>
@endif
