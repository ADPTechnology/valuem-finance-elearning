@component('mail::message')
# <center> ¡Hola {{ $certification->user->full_name }}! </center>

<br>
Han pasado {{ $fcInstance->days_count }} días luego de la obtención de su certificado<br>
El cual pertenece al curso: <b>{{ $certification->course->description }}</b> <br>
<i>Fecha de la obtención: {{ ucfirst(getDateForHummans($certification->end_time)) }}</i> <br>
<br>
Realize sus evaluaciones que son parte de la curva del olvido:

@component('mail::button', ['url' => route('login'), 'color' => 'success'])
    Ingresa a tu cuenta
@endcomponent

<center>
    ¿El link no funciona? Copia y pega esta URL en el navegador: <br>
    <a href="{{ route('login') }}"> {{ route('login') }} </a>
</center>

<br>

<b>Gracias,</b> <br>
{{ config('app.name') }}
@endcomponent
