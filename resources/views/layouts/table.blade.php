<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Prediksi Panen Padi</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Styles -->
{{--    <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">--}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker3-1.10.0.standalone.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pro.css') }}">
    <!--<link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v6.4.0/css/pro.css">-->
    @livewireStyles
    <link href="{{ asset('DataTables-1.13.4/css/dataTables.bootstrap5.min.css') }}" rel="stylesheet" />{{--
    <link href="{{ asset('AutoFill-2.5.3/css/autoFill.bootstrap5.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('Buttons-2.3.6/css/buttons.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('ColReorder-1.6.2/css/colReorder.bootstrap5.css') }}" rel="stylesheet" />--}}
    <link href="{{ asset('DateTime-1.4.1/css/dataTables.dateTime.min.css') }}" rel="stylesheet" />{{--
    <link href="{{ asset('FixedColumns-4.2.2/css/fixedColumns.bootstrap5.css') }}" rel="stylesheet" />--}}
    <link href="{{ asset('FixedHeader-3.3.2/css/fixedHeader.bootstrap5.min.css') }}" rel="stylesheet" />{{--
    <link href="{{ asset('KeyTable-2.9.0/css/keyTable.bootstrap5.css') }}" rel="stylesheet" />--}}
    <link href="{{ asset('Responsive-2.4.1/css/responsive.bootstrap5.min.css') }}" rel="stylesheet" />{{--
    <link href="{{ asset('RowGroup-1.3.1/css/rowGroup.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('RowReorder-1.3.3/css/rowReorder.bootstrap5.css') }}" rel="stylesheet" />--}}
    <link href="{{ asset('Scroller-2.1.1/css/scroller.bootstrap5.min.css') }}" rel="stylesheet" />{{--
    <link href="{{ asset('SearchBuilder-1.4.2/css/searchBuilder.bootstrap5.css') }}" rel="stylesheet" />
    <link href="{{ asset('SearchPanes-2.1.2/css/searchPanes.bootstrap5.css') }}" rel="stylesheet" />--}}
    <link href="{{ asset('Select-1.6.2/css/select.bootstrap5.min.css') }}" rel="stylesheet" />{{--
    <link href="{{ asset('StateRestore-1.2.2/css/stateRestore.bootstrap5.css') }}" rel="stylesheet" />--}}

    <!-- Scripts -->
    <!--@vite(['resources/css/app.css', 'resources/js/app.js'])-->
    <script type="text/javascript" src="{{ asset('js/app.js') }}" defer></script>
</head>

<body class="font-sans antialiased">
    <x-jet-banner />

    <div class="min-h-screen bg-gray-100">
        @livewire('navigation-menu')

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('modals')
    <script type="text/javascript" src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery-3.7.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker-1.10.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker-1.10.0.id.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker-1.10.0.en-GB.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/papaparse-5.4.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/moment-locales-2.29.4.js') }}"></script>{{--
    <script type="text/javascript" src="{{ asset('JSZip-2.5.0/jszip.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('pdfmake-0.2.7/pdfmake.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('pdfmake-0.2.7/vfs_fonts.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('DataTables-1.13.4/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DataTables-1.13.4/js/dataTables.bootstrap5.min.js') }}"></script>{{--
    <script type="text/javascript" src="{{ asset('AutoFill-2.5.3/js/dataTables.autoFill.js') }}"></script>
    <script type="text/javascript" src="{{ asset('AutoFill-2.5.3/js/autoFill.bootstrap5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Buttons-2.3.6/js/dataTables.buttons.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Buttons-2.3.6/js/buttons.bootstrap5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Buttons-2.3.6/js/buttons.colVis.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Buttons-2.3.6/js/buttons.html5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Buttons-2.3.6/js/buttons.print.js') }}"></script>
    <script type="text/javascript" src="{{ asset('ColReorder-1.6.2/js/dataTables.colReorder.js') }}"></script>
    <script type="text/javascript" src="{{ asset('ColReorder-1.6.2/js/colReorder.bootstrap5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('DateTime-1.4.1/js/dataTables.dateTime.js') }}"></script>
    <script type="text/javascript" src="{{ asset('FixedColumns-4.2.2/js/dataTables.fixedColumns.js') }}"></script>
    <script type="text/javascript" src="{{ asset('FixedColumns-4.2.2/js/fixedColumns.bootstrap5.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('FixedHeader-3.3.2/js/dataTables.fixedHeader.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('FixedHeader-3.3.2/js/fixedHeader.bootstrap5.min.js') }}"></script>{{--
    <script type="text/javascript" src="{{ asset('KeyTable-2.9.0/js/dataTables.keyTable.js') }}"></script>
    <script type="text/javascript" src="{{ asset('KeyTable-2.9.0/js/keyTable.bootstrap5.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('Responsive-2.4.1/js/dataTables.responsive.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Responsive-2.4.1/js/responsive.bootstrap5.min.js') }}"></script>{{--
    <script type="text/javascript" src="{{ asset('RowGroup-1.3.1/js/dataTables.rowGroup.js') }}"></script>
    <script type="text/javascript" src="{{ asset('RowGroup-1.3.1/js/rowGroup.bootstrap5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('RowReorder-1.3.3/js/dataTables.rowReorder.js') }}"></script>
    <script type="text/javascript" src="{{ asset('RowReorder-1.3.3/js/rowReorder.bootstrap5.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('Scroller-2.1.1/js/dataTables.scroller.min.js') }}" defer></script>
    <script type="text/javascript" src="{{ asset('Scroller-2.1.1/js/scroller.bootstrap5.min.js') }}"></script>{{--
    <script type="text/javascript" src="{{ asset('SearchBuilder-1.4.2/js/dataTables.searchBuilder.js') }}"></script>
    <script type="text/javascript" src="{{ asset('SearchBuilder-1.4.2/js/searchBuilder.bootstrap5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('SearchPanes-2.1.2/js/dataTables.searchPanes.js') }}"></script>
    <script type="text/javascript" src="{{ asset('SearchPanes-2.1.2/js/searchPanes.bootstrap5.js') }}"></script>--}}
    <script type="text/javascript" src="{{ asset('Select-1.6.2/js/dataTables.select.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('Select-1.6.2/js/select.bootstrap5.min.js') }}"></script>{{--
    <script type="text/javascript" src="{{ asset('StateRestore-1.2.2/js/dataTables.stateRestore.js') }}"></script>
    <script type="text/javascript" src="{{ asset('StateRestore-1.2.2/js/stateRestore.bootstrap5.js') }}"></script>--}}
    @livewireScripts
    @stack('extra-script')
</body>

</html>
