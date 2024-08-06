@extends('home.layout.masterpage')

@section('content')
    <!-- Header Start -->
    <div class="container-fluid bg-header-section py-5 mb-5 page-header">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-lg-10 text-center">
                    <h1 class="display-3 text-white animated slideInDown">Webinars</h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb justify-content-center">
                            <li class="breadcrumb-item"><a class="text-white" href="{{ route('home.index') }}">Inicio</a></li>
                            <li class="breadcrumb-item text-white active" aria-current="page">Webinas</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- Header End -->


    <!-- Webinars Start -->

    @include('home.webinar.partials._webinars')

    <!-- Webinars End -->
@endsection
