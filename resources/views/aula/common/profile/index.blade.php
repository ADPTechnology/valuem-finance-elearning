@extends('aula.common.layouts.masterpage')

@section('navbar-extra-content')
    <nav class="navbar navbar-expand-lg main-navbar @yield('navbarClass')">
        <form class="form-inline mr-auto">
            <ul class="navbar-nav mr-3" style="display: block">
                <li>
                    <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg">
                        <i class="fas fa-bars"></i>
                    </a>
                </li>
            </ul>
            <div class="navbar-container-design">
                <div class="welcome">
                    Bienvenido (a)
                </div>
                <span class="full-name">
                    {{ strtoupper($user->full_name_complete) }}
                </span>
            </div>
        </form>
    </nav>
@endsection

@section('content')

    <div class="content global-container">

        <div class="profile-view-container">

            <section class="card-body body-global-container profile-page-container card z-index-2 principal-container @if ($news->count()) container-grid-profile @endif">

                {{-- información general --}}

                <div class="container-information-main information-hama">

                    <div class="header-title">
                        <span class="title">Información General</span>
                    </div>
                    <div class="line-80"></div>

                    <div class="data-profile-container">
                        <div class="profile-row">
                            <div class="profile-label">Nombres</div>
                            <div class="profile-info">
                                {{ $user->name }}
                            </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Apellidos</div>
                            <div class="profile-info">
                                {{ $user->full_surname }}
                            </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">DNI</div>
                            <div class="profile-info">{{ $user->dni }}</div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">ECM</div>
                            <div class="profile-info">
                                {{ $user->company()->select('id', 'description')->first()->description ?? '-' }}
                            </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Unidad</div>
                            <div class="profile-info">
                                @if ($user->miningUnits()->count() == 0)
                                    -
                                @else
                                    @foreach ($user->miningUnits()->select('mining_units.id', 'mining_units.description')->get() as $miningUnit)
                                        <div>
                                            {{ $miningUnit->description }}
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Cargo</div>
                            <div class="profile-info"> {{ $user->position ?? '-' }} </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Correo</div>
                            <div class="profile-info"> {{ $user->email }} </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Celular</div>
                            <div class="profile-info"> {{ $user->telephone ?? '-' }} </div>
                        </div>
                        <div class="profile-row">
                            <div class="profile-label">Perfil</div>
                            <div class="profile-info"> {{ $user->profile_user ?? '-' }} </div>
                        </div>

                    </div>

                    <div id="form-container-update-password">
                        <form action="{{ route('aula.profile.updatePassword', ['user' => Auth::user()]) }}" method="POST"
                            id="user_password_update_form">

                            @include('aula.common.profile.partials.boxes._form_update_password')

                        </form>
                    </div>

                </div>

                @if ($news->count())
                    @foreach ($news as $new)
                        <div class="news-hama">

                            <div class="image">
                                <img src="{{ verifyImage($new->file) }}" width="100" alt="">
                            </div>
                            <div class="text-description">
                                <span class="title"> {{ $new->title }} </span>
                            </div>
                            {!! $new->content !!}
                        </div>
                    @endforeach
                @endif

            </section>
        </div>


    </div>
@endsection
