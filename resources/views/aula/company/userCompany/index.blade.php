@extends('aula.common.layouts.masterpage')
@section('content')

<div class="content">
    <div class="main-container-page">
        <div class="card page-title-container">
            <div class="card-header">
                <div class="total-width-container">
                    <h4>USUARIOS EN LA EMPRESA</h4>
                </div>
            </div>
        </div>

        <div class="card-body card z-index-2 principal-container">

            <table id="usersCompany_table"  class="table table-hover" data-url="{{route('aula.userCompany.index')}}">
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>DNI</th>
                        <th>NOMBRE</th>
                        <th>APELLIDO</th>
                        <th>EMAIL</th>
                        <th>TELEFONO</th>
                        <th>ROL</th>
                        <th>EMPRESA</th>
                        <th>Estado</th>
                    </tr>
                </thead>
            </table>

        </div>



    </div>

</div>

@endsection

@section('extra-script')
<script type="module" src="{{asset('assets/aula/js/pages/company/usersCompany.js')}}"></script>
@endsection
