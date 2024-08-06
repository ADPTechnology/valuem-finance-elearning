@extends('aula.common.layouts.masterpage')

@section('content')


<div class="content global-container">

    <div class="card page-title-container">
        <div class="card-header">
            <div class="total-width-container">
                <h4>FIRMA DIGITAL</h4>
            </div>
        </div>
    </div>

    <div class="card page-title-container">
        <div class="card-header">
            <div class="total-width-container">
                <h4>
                    <span>
                        Curso - {{ $event->course->description }} /
                    </span>
                    <a href="{{ route('aula.course.events.security.index', ["course" => $event->course]) }}">
                        Eventos
                    </a>
                </h4>
            </div>
        </div>
    </div>


    <div class="card-body body-global-container card z-index-2 principal-container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header justify-content-center h5">
                        Digitaliza tu firma para poder validar los documentos Anexo 4 del Evento - 
                        {{ $event->description }}
                    </div>

                    <div class="card-body">
                        <center>

                            <a  href="javascript:;"
                                data-izimodal-open="#authorization_modal"
                                class="btn btn-danger btn-lg">
                                <span class="btn-label">
                                    FIRMA AQUI
                                </span>
                            </a>

                        </center>
                        <br>
                        <br>
                        <center> ¿Cómo realizar tu firma digital?</center>
                        <br>
                        <center>
                            <a href="javascript:;" data-izimodal-open="#tutorial_ex_modal" 
                                class="btn btn-danger btn-sm">
                                <span class="btn-label">
                                    <i class="fas fa fa-play"></i>
                                </span>
                                Ver Tutorial
                            </a>
                        </center>
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>

@endsection

@section('modals')
@include('aula.security.signatures.partials.modals._authorization')
@include('aula.common.signatures.partials.modals._tutorial_ex')
@endsection

@section('extra-script')
<script type="module" src="{{ asset('assets/aula/signatures/js/signature.js') }}"></script>
@endsection