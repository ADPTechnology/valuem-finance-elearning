@extends('aula.common.layouts.masterpage')

@section('content')

<div class="content global-container">

    <div class="card page-title-container">
        <div class="card-header">
            <div class="total-width-container">
                <h4>
                    <a href="{{route('aula.course.index')}}">
                        <i class="fa-solid fa-circle-chevron-left"></i> Cursos
                    </a>
                    <span> / {{$course->description}} </span> /
                    <a href="{{route('aula.course.show', $course)}}">
                        MENÚ
                    </a> 
                </h4>
            </div>
        </div>
    </div>

    <div class="card page-title-container">
        <div class="card-header">
            <div class="total-width-container">
                <h4>
                    EVENTOS POR FIRMAR
                </h4>
            </div>
        </div>
    </div>


    <div class="card-body body-global-container card z-index-2 principal-container">

        <div class="course-container">

            <table id="events-table" class="table table-hover" data-url="{{ route('aula.course.events.security.index', $course) }}">
                <thead>
                    <tr>
                        <th>Código Evento</th>
                        <th>Descripción</th>
                        <th>Fecha</th>
                        <th>Participantes</th>

                        @foreach ($user->miningUnits as $miningUnit)

                        @if (getMiningUnitSufix($miningUnit->description) == 'P')
                        <th>Firmar EP</th>
                        @elseif(getMiningUnitSufix($miningUnit->description) == 'A')
                        <th>Firmar AT</th>
                        @endif
                        
                        @endforeach
                    </tr>
                </thead>
            </table>
    
        </div>
    
    </div>  

</div>

@endsection

@section('extra-script')

<script type="module" src="{{ asset('assets/aula/js/pages/security_events.js') }}"></script>

@endsection