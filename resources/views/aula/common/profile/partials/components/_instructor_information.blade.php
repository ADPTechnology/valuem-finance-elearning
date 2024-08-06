@php
    $userDetail = $instructor->userDetail;
@endphp

<div class="modal-body">

    <div class="form-row">
        <div class="form-group col-12">
            <label>Contenido *</label>
            <textarea name="content" class="summernote-card-editor" id="card-content-register">{{$userDetail->content}}</textarea>
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Número de cursos (opcional)</label>
            <input type="number" name="courses_count" class="form-control content"
                placeholder="Ingresa el número de cursos" value="{{ $userDetail->courses_count }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Link Facebook (opcional)</label>
            <input type="text" name="facebook_link" class="form-control content"
                placeholder="Ingresa el link de facebook" value="{{ $userDetail->facebook_link }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Link linkedin (opcional)</label>
            <input type="text" name="linkedin_link" class="form-control content"
                placeholder="Ingresa el link de linkedin" value="{{ $userDetail->linkedin_link }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Link Instagram (opcional)</label>
            <input type="text" name="instagram_link" class="form-control content"
                placeholder="Ingresa el link de instagram" value="{{ $userDetail->instagram_link }}">
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-12">
            <label>Link Página web (opcional)</label>
            <input type="text" name="pag_web_link" class="form-control content"
                placeholder="Ingresa el link de tú página web" value="{{ $userDetail->pag_web_link }}">
        </div>
    </div>

</div>
