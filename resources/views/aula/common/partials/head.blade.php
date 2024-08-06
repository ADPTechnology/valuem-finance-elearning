<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'E-learning | Home')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">

    <!-- General CSS Files -->
    <link rel="stylesheet" href="{{ asset('assets/common/modules/bootstrap/css/bootstrap.min.css') }}">

    <script src="https://kit.fontawesome.com/469f55554f.js" crossorigin="anonymous"></script>

    <link href="https://cdn.datatables.net/v/bs4/dt-1.13.7/r-2.5.0/datatables.min.css" rel="stylesheet">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('assets/common/modules/jqvmap/dist/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/modules/summernote/summernote-bs4.css') }}">

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/common/modules/bootstrap-daterangepicker/daterangepicker.css') }}">

    <link rel="stylesheet"
        href="{{ asset('assets/common/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/common//modules/izitoast/css/iziToast.min.css') }}">

    <!-- Template CSS -->
    <link rel="stylesheet" href="{{ asset('assets/common/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/components.css') }}">


    {{-- ----- IZI MODAL ---- --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.6.1/css/iziModal.min.css"
        integrity="sha512-3c5WiuZUnVWCQGwVBf8XFg/4BKx48Xthd9nXi62YK0xnf39Oc2FV43lIEIdK50W+tfnw2lcVThJKmEAOoQG84Q=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!----   Jquery UI ---->

    <link rel="stylesheet" href="{{ asset('assets/common/css/jquery-ui.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/aula/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/fonts.css') }}">

    @yield('extra-head')

    <!-- VIDEO.JS ---->
    <link href="https://vjs.zencdn.net/8.3.0/video-js.css" rel="stylesheet" />


</head>
