@component('mail::message')
# <center> ¡Hola {{ $user->full_name }}, Bienvenido a {{ config('app.name') }}! </center>

<br>
Se ha creado una nueva cuenta para ti. <br>
Utiliza las siguientes credenciales para acceder:

@component('mail::panel')
<b>ID de Usuario:</b> {{ $user->dni }} <br>
<b> Contraseña:</b> {{ $password }}
@endcomponent

@component('mail::button', ['url' => route('login'), 'color' => 'success'])
Ingresa a tu cuenta
@endcomponent

<center>
¿El link no funciona? Copia y pega esta URL en el navegador: <br>
<a href="{{ route('login') }}"> {{ route('login') }} </a>

</center>

@component('mail::subcopy')
<b>Recuerda que puedes modificar tu contraseña ingresando a tu perfil.</b> <br>
<i>No compartas esta información con nadie. </i>
@endcomponent

<br>

<b>Gracias,</b> <br>
{{ config('app.name') }}

@endcomponent
