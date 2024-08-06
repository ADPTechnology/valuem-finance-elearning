<nav class="navbar navbar-expand-lg main-navbar @yield('navbarClass')">

    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3" style="display: block">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
        </ul>

        <div class="banner-navbar">
            <img src="{{ asset('assets/common/images/banner-header2.jpg') }}" alt="">
        </div>

        <div class="company-info-welcome">
            <span class="welcome">
                Bienvenido (a) {{ Auth::user()->full_name }} a
            </span>
            <span class="company-name">
            {{ env('APP_NAME') }}
            </span>
        </div>
    </form>
</nav>
