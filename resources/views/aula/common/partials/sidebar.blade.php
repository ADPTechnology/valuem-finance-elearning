<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <div class="sidebar-brand p-4">
            <a href="{{ route('aula.index') }}">
                <img src="{{ verifyUrl(getConfig()->logo_url) }}" alt="">
            </a>
        </div>

        <div class="sidebar-brand hidden sidebar-brand-sm">
            <a href="{{ route('aula.index') }}">
                <img src="{{ verifyUrl(getConfig()->logo_url) }}" alt="">
            </a>
        </div>

        <ul class="sidebar-menu">

            <div class="box-profile">
                <div class="user-profile-presentation-cont mb-3" id="profile-avatar-container">
                    @include('aula.common.profile.partials.boxes._profile_image')
                </div>
                <div class="name">
                    {{ strtoupper(Auth::user()->full_name) }}
                </div>
                <div class="dni">
                    @if (Auth::user()->role == 'companies')
                        RUC {{ strtolower(Auth::user()->dni) }}
                    @else
                        DNI {{ strtolower(Auth::user()->dni) }}
                    @endif
                </div>

                <div class="my-profile">
                    <li class="{{ setActive('aula.profile.index') }}">
                        <a href="{{ route('aula.profile.index') }}" class="nav-link">
                            <span>Mi Perfil</span>
                        </a>
                    </li>
                </div>

                <div id="icon-profile" style="display: none">
                    <li class="{{ setActive('aula.profile.index') }}">
                        <a href="{{ route('aula.profile.index') }}" class="nav-link"
                            data-toggle="tooltip"data-original-title="Mi perfil">
                            <i class="fa-solid fa-circle-user" style="margin-right: 0"></i>
                        </a>
                    </li>
                </div>

                @can('allowInstructor')
                    <div class="my-information">
                        <li class="{{ setActive('aula.profile.instructor.information.index') }}">
                            <a href="{{ route('aula.profile.instructor.information.index') }}" class="nav-link">
                                <span>Información</span>
                            </a>
                        </li>
                    </div>

                    <div id="icon-information" style="display: none">
                        <li class="{{ setActive('aula.profile.instructor.information.index') }}">
                            <a href="{{ route('aula.profile.instructor.information.index') }}" class="nav-link"
                                data-toggle="tooltip"data-original-title="Mi información">
                                <i class="fa-solid fa-user-pen" style="margin-right: 0"></i>
                            </a>
                        </li>
                    </div>
                @endcan

                <div class="line-50"></div>
            </div>

            <li class="{{ setActive('aula.index') }}">
                <a href="{{ route('aula.index') }}" class="nav-link">
                    <i class="fa-solid fa-house"></i>
                    <span>Inicio</span>
                </a>
            </li>

            @can(['denySecurity', 'denyCompany', 'denySupervisor', 'denyExternal'])
                <li class="{{ setActive('aula.signatures.*') }}">
                    <a href="{{ route('aula.signatures.index') }}" class="nav-link">
                        <i class="fa-solid fa-signature"></i>
                        <span>Firma Digital</span>
                    </a>
                </li>
            @endcan

            @can('allowParticipant')

                <li class="{{ setActive('aula.myDocs.*') }}">
                    <a href="{{ route('aula.myDocs.index') }}" class="nav-link">
                        <i class="fa-solid fa-folder-open"></i>
                        <span>Mis Documentos</span>
                    </a>
                </li>

            @endcan

            @can(['denyCompany', 'denySupervisor'])

                @if (hasElearning())
                    <li class="{{ setActive('aula.course.*') }}">
                        <a href="{{ route('aula.course.index') }}" class="nav-link">
                            <i class="fa-solid fa-book"></i>
                            <span>E-Learning</span>
                        </a>
                    </li>
                @endif

            @endcan

            @can(['denySecurity', 'denyCompany', 'denySupervisor'])

                @can('denyInstructor')

                    @if (hasFreeCourses())

                    <li class="{{ setActive('aula.freecourse.*') }}">
                        <a href="{{ route('aula.freecourse.index') }}" class="nav-link">
                            <i class="fa-solid fa-laptop-file"></i>
                            <span>Cursos Libres</span>
                        </a>
                    </li>

                    @endif

                @endcan

                {{-- @can('denyExternal') --}}

                @if (hasSpecCourses())

                <li class="{{ setActive('aula.specCourses.*') }}">
                    <a href="{{ route('aula.specCourses.index') }}" class="nav-link">
                        <i class="fa-solid fa-graduation-cap"></i>
                        <span>Cursos de Especialización</span>
                    </a>
                </li>

                @endif

                @if (hasLiveFreeCourses())

                <li class="{{ setActive('aula.freeCourseLive.*') }}">
                    <a href="{{ route('aula.freeCourseLive.index') }}" class="nav-link">
                        <i class="fa-solid fa-circle-play"></i>
                        <span>Cursos libres en vivo</span>
                    </a>
                </li>

                @endif


                {{-- @endcan --}}

                @if (hasWebinar())

                <li class="{{ setActive('aula.webinar.*') }}">
                    <a href="{{ route('aula.webinar.index') }}" class="nav-link">
                        <i class="fa-solid fa-desktop"></i>
                        <span>Webinars</span>
                    </a>
                </li>

                @endif

            @endcan

            @can(['denyInstructor', 'denySecurity', 'denyCompany', 'denySupervisor'])


                <li class="{{ setActive('aula.myprogress.*') }}">
                    <a class="nav-link" href="{{ route('aula.myprogress.index') }}">
                        <i class="fa-solid fa-chart-pie"></i>
                        <span>Mi Progreso</span>
                    </a>
                </li>

                {{-- @can('denyExternal') --}}

                <li class="{{ setActive('aula.surveys.*') }}">
                    <a href="{{ route('aula.surveys.index') }}" class="nav-link">
                        <i class="fa-solid fa-square-poll-vertical"></i>
                        <span>Encuestas</span>
                        @if (validateSurveys())
                            <i class="fa-solid fa-circle-exclamation surveys-notify"></i>
                        @endif
                    </a>
                </li>

                @if (hasForgettingCurve())

                <li class="{{ setActive('aula.forgettingCurve.*') }}">
                    <a href="{{ route('aula.forgettingCurve.index') }}" class="nav-link">
                        <i>
                            <svg class="icon icon-tabler icon-tabler-ease-out-control-point" width="24" height="24"
                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M3 21s10 -16 18 -16" />
                                <path d="M7 5a2 2 0 1 1 -4 0a2 2 0 0 1 4 0z" />
                                <path d="M7 5h2" />
                                <path d="M14 5h-2" />
                            </svg>
                        </i>
                        <span>Curva del olvido</span>
                        @if (validateForgettingCurve())
                            <i class="fa-solid fa-circle-exclamation surveys-notify"></i>
                        @endif
                    </a>
                </li>

                @endif

                {{-- @endcan --}}

            @endcan

            @can('allowInstructor')
                <li class="{{ setActive('aula.userSurveysInstructor.*') }}">
                    <a href="{{ route('aula.userSurveysInstructor.index') }}" class="nav-link">
                        <i class="fa-solid fa-square-poll-vertical"></i>
                        <span>Resultado de encuestas</span>
                    </a>
                </li>
            @endcan

            @can('allCompany')
                <li class="{{ setActive('aula.kpisCompany.*') }}">
                    <a href="{{ route('aula.kpisCompany.index') }}" class="nav-link">
                        <i class="fa-solid fa-chart-simple"></i>
                        <span>KPIs de la empresa</span>
                    </a>
                </li>
                <li class="{{ setActive('aula.docCompany.*') }}">
                    <a href="{{ route('aula.docCompany.index') }}" class="nav-link">
                        <i class="fa-regular fa-folder-open"></i>
                        <span>Documentos de la empresa</span>
                    </a>
                </li>
                <li class="{{ setActive('aula.userCompany.*') }}">
                    <a href="{{ route('aula.userCompany.index') }}" class="nav-link">
                        <i class="fa-solid fa-users"></i>
                        <span>Usuarios de la empresa</span>
                    </a>
                </li>

                <li class="{{ setActive('aula.userEvaluationsCompany.*') }}">
                    <a href="{{ route('aula.userEvaluationsCompany.index') }}" class="nav-link">
                        <i class="fa-solid fa-square-poll-horizontal"></i>
                        <span>Evaluaciones</span>
                    </a>
                </li>
            @endcan

            @can('allowSupervisor')
                <li class="{{ setActive('aula.supervisor.events.*') }}">
                    <a href="{{ route('aula.supervisor.events.index') }}" class="nav-link">
                        <i class="fa-solid fa-calendar-days"></i>
                        <span>Eventos</span>
                    </a>
                </li>

                <li class="{{ setActive('aula.certification.index') }}">
                    <a href="{{ route('aula.certification.index') }}" class="nav-link">
                        <i class="fa-solid fa-square-poll-horizontal"></i>
                        <span>Certificados y Documentos</span>
                    </a>
                </li>

                <li class="{{ setActive('aula.supervisor.kpi.*') }}">
                    <a href="{{ route('aula.supervisor.kpi.index') }}" class="nav-link">
                        <i class="fa-solid fa-chart-simple"></i>
                        <span>KPIs</span>
                    </a>
                </li>
            @endcan

            <li>
                <a href="{{ route('home.index') }}" id="return-main-view" class="nav-link">
                    <i class="fa-solid fa-reply"></i>
                    <span>Página principal</span>
                </a>
            </li>

            <li>
                <a href="#" class="nav-link" id="close-auth-user" data-swal-toast-template="#my-template">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <span>Cerrar sesión</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>

        </ul>

    </aside>
</div>
