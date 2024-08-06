<div class="action-btn-dropdown-container top-container-inner-box">
    <button class="btn btn-primary" id="btn-register-spec-event-modal"
        data-url="{{ route('admin.specCourses.events.create') }}"
        data-store="{{ route('admin.specCourses.events.store', ['module' => $module, 'groupEvent' => $groupEvent]) }}">
        <i class="fa-solid fa-plus"></i> &nbsp;
        Añadir Evento
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
    </button>
</div>

<table id="specCourses-events-table" class="table table-hover">
    <thead>
        <tr>
            <th>N°</th>
            <th>Descripción</th>
            <th>Tipo</th>
            <th>Fecha</th>
            <th>Curso</th>
            <th>Instructor</th>
            <th>Responsable</th>
            <th>Asistencias</th>
            <th>Estado</th>
            <th class="action-with">Acciones</th>
        </tr>
    </thead>
</table>
