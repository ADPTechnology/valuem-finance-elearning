@extends('admin.common.layouts.masterpage')

@section('content')

<div class="row content">


    <div class="main-container-page">
        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>GESTIÓN DE ARCHIVOS</h4>
                </div>
            </div>
        </div>
       
        <div class="card-body card z-index-2 principal-container">

            <div class="group-filter-buttons-section">
                <div class="form-group date-range-container">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <a href="javascript:;" id="daterange-btn-files"
                                class="btn btn-primary icon-left btn-icon pt-2">
                                <i class="fas fa-calendar"></i>
                                Elegir Fecha
                            </a>
                        </div>
                        <input type="text" name="date-range" class="form-control date-range-input"
                            id="date-range-input-files" disabled>
                    </div>
                </div>
            </div>
    

            <div class="mb-4">
                <button class="btn btn-primary" data-toggle="modal" data-target="#storeFileModal">
                    <i class="fa-solid fa-square-plus"></i> &nbsp; Subir archivo
                </button>
            </div>

            <table id="files-table" class="table table-hover" data-url="{{ route('admin.filesManagement.index') }}">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Archivo</th>
                        <th>Categoría</th>
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
@include('admin.filesManagement.partials.modals._store')
@endsection

@section('extra-script')
<script type="module" src="{{ asset('assets/admin/js/files-management.js') }}"></script>
@endsection