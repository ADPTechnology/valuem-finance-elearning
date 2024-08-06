@if ($step->video_count >= 1 && $step->video->file)
    <h5 class="title-header-show mb-4">
        Video subido:
    </h5>
    <div class="active-video" id="video-reload">
        <div class="video-controls">
            <video controls class="video-class">
                <source src="{{ $step->video->file->file_url }}" type="video/mp4">
            </video>
        </div>
        @if ($step->video->questions_count == 0)
            <button class="btn btn-danger btn-sm btn-videoDelete" id="btn-delete-video"
                data-url="{{ route('admin.forgettingCurve.steps.video.destroy', $step->video) }}">
                <i class="fa-solid fa-trash"></i>
                &nbsp;
                <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
            </button>
        @else
            <button class="btn btn-danger btn-sm disabled btn-videoDelete" disabled>
                <i class="fa-solid fa-trash"></i>
            </button>
        @endif
    </div>

    <hr>

    <div class="principal-splitted-container mt-1 mb-2">


        <div class="principal-inner-container total-width">

            <div class="inner-title-container">
                <div id="" class="btn-dropdown-container show">
                    <h5 class="title-header-show"> Creación de enunciados </h5>

                    <div class="btn-row-container">
                        <div>
                            <span class="text-dropdown-cont">
                                Ocultar
                            </span>
                            <i class="fa-solid fa-chevron-down ms-2"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div id="dropdown-questions-create" class="related-dropdown-container">

                <form id="registerQuestionForm"
                    action="{{ route('admin.forgettingCurve.steps.video.questionVideo.store', $step->video) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="form-row questionTypeSelect">
                        <div class="form-group col-12">
                            <label>Selecciona un tipo de enunciado *</label>
                            <div class="input-group">
                                <select name="question_type_id" class="form-control select2"
                                    id="registerQuestionTypeSelect"
                                    data-url="{{ route('admin.exams.questions.getType') }}">
                                    <option></option>
                                    @foreach ($questionTypes as $questionType)
                                        <option value="{{ $questionType->id }}"> {{ $questionType->description }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div id="question-type-container">

                    </div>

                </form>

            </div>

        </div>

    </div>

    <hr>

    <h5 class="title-header-show mb-4">
        Lista de preguntas:
    </h5>

    <table id="questions-steps-table" class="table table-hover"
        data-url="{{ route('admin.forgettingCurve.steps.video.questionVideo.getDatatable', $step->video) }}">
        <thead>
            <tr>
                <th>N°</th>
                <th>Tipo de enunciado</th>
                <th>Enunciado</th>
                <th>Puntos</th>
                <th>Fecha de creación</th>
                <th>Fecha de actualización</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
    </table>
@else
    <div class="folder-inner-container">
        <h5 class="title-header-show mb-4"> Video: </h5>
        <div class="files-section-container">

            <div>
                <h6> Añadir video <strong>(Max: 40MB)</strong> </h6>
            </div>

            <div>
                <form action="{{ route('admin.forgettingCurve.steps.video.upload', $step) }}" method="POST"
                    id="videoForm" enctype="multipart/form-data">
                    @csrf

                    <div class="form-row">
                        <div class="form-group col-12">
                            <input type="file" class="store-step-video-evaluation-input">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-12">
                            <button type="submit" class="btn btn-save btn-primary waves-effect waves-light">
                                Guardar
                                <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                            </button>
                        </div>
                    </div>

                </form>

                {{-- <span class="font-italic font-weight-300">Nota: Si ocurre un error, pruebe cambiándole el nombre al
                    video. --}}
            </div>

        </div>

    </div>
@endif
