@extends('aula.common.layouts.masterpage')
@section('content')

<div class="content">
    <div class="main-container-page">
        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>DOCUMENTOS DE LA EMPRESA</h4>
                </div>
            </div>
        </div>

        <div class="card-body card z-index-2 principal-container">

            <div class="mb-4">
                <button class="btn btn-primary" data-toggle="modal" data-target="#storeFileModal">
                    <i class="fa-solid fa-square-plus"></i> &nbsp; Subir Documentos
                </button>
            </div>



            <table id="companyFiles_table"  class="table table-hover" data-url="{{route('aula.docCompany.index')}}">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Documento</th>
                        <th>Fecha de carga</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>

        </div>

    </div>

</div>

@endsection

@section('modals')
@include('aula.company.docCompany.partials.modals._store')
@endsection

@section('extra-script')
<script type="module" src="{{ asset('assets/aula/js/pages/company/documentsCompany.js') }}"></script>
@endsection

