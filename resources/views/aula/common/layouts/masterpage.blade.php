<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

@include('aula.common.partials.head')

<body>

    {{--   WHATSAPP FLOATING BUTTON   --}}

    @php
        $config = getWhatsappConfig();
    @endphp

    <a href="https://wa.me/{{ $config->whatsapp_number }}?text={{ urlencode($config->whatsapp_message) }}" target="_BLANK"
        class="btn-whatsapp-pulse">
        <i class="fa-brands fa-whatsapp"></i>
    </a>

    <div id="app" style="background-color: #fdfdfd">
        <div class="main-wrapper main-wrapper-1">

            <div class="navbar-bg @yield('navbarClass')"></div>

            @hasSection('navbar-extra-content')
                @yield('navbar-extra-content')
            @else
                @include('aula.common.partials.navbar')
            @endif

            @include('aula.common.partials.sidebar')

            <div class="main-content @yield('main-content-extra-class')">

                <section class="section">

                    @yield('content')

                </section>

            </div>

            @include('aula.common.partials.footer')

        </div>
    </div>



    @include('aula.common.partials.scripts')

</body>

</html>
