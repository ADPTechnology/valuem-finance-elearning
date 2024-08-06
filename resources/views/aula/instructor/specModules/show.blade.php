@extends('aula.common.layouts.masterpage')

@section('content')


<div class="content global-container">

    <div class="card page-title-container">
        <div class="card-header">
            <div class="total-width-container">
                <h4>
                    <a href="{{ route('aula.specCourses.index') }}">
                        <i class="fa-solid fa-circle-chevron-left"></i> Cursos
                    </a>
                    <span> / {{$module->specCourse->title}} </span> /
                    <a href="{{ route('aula.specCourses.show', ["specCourse" => $module->specCourse]) }}">
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
                    <a href="{{ route('aula.specCourses.modules.index', ["specCourse" => $module->specCourse]) }}">
                        MÓDULOS
                    </a>
                    <span> / {{ $module->title }} </span>
                    / EVENTOS
                </h4>
            </div>
        </div>
    </div>


    <div class="card-body body-global-container card z-index-2 principal-container">

        <div class="course-container">

            <table id="events-table" class="table table-hover" data-url="">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descripción</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Sala</th>
                        <th>Curso</th>
                    </tr>
                </thead>
            </table>

        </div>

    </div>

</div>

@endsection

@section('extra-script')

<script type="module" src="{{ asset('assets/aula/js/pages/spec_modules.js') }}"></script>

@endsection
