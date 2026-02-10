<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'groww') }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <!-- Fonts -->
        <link
            rel="stylesheet"
            href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

        
        <!-- chart JS -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
        {{-- flatpick --}}

        <!--Regular Datatables CSS-->
        <link
            href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"
            rel="stylesheet">
        <!--Responsive Extension Datatables CSS-->
        <link
            href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css"
            rel="stylesheet">


        <!-- laravel notify -->
        @notifyCss
        @include('notify::components.notify')

        <link
            href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700"
            rel="stylesheet"/>
        {{-- / FontAwesome Icons --}}
        <link href="./assets/css/nucleo-icons.css" rel="stylesheet"/>
        <link href="./assets/css/nucleo-svg.css" rel="stylesheet"/>
    </head>
    <body class="font-sans antialiased">
        <x-jet-banner/>

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
                <x:notify-messages />

            </main>
        </div>

        @stack('modals') @livewireScripts
        <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

        <script src="../path/to/soft-ui-dashboard-tailwind.js"></script>
        {{-- flowbit --}}
        <script src="https://unpkg.com/flowbite@1.5.4/dist/datepicker.js"></script>
        <script src="../path/to/flowbite/dist/flowbite.js"></script>
        <script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
        {{-- flatpick --}}
        <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

        {{-- turbolink --}}
        <script
            src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
            data-turbolinks-eval="false"></script>
        <!-- jQuery -->
        <script
            type="text/javascript"
            src="https://code.jquery.com/jquery-3.4.1.min.js"></script>

        <!-- Laravel Notify -->
        @notifyJs
        
        <!--Datatables -->
        <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
        <script
            src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>
        <script>
            $(document).ready(function () {

                var table = $('#example')
                    .DataTable({responsive: true})
                    .columns
                    .adjust()
                    .responsive
                    .recalc();
            });
        </script>
    </body>
</html>
