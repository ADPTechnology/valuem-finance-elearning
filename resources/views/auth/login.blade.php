@extends('auth.layouts.login-layout')

@section('title', 'Aula Virtual | Login')

@section('content')

    <section class="background-radial-gradient overflow-hidden">
        <span class="bg-filter"></span>

        <div
            class="container px-4 py-5 px-md-5 text-center text-lg-start my-5 h-100 d-flex justify-content-center  min-vh-100">
            <div class="row w-100 gx-lg-5 align-items-center">

                <div class="col-lg-5 col-md-12 col-sm-12 mb-lg-0 position-relative ">
                    <div class="container-image">
                        <img src="{{ asset('assets/login/img/logo.png') }}" alt="logo" class="img-fluid">
                    </div>
                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>
                    <div class="card bg-glass" style="">
                        <div class="card-body container-form px-3 py-3 px-md-3">
                            <form method="POST" action="{{ route('login') }}" role="form">
                                @csrf

                                <div class="container-description">
                                    <h1>Bienvenido(a)</h1>
                                    <p>a un paso de vivir la <strong>#experienciaHAMA</strong></p>
                                </div>

                                <div class="input-box mb-3">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend color-input-bk icon-color">
                                                <div class="input-group-text" style="height: 100%; border-radius: 0">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            </div>
                                            <input id="dni" name="dni" type="text"
                                                class="form-control color-input-bk @error('dni') is-invalid @enderror"
                                                required autocomplete="dni" value="{{ old('dni') }}"
                                                placeholder="Usuario (DNI)">
                                        </div>
                                    </div>

                                    @error('dni')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                                <div class="input-box mb-3">

                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-prepend color-input-bk icon-color">
                                                <div class="input-group-text" style="height: 100%; border-radius: 0">
                                                    <i class="fas fa-lock"></i>
                                                </div>
                                            </div>
                                            <input id="password" name="password" type="password" required
                                                class="form-control color-input-bk @error('dni') is-invalid @enderror"
                                                placeholder="Contraseña">
                                        </div>
                                    </div>

                                </div>


                                <button type="submit" class="btn btn-ingresar w-100 my-2 ">{{ 'INICIAR SESIÓN' }}</button>

                            </form>
                        </div>
                    </div>
                    <div class="text-center mt-5 mb-5">
                        <button type="button" class="btn btn-link btn-floating mx-1">
                            <i class="fab fa-facebook-f"></i>
                        </button>
                        <button type="button" class="btn btn-link btn-floating mx-1">
                            <i class="fab fa-instagram"></i>
                        </button>
                    </div>
                </div>


                <div id="capaTwo" class="col-lg-7 col-md-12 col-sm-12 mb-lg-0 capaTwo" style="z-index: 10">

                    <div class="container-carousel d-flex justify-content-end">
                        <div id="carouselExampleIndicators" style="width: 500px; height: 500px;"
                            class="carousel slide carousel-fade" data-bs-ride="carousel">
                            <div class="carousel-indicators">

                                @php
                                    $activeIndicators = 'active';
                                    $number = 0;
                                @endphp

                                @forelse ($sliderImages as $sliderImage)
                                    <button type="button" data-bs-target="#carouselExampleIndicators"
                                        class="{{ $activeIndicators }}" data-bs-slide-to="{{ $number }}"
                                        aria-current="true" aria-label="Slide {{ $number + 1 }}"></button>
                                    @php
                                        $activeIndicators = '';
                                        $number++;
                                    @endphp
                                @empty
                                    <button type="button" data-bs-target="#carouselExampleIndicators"
                                        class="{{ $activeIndicators }}" data-bs-slide-to="{{ $number }}"
                                        aria-current="true" aria-label="Slide {{ $number + 1 }}"></button>
                                @endforelse

                            </div>
                            <div class="carousel-inner container-carousel-inner" style="width: 100%; height: 100%">

                                @php
                                    $activeCarousel = 'active';
                                @endphp

                                @forelse ($sliderImages as $sliderImage)
                                    <div class="carousel-item {{ $activeCarousel }}">
                                        <img src="{{ verifyImage($sliderImage->file) }}" class="d-block h-100 img-slider"
                                            style="object-fit:cover;">
                                        {!! $sliderImage->content !!}

                                    </div>
                                    @php
                                        $activeCarousel = '';
                                    @endphp
                                @empty
                                    <div class="carousel-item {{ $activeCarousel }}">
                                        <img src=" {{ asset('assets/login/img/left-login.jpg') }} "
                                            class="d-block h-100 img-slider" style="object-fit:cover;">
                                    </div>
                                @endforelse

                            </div>

                            @if ($sliderImages->count() > 1)
                                <button class="carousel-control-prev" type="button"
                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Anterior</span>
                                </button>
                                <button class="carousel-control-next" type="button"
                                    data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Siguiente</span>
                                </button>
                            @endif

                        </div>
                    </div>


                </div>

            </div>
        </div>
    </section>

@endsection
