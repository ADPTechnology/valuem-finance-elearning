<div class="action-btn-dropdown-container top-container-inner-box">
    <button class="btn btn-primary" id="btn-register-group-event-modal"
        data-toggle="modal" data-target="#registerGroupEventModal">
        <i class="fa-solid fa-plus"></i> &nbsp;
            Añadir Grupo de evento
        <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
    </button>
</div>

<table id="specCourses-GroupEvents-table" class="table table-hover" data-url="{{ route('admin.specCourses.groupEvents.getDataTable', $specCourse) }}">
    <thead>
        <tr>
            <th>N°</th>
            <th>Titulo</th>
            <th>Descripción</th>
            <th>Estado</th>
            <th class="action-with">Acciones</th>
        </tr>
    </thead>
</table>

