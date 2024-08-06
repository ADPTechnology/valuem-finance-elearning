<nav class="navbar navbar-expand-lg navbar-dark sticky-top p-0">

    <a href="{{ route('home.index') }}" class="navbar-brand d-flex align-items-center px-4 px-lg-5">
        <img
        src="{{ asset('assets/common/images/logo_rediseno.svg') }}"
        {{-- src="{{ verifyUrl(getConfig()->logo_url) }}" --}}
        alt="Valuem Finance" style="width: 100%; height: auto; max-height: 75px; object-fit: cover;">
    </a>

    <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarCollapse">

        <div class="navbar-nav m-auto p-4 p-lg-0">

            @auth
                <span class="me-4 my-auto d-flex align-items-center">

                    <span class="user-avatar-container me-3">
                        <img src="{{ verifyUserAvatar(Auth::user()->avatar()) }}" alt="">
                    </span>

                    <b>¡Hola, {{ ucwords(mb_strtolower(Auth::user()->full_name, 'UTF-8')) }}!</b>
                </span>

            @endauth

            <a href="{{ route('home.index') }}" class="nav-item nav-link {{ setActive('home.index') }}">Inicio</a>

            <a href="{{ route('home.about.index') }}"
                class="nav-item nav-link {{ setActive('home.about.index') }}">Nosotros</a>

            {{-- <a href="{{ route('home.courses.index') }}"
                class="nav-item nav-link  {{ setActive('home.courses.*') }}">Cursos</a> --}}

            <a href="{{ route('home.freecourses.categories.index') }}"
                class="nav-item nav-link  {{ setActive('home.freecourses.*') }}">Cursos</a>

            @guest
                <a href="{{ route('home.webinar.index') }}" class="nav-item nav-link  {{ setActive('home.webinar.*') }}">
                    Contáctanos
                </a>
            @else
                @can('allowExternalAndAdmin')
                    <a href="{{ route('home.webinar.index') }}" class="nav-item nav-link  {{ setActive('home.webinar.*') }}">
                        Contáctanos
                    </a>
                @endcan
            @endguest

            @guest
                <a href="{{ route('register.show') }}" class="nav-item nav-link">
                    Registrarse
                    <i class="fa-solid fa-user-plus ms-2"></i>
                </a>
            @endguest

        </div>


        @guest

        <a href=" {{ route('login') }} " class="btn btn-primary py-2 px-lg-3 me-lg-4 d-block">
            Iniciar sesíón
            <i class="fa-solid fa-arrow-right-to-bracket ms-1"></i>
        </a>

        @endguest

        @auth

        <a href=" {{ route('login') }} " class="btn btn-primary py-4 px-lg-5 d-block">
            Ingresar al E-Learning
            <i class="fa-solid fa-chalkboard-user ms-3"></i>
            {{-- <i class="fa fa-arrow-right ms-3"></i> --}}
        </a>

        <a href="#" class="nav-link" style="color: #de1a2b"
            onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
            <i class="fa-solid fa-power-off"></i> &nbsp;
            <span>Cerrar sesión</span>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

        @endauth

    </div>




</nav>
