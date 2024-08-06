<html lang="es">

@include('certificates.common.partials.head')

<body id="top">

    <div id="overlayer" style="display: none;"></div>

    <div class="site-wrap">

        @include('certificates.common.partials.header')

        @yield('content')

        @include('certificates.common.partials.footer')

    </div>

    @include('certificates.common.partials.scripts')

</body>

</html>