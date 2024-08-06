<head>

    <meta charset="UTF-8">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

	<meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Hama Perú | Admin')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
	{{-- <link rel="stylesheet" href="{{asset('assets/common/modules/datatables/DataTables-1.10.16/css/dataTables.bootstrap4.min.css')}}"> --}}

   <!-- General CSS Files -->
	<link rel="stylesheet" href="{{asset('assets/common/modules/bootstrap/css/bootstrap.min.css')}}">

	<script src="https://kit.fontawesome.com/469f55554f.js" crossorigin="anonymous"></script>


	<link href="https://cdn.datatables.net/v/bs4/dt-1.13.7/r-2.5.0/datatables.min.css" rel="stylesheet">

    {{-- FILEPOND --}}

    <link rel="stylesheet" href="{{ asset('assets/common/css/filepond/filepond.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/common/css/filepond/filepond-image-preview.css') }}">

	<!-- CSS Libraries -->
	<link rel="stylesheet" href="{{asset('assets/common/modules/jqvmap/dist/jqvmap.min.css')}}">
	<link rel="stylesheet" href="{{asset('assets/common/modules/summernote/summernote-bs4.css')}}">


	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

	{{-- Date range picker --}}
	<link rel="stylesheet" href="{{asset('assets/common/modules/bootstrap-daterangepicker/daterangepicker.css')}}">

	<link rel="stylesheet" href="{{asset('assets/common/modules/bootstrap-timepicker/css/bootstrap-timepicker.min.css')}}">

	<link rel="stylesheet" href="{{asset('assets/common//modules/izitoast/css/iziToast.min.css')}}">

	{{-- DropZone --}}

	<link rel="stylesheet" href="{{asset('assets/common/modules/dropzonejs/dropzone.css')}}">

	<!-- Template CSS -->
	<link rel="stylesheet" href="{{asset('assets/common/css/style.css')}}">
	<link rel="stylesheet" href="{{asset('assets/common/css/components.css')}}">

	@yield('extra-head')

	<link rel="stylesheet" href="{{asset('assets/admin/css/custom.css')}}">
	<link rel="stylesheet" href="{{asset('assets/common/css/fonts.css')}}">

	{{-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css"> --}}

	{{-- <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.css" rel="stylesheet"> --}}

</head>
