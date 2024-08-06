@extends('aula.common.layouts.masterpage')

@section('content')
    <div class="content global-container">

        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>Consulta certificados</h4>
                </div>
            </div>
        </div>

        <div class="card-body body-global-container card z-index-2 principal-container">

            <form action="{{ route('aula.certification.getCertificationDocuments') }}" id="getCertificationDocuments"
                method="GET" class="search-jobs-form">
                <div class="row mb-5 justify-content-center">
                    <div class="col-sm-3 col-md-3 col-lg-3 mb-3 mb-lg-0">
                        <input type="text" name="dni" class="form-control form-control-lg"
                            placeholder="ESCRIBE EL NÚMERO DE DNI">
                    </div>
                    <div class="col-sm-3 col-md-3 col-lg-3 mb-3 mb-lg-0">
                        <button class="btn btn-danger btn-lg btn-block text-white btn-search">
                            <i class="fa-solid fa-magnifying-glass mr-2"></i>
                            <i class="fa-solid fa-spinner fa-spin loadSpinner ms-1"></i>
                            BUSCAR EL DOCUMENTO
                        </button>
                    </div>
                </div>
            </form>

            <div class="row">
                <div class="col-12" >
                    <h5>CONTENIDO DE DOCUMENTOS:</h5>
                    <div class="content-docs">
                        <span class="badge badge-fill badge-secondary">Carta de compromiso</span>
                        <span class="badge badge-fill badge-secondary">Certificados</span>
                        <span class="badge badge-fill badge-secondary">Anexo 4</span>
                    </div>
                </div>
            </div>


            <div class="row" id="container-info-documents">

            </div>

        </div>

    </div>
@endsection

@section('extra-script')
    <script type="module" src="{{ asset('assets/aula/js/pages/supervisor/searchDocument.js') }}"></script>
@endsection
