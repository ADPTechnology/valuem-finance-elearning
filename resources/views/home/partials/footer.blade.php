<div class="container-fluid  text-light footer pt-5 mt-5 wow fadeIn" data-wow-delay="0.1s">

    <div class="container py-5">
        <div class="row g-5">

            <div class="col-lg-4 col-md-6 d-flex flex-column justify-content-between">
                <div class="logo-adp-white">
                    <img
                    src="{{ asset("assets/common/images/logo_rediseno.svg") }}"
                    {{-- src="{{  verifyUrl(getConfig()->logo_url) }}" --}}
                    alt=""
                    style="width: 100%; height: auto; max-width: 200px; max-height: 75px; object-fit: cover">
                    {{-- <img src="{{ asset('assets/common/images/logo-red.png') }}" alt=""> --}}
                </div>
                <p class="description-c-o">«El conocimiento es poder y está al alcance de tus manos»</p>
                <div class="d-flex pt-2 container-footer-socials gap-1">

                    <a class="btn btn-outline-light btn-social icon-linkedin"
                        href="#" target="_BLANK">
                        <i class="fa-brands fa-linkedin-in"></i>
                        {{-- <img decoding="async" src="https://www.hamaperu.com/wp-content/uploads/2023/11/icon-ld.svg"> --}}
                    </a>


                    <a class="btn btn-outline-light btn-social icon-facebook"
                        href="#" target="_BLANK">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>

                    <a class="btn btn-outline-light btn-social icon-instagram"
                    href="#" target="_BLANK">
                        <i class="fa-brands fa-instagram"></i>
                    </a>

                    <a class="btn btn-outline-light btn-social icon-website" href="https://www.hamaperu.com"
                        target="_BLANK"><i class="fa-solid fa-globe"></i></a>
                </div>

            </div>

            <div class="col-lg-4 col-md-6">
                <h4 class="text-white mb-3">Soporte</h4>
                <p class="mb-2"> ¿Tienes alguna duda, consulta o incidencia con el uso de la plataforma? Escríbenos
                    a: </p>

                <h5>
                    {{-- <a href="mailto:administracion@hamaperu.com" style="color: #de1a2b">administracion@hamaperu.com</a> --}}
                </h5>

            </div>

            <div class="col-lg-4 col-md-12">
                <h4 class="text-white mb-3">Visita nuestras otras páginas</h4>
                <a class="btn btn-link" href="{{ route('home.about.index') }}">Nosotros</a>
                <a class="btn btn-link" href="{{ route('home.courses.index') }}">Cursos</a>
                <a class="btn btn-link" href="{{ route('home.freecourses.categories.index') }}">Cursos libres</a>
                <a class="btn btn-link" href="{{ route('register.show') }}">Regístrate!</a>
            </div>
        </div>
    </div>


    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-12 text-center text-md-start mb-3 mb-md-0">

                    <p class="text-center">
                        Copyright &copy; {{ getCurrentYear() }} <a class="border-bottom" href="#">Valuem Finance</a>.
                        Reservados todos los derechos.
                    </p>

                </div>
            </div>
        </div>
    </div>


</div>
