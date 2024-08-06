@component('mail::message')
# <center> ¡Hola {{ $admin->full_name }}!</center>

<br>
Hay una nueva solicitud por el acceso del curso libre: {{ $course->description }} <br>
<br>
<b>Datos del usuario solicitante:</b> <br> <br>
Nombres y apellidos: <b>{{ $user->full_name_complete }}</b> <br>
Dni: <b>{{ $user->dni }}</b> <br>
<br>
Puede visualizar la solicitud en el siguiente enlace:

@component('mail::button', ['url' => route('admin.freeCourses.courses.index', $course), 'color' => 'success'])
    Ingresa al sistema
@endcomponent

<center>
    ¿El link no funciona? Copia y pega esta URL en el navegador: <br>
    <a href="{{ route('admin.freeCourses.courses.index', $course) }}">
        {{ route('admin.freeCourses.courses.index', $course) }} </a>
</center>

<br>

<b>Gracias,</b> <br>
{{ config('app.name') }}
@endcomponent
