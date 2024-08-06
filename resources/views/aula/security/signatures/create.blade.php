@extends('aula.common.layouts.masterpage')

@section('extra-head')

<link rel="stylesheet" href="{{ asset('assets/aula/signatures/css/signature.css') }}">

@endsection

@section('content')

<div class="content global-container">

    <div class="card page-title-container">
        <div class="card-header">
            <div class="total-width-container">
                <h4>FIRMA DIGITAL</h4>
            </div>
        </div>
    </div>

    <div class="inner-content">
        <div id="signature-pad" class="signature-pad">
            <div class="signature-pad--body">
                <canvas></canvas>
            </div>
            <div class="signature-pad--footer">
                <div class="description">
                    Firmas Digitales - HAMA PERU <br> (Estimado Gerente de seguridad firmar dentro del recuadro)
                </div>
                <div class="signature-pad--actions">
                    <div class="button-section-1">
                        <button type="button" class="button clear btn btn-danger" data-action="clear">Volver a
                            Firmar </button>
                        <button type="button" class="button clear btn btn-warning" data-action="undo">Corregir
                            Firma</button>
                    </div>
                    <div class="button-section-2">
                        <button
                        id="btn_save_signature" data-url="{{ route('aula.signatures.security.store', [$event, $miningUnit]) }}"
                         type="button" class="button save btn btn-success" data-action="save-png">Guardar
                            Firma
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


</div>

@endsection


@section('extra-script')
<script src="{{ asset('assets/aula/signatures/js/signature_pad.umd.js') }}"></script>
<script type="module" src="{{ asset('assets/aula/signatures/js/signature.js') }}"></script>
@endsection