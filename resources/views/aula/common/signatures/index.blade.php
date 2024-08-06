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

    <div class="card-body body-global-container card z-index-2 principal-container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header justify-content-center h5">
                        Digitaliza tu firma para poder acceder a las capacitaciones
                        y
                        evaluaciones
                    </div>

                    <div class="card-body">
                        <center>

                            <a @if(Auth::user()->signature != 'S') 
                                href="javascript:;"
                                data-izimodal-open="#authorization_modal" 
                                @else
                                href="{{ route('aula.signatures.create') }}"
                                @endif
                                class="btn btn-primary btn-lg">
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
                                class="btn btn-secondary btn-sm">
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
@include('aula.common.signatures.partials.modals._authorization_modal')
@include('aula.common.signatures.partials.modals._tutorial_ex')
@endsection

@section('extra-script')
<script type="module" src="{{ asset('assets/aula/signatures/js/signature.js') }}"></script>
@endsection