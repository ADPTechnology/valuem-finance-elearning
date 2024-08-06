@if (empty($user))
    <div class="col-12">
        <section class="mt-5 mb-5">
            <div class="module-title mb-4 mt-4"></div>

            <div class="alert alert-danger">
                <i class="fa-solid fa-circle-exclamation"></i>
                &nbsp;
                No se ha podido encontrar al usuario
            </div>
        </section>
    </div>
@else
    <div class="col-12">

        <section class="mt-5 mb-5">
            <div class="module-title mb-4 mt-4"></div>

            <div class="d-flex flex-column align-items-center">
                <h2 class="section-title mb-2 ">RESULTADOS</h2>
                <p class="lead">Descarga tus documentos</p>
            </div>

        </section>
    </div>

    <div class="col-12">
        <section class="site-section-2">
            <div class="participant">
                <label> <strong>PARTICIPANTE:</strong> </label>
                <span> {{ strtoupper($user->full_name_complete_reverse) }} </span>
            </div>
            <div>
                <label> <strong>COD. IDENTIDAD:</strong> </label>
                <span> {{ strtoupper($user->dni ?? '--') }}</span>
            </div>
        </section>
    </div>

    <div class="col-12">

        @if (isset($course_types_collection))
            @forelse ($course_types_collection as $type_collection)
                @php
                    $type = $type_collection->first()->course->type;
                @endphp

                <section class="site-section-2">

                    <div class="data-users mt-4 mb-4">
                        <h5 class="text-black-50">
                            {{ $type->name }}

                        </h5>
                    </div>

                    @php $i = 0 @endphp

                    <table class="table table-striped table-bordered table-hover text-center" id="example-table"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>
                                    EMPRESA
                                </th>
                                <th>
                                    CURSO
                                </th>
                                <th>
                                    FECHA
                                </th>
                                <th>
                                    DOCUMENTOS
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($type_collection as $certification)
                                <tr>
                                    <td> {{ $certification->company->description }} </td>
                                    <td> {{ $certification->course->description }}
                                        @if ($certification->course->subtitle)
                                            / {{ strtoupper($certification->course->subtitle) }}
                                        @endif
                                    </td>
                                    <td> {{ $certification->event->date ?? '-' }} </td>
                                    <td>
                                        @if (Str::is('*EXTERNO*', strtoupper($type->name)))
                                            <a href="{{ route('pdf.export.ext_certification', $certification) }}"
                                                target="_BLANK">
                                            @elseif(Str::is('*WEBINAR*', strtoupper($type->name)))
                                                <a href="{{ route('pdf.export.web_certification', $certification) }}"
                                                    target="_BLANK">
                                                @else
                                                    <a href="{{ route('pdf.export.certification', $certification) }}"
                                                        target="_BLANK">
                                        @endif
                                        <img src="{{ asset('assets/certificates/images/certificado.png') }}"
                                            title="CERTIFICADO" width='25' height='25' />
                                        </a>

                                        @if (Str::is('*INDUCCI*', strtoupper($type->name)))
                                            @foreach ($certification->miningUnits as $miningUnit)
                                                <a href="{{ route('pdf.export.commitment', [$certification, $miningUnit]) }}"
                                                    target="_BLANK">
                                                    <img src="{{ asset('assets/certificates/images/carta_compromiso.png') }}"
                                                        title="CARTA COMPROMISO {{ strtoupper($miningUnit->description) }}"
                                                        width='25' height='25' /></a>
                                                </a>
                                            @endforeach

                                            @foreach ($certification->miningUnits as $miningUnit)
                                                @foreach ($certification->files as $file)
                                                    @if (substr($file->name, -5, 1) == getMiningUnitSufix($miningUnit->description))
                                                        <a href="{{ route('pdf.download.file', $file) }}">
                                                            <img src="{{ asset('assets/certificates/images/pdf.png') }}"
                                                                title="Anexo 4 {{ $miningUnit->description }}"
                                                                width='25' height='25' />
                                                        </a>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif

                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </section>
            @empty
                <section class="mt-5 mb-5">
                    <div class="alert alert-warning">
                        <i class="fa-solid fa-circle-exclamation"></i>
                        &nbsp;
                        No se encontaron documentos para este usuario
                    </div>
                </section>
            @endforelse

        @endif


    </div>


@endif
