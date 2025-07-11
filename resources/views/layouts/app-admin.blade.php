<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Agrosector Map</title>
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.0/css/dataTables.dataTables.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/2.3.0/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .pt-15 {
            padding-top: 3.8rem;
        }

        .select2-container .select2-selection--single {
            height: 40px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #000000 !important;
            line-height: 38px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #000000 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 38px !important;
        }
    </style>
</head>

<body class="pt-15 lg:pt-0 bg-white">
    @extends('layouts.sidebar-admin')

    <main id="main-content" class="flex-1 bg-white transition-all duration-300 ml-0 lg:ml-64">
        @yield('content')
    </main>
</body>

</html>
