<div class="modal-body">

    <div class="group-filter-buttons-section flex-wrap">
        <div class="form-group col-2 p-0 select-group">
            <label class="form-label">Filtrar por empresa: &nbsp;</label>
            <div>
                <select name="type" class="form-control select2 select-filter-event search_from_company_select">
                    <option value=""> Todos </option>
                    @foreach ($companies as $company)
                    <option value="{{ $company->id }}"> {{ $company->id }} - {{ $company->description }} </option>
                    @endforeach
                </select>
            </div>
        </div>

    </div>

    <table id="wb-internal-users-participants-table" class="table table-hover"
            data-url="{{ route('admin.webinars.all.events.certifications.getUsersList', $webinarEvent) }}">
        <thead>
            <tr>
                <th>Elegir</th>
                <th>N°</th>
                <th>DNI</th>
                <th>Nombre</th>
                <th>Posición</th>
                <th>Empresa</th>
            </tr>
        </thead>

    </table>

</div>
