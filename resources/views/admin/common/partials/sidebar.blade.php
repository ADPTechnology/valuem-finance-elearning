<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">

        <div class="sidebar-brand p-4 dynamic-logo-container" id="dynamic-logo-container">
            @include('admin.common.partials.components._logo_content')
        </div>

        <div class="sidebar-brand hidden sidebar-brand-sm p-1 align-items-center dynamic-logo-container">
            @include('admin.common.partials.components._logo_content')
        </div>

        <div id="modules-title-admin">
            <a  class="principal-hama-logo" href="{{ route('admin.home.index') }}">
                <i class="fa-solid fa-house me-2"></i>
                PRINCIPAL
            </a>
            <div class="text-principal-hm">
                MÓDULOS
            </div>
        </div>

        <ul class="sidebar-menu">

            <li class="{{ setActive('admin.home.*') }} home-button" style="display: none;">
                <a href="{{ route('admin.home.index') }}" class="nav-link">
                    <i class="fa-solid fa-house"></i>
                    <span>Inicio</span>
                </a>
            </li>

            <li class="{{ setActive('admin.users.*') }}">
                <a href="{{ route('admin.users.index') }}" class="nav-link">
                    <i class="fa-solid fa-users"></i>
                    <span>Usuarios</span>
                </a>
            </li>

            {{-- <li class="{{ setActive('admin.companies.*') }}">
                <a href="{{ route('admin.companies.index') }}" class="nav-link">
                    <i class="fa-solid fa-building"></i>
                    <span>Empresas</span>
                </a>
            </li> --}}

            {{-- <li class="{{ setActive('admin.ownerCompanies.*') }}">
                <a href="{{ route('admin.ownerCompanies.index') }}" class="nav-link">
                    <i class="fa-regular fa-building"></i>
                    <span>Empresas Titulares</span>
                </a>
            </li> --}}

            {{-- <li class="{{ setActive('admin.miningUnits.*') }}">
                <a href="{{ route('admin.miningUnits.index') }}" class="nav-link">
                    <i class="fa-solid fa-mountain-city"></i>
                    <span>Unidades Mineras</span>
                </a>
            </li> --}}

            {{-- <li class="{{ setActive('admin.rooms.*') }}">
                <a href="{{ route('admin.rooms.index') }}" class="nav-link">
                    <i class="fa-solid fa-chalkboard-user"></i>
                    <span>Salas</span>
                </a>
            </li> --}}

            {{-- <li class="{{ setActive('admin.coursetypes.*') }}">
                <a href="{{ route('admin.coursetypes.index') }}" class="nav-link">
                    <i class="fa-solid fa-book-bookmark"></i>
                    <span>Tipos de curso</span>
                </a>
            </li> --}}

            {{-- <li class="{{ setActive('admin.courses.*') }}">
                <a class="nav-link" href="{{ route('admin.courses.index') }}">
                    <i class="fa-solid fa-book"></i>
                    <span>Cursos</span>
                </a>
            </li> --}}

            {{-- <li class="{{ setActive('admin.specCourses.*') }}">
                <a class="nav-link" href="{{ route('admin.specCourses.index') }}">
                    <i class="fa-solid fa-graduation-cap"></i>
                    <span>Cursos de Espec.</span>
                </a>
            </li> --}}

            <li class="{{ setActive('admin.freeCourses.*') }}">
                <a href="{{ route('admin.freeCourses.index') }}" class="nav-link">
                    <i class="fa-solid fa-book-open"></i>
                    <span>Cursos Libres</span>
                </a>
            </li>

            {{-- <li class="{{ setActive('admin.freeCourseLive.*') }}">
                <a href="{{ route('admin.freeCourseLive.index') }}" class="nav-link">
                    <i class="fa-solid fa-circle-play"></i>
                    <span>Cursos libres en Vivo</span>
                </a>
            </li> --}}

            {{-- <li class="{{ setActive('admin.exams.*') }}">
                <a href="{{ route('admin.exams.index') }}" class="nav-link">
                    <i class="fa-solid fa-file-signature"></i>
                    <span>Exámenes</span>
                </a>
            </li> --}}

            {{-- <li class="{{ setActive('admin.events.*') }}">
                <a href="{{ route('admin.events.index') }}" class="nav-link">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Eventos</span>
                </a>
            </li> --}}

            {{-- <li class="dropdown {{ setActive('admin.webinars.*') }}">
                <a href="javascript:void(0);" class="nav-link has-dropdown">
                    <i class="fa-solid fa-desktop"></i>
                    <span>Webinar</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive('admin.webinars.all.*') }}">
                        <a href="{{ route('admin.webinars.all.index') }}" class="nav-link">
                            <i class="fa-solid fa-circle fa-2xs"></i>
                            <span>Ver todos</span>
                        </a>
                    </li>
                </ul>
            </li> --}}



            {{-- <li class="{{ setActive('admin.forgettingCurve.*') }}">
                <a href="{{ route('admin.forgettingCurve.index') }}" class="nav-link">
                    <i>
                        <svg class="icon icon-tabler icon-tabler-ease-out-control-point" width="19" height="19"
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
                </a>
            </li> --}}

            {{-- <li class="{{ setActive('admin.reportForgettingCurve.*') }}">
                <a href="{{ route('admin.reportForgettingCurve.index') }}" class="nav-link">
                    <i class="fa-solid fa-file-waveform"></i>
                    <span>Reporte curva del olvido</span>
                </a>
            </li> --}}

            {{-- <li class="dropdown {{ setActive('admin.surveys.*') }}">
                <a href="javascript:void(0);" class="nav-link has-dropdown">
                    <i class="fa-solid fa-square-poll-vertical"></i>
                    <span>Encuestas</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ setActive('admin.surveys.all.*') }}">
                        <a href="{{ route('admin.surveys.all.index') }}" class="nav-link">
                            <i class="fa-solid fa-circle fa-2xs"></i>
                            Todos
                        </a>
                    </li>

                    <li class="{{ setActive('admin.surveys.reports.index') }}">
                        <a href="{{ route('admin.surveys.reports.index') }}" class="nav-link">
                            <i class="fa-solid fa-circle fa-2xs"></i>
                            Reporte de encuestados
                        </a>
                    </li>

                    <li class="{{ setActive('admin.surveys.reports.profile.index') }}">
                        <a href="{{ route('admin.surveys.reports.profile.index') }}" class="nav-link">
                            <i class="fa-solid fa-circle fa-2xs"></i>
                            Reporte de perfil de usuario
                        </a>
                    </li>
                </ul>
            </li> --}}

            {{-- <li class="{{ setActive('admin.certifications.*') }}">
                <a href="{{ route('admin.certifications.index') }}" class="nav-link">
                    <i class="fa-solid fa-file-contract"></i>
                    <span>Evaluaciones</span>
                </a>
            </li> --}}

            <li class="{{ setActive('admin.filesManagement.*') }}">
                <a href="{{ route('admin.filesManagement.index') }}" class="nav-link">
                    <i class="fa-regular fa-folder-open"></i>
                    <span>Gestión de Archivos</span>
                </a>
            </li>

        </ul>

    </aside>
</div>
