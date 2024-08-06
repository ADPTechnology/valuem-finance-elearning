@extends('certificates.common.layout.masterpage')

@section('content')

    @php
        use Illuminate\Support\Str;
    @endphp

    <section class="home-section section-hero overlay bg-image"
        style="background-image: url('/assets/certificates/images/hero_1.png');" id="home-section">

        <div class="container">
            <div class="row align-items-center justify-content-center">
                <div class="col-md-12">
                    <div class="mb-5 text-center">
                        <h1 class="text-white font-weight-bold">BIENVENIDO AL PORTAL DE EMISIÓN DE DOCUMENTOS</h1>
                        <p>
                            CONSULTA POR NÚMERO DE DNI Y DESCARGA TU CARTAS DE COMPROMISO, ANEXO 4 Y CERTIFICADOS.
                        </p>
                    </div>
                    <form action="{{ route('certifications.index') }}#next" method="GET" class="search-jobs-form">
                        <div class="row mb-5">
                            <div class="col-sm-3 col-md-3 col-lg-3 mb-3 mb-lg-0"></div>
                            <div class="col-sm-3 col-md-3 col-lg-3 mb-3 mb-lg-0">
                                <input type="text" name="dni"
                                    class="form-control form-control-lg {{ $errors->has('dni') ? 'is-invalid' : '' }}"
                                    placeholder="ESCRIBE TU NÚMERO DE DNI" value="{{ old('dni') }}">
                                @if ($errors->has('dni'))
                                    <div class="invalid-feedback">
                                        <strong>{{ $errors->first('dni') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <div class="col-sm-3 col-md-3 col-lg-3 mb-3 mb-lg-0">
                                <button type="submit" class="btn btn-danger btn-lg btn-block text-white btn-search">
                                    <i class="fa-solid fa-magnifying-glass mr-2"></i>
                                    BUSCA TU DOCUMENTO
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 popular-keywords">
                                <h3>CONTENIDO DE DOCUMENTOS: </h3>
                                <ul class="keywords list-unstyled m-0 p-0">
                                    <li><a href="#" class="">Cartas de Compromiso</a></li>
                                    <li><a href="#" class="">Certificados</a></li>
                                    <li><a href="#" class="">Anexo4</a></li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <a href="#next" class="scroll-button smoothscroll">
            <span>
                <i class="fa-solid fa-chevron-down fa-xs"></i>
            </span>
        </a>

    </section>

    <section class="py-5 bg-image overlay-primary fixed overlay" id="next"
        style="background-image: url('/assets/certificates/images/hero_1.png');">

        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 text-center">
                    <h2 class="section-title mb-2 text-white">RESULTADOS</h2>
                    <p class="lead text-white">Descarga tus Documentos.</p>
                </div>
            </div>
        </div>

    </section>

    <section class="site-section-2 mb-0">
        <div class="container" style="max-width: 1500px;">

            <div class="data-users">

                <div class="">
                    <label for="">PARTICIPANTE: </label>
                    <span>{{ strtoupper($user->full_name_complete_reverse ?? '-') }}</span>
                </div>

                <div class="">
                    <label for="">COD. IDENTIDAD: </label>
                    <span>{{ $user->dni ?? '-' }}</span>
                </div>
                <br>
            </div>
        </div>
    </section>

    @if (isset($course_types_collection))
        @forelse ($course_types_collection as $type_collection)
            @php
                $type = $type_collection->first()->course->type;
            @endphp

            <section class="site-section-2">
                <div class="container" style="max-width: 1500px;">

                    <div class="data-users">
                        <span>
                            {{ $type->name }}
                        </span>
                    </div>

                    @php $i = 0 @endphp
                    <table class="table table-striped table-bordered table-hover text-center" id="example-table"
                        cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <center>EMPRESA</center>
                                </th>
                                <th scope="col">
                                    <center>CURSO</center>
                                </th>
                                <th scope="col">
                                    <center>FECHA</center>
                                </th>
                                <th scope="col">
                                    <center>DOCUMENTOS</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($type_collection as $certification)
                                <tr role="row" class="odd">
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
                </div>
            </section>

        @empty

            <div class="row align-items-center text-center mb-5">
                <div class="col-12">
                    <h2>
                        No se encontraron certificados de este participante
                    </h2>
                </div>
            </div>
        @endforelse
    @endif


    <section class="py-5 bg-image overlay-primary fixed overlay"
        style="background-image: url('/assets/certificates/images/hero_1.png');">

        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h2 class="text-white">¿Encontraste tus Certificados?</h2>
                    <p class="mb-0 text-white lead">Si no encontraste tus certificados comunícate con los
                        supervisores responsables.</p>
                </div>
                <div class="col-md-3 ml-auto">
                    <a href="#" class="btn btn-danger btn-block btn-lg">Ir al Inicio</a>
                </div>
            </div>
        </div>

    </section>

@endsection
