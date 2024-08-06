<div class="modal-header">
    <h5 class="modal-title" id="showCertificationModalTitle">
        <div class="title-header-show mt-0">
            <i class="fa-solid fa-file-lines"></i> &nbsp;
            <span>
                Certificado N°:
            </span>
            <span id="txt-context-element" class="text-bold">
                {{ $certification->id }}
            </span>
        </div>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true"> &times; </span>
    </button>
</div>

<div class="modal-body">

    <div class="title-header-show mb-4 mt-4"> Datos del participante: </div>

    <table id="participant-info-table" class="table table-hover">

        <thead>
            <tr>
                <th>N°</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Unidades mineras</th>
                <th>Email</th>
                <th>Empresa</th>
                <th>Cargo</th>
                <th>Estado</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td> {{ $certification->user->id }} </td>
                <td> {{ $certification->user->dni }} </td>
                <td> {{ $certification->user->full_name }} </td>
                <td>
                    <ul>
                        @foreach ($certification->user->miningUnits as $miningUnit)
                        <li>
                            {{ $miningUnit->description ?? '-' }}
                        </li>
                        @endforeach
                    </ul>
                </td>
                <td> {{ $certification->user->email }} </td>
                <td> {{ $certification->user->company->description ?? '-' }} </td>
                <td> {{ $certification->user->position ?? '-' }} </td>
                <td> {!! getStatusButton($certification->user->active) !!} </td>
            </tr>
        </tbody>

    </table>

    <hr>

    <div class="title-header-show mb-4 mt-4"> Datos del evento: </div>

    <table id="event-info-table" class="table table-hover">

        <thead>
            <tr>
                <th>N°</th>
                <th>Descripción</th>
                <th>Fecha</th>
                <th>Estado</th>
                <th>Examen</th>
                <th>Examen Activo</th>
                <th>Curso</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td> {{ $certification->event->id }} </td>
                <td> {{ $certification->event->description }} </td>
                <td> {{ $certification->event->date }} </td>
                <td> {!! getStatusButton($certification->event->active) !!} </td>
                <td> {{ $certification->event->exam->title ?? '-' }} </td>
                <td> {!! getStatusButton($certification->event->exam->active) !!} </td>
                <td> {{ $certification->event->course->description ?? '-' }} </td>
            </tr>
        </tbody>

    </table>

    <hr>

    <div class="title-header-show mb-4 mt-4"> Datos del certificado: </div>

    <table id="certification-info-table" class="table table-hover">

        <thead>
            <tr>
                <th>Hora de inicio de evaluación</th>
                <th>Hora de fin de evaluación</th>
                <th>Tiempo total de evaluación (min.)</th>
                <th>Nota</th>
                <th>Estado de la evaluación</th>
                <th>Asistencia</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td> {{ $certification->start_time ?? '-' }} </td>
                <td> {{ $certification->end_time ?? '-' }} </td>
                <td> {{ $certification->total_time ?? '-' }} </td>
                <td> {{ $certification->score ?? '-' }} </td>
                <td>
                    <span class="status {{ $certification->status }}">
                        {{ config('parameters.certification_status')[$certification->status] }}
                    </span>
                </td>
                <td>
                    {{ $certification->assist_user == 'S' ? 'Sí' : 'No' }}
                </td>
            </tr>
        </tbody>

    </table>

</div>
