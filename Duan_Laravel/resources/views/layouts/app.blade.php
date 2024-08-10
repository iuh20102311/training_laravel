<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        input:disabled {
            background-color: #e2e8f0;
            /* Tương đương với bg-gray-300 */
            color: #4b5563;
            /* Tương đương với text-gray-700 */
            border-color: #9ca3af;
            /* Màu viền đậm hơn khi bị vô hiệu hóa */
        }

        select:disabled {
            background-color: #e2e8f0;
            /* Tương đương với bg-gray-300 */
            color: #4b5563;
            /* Tương đương với text-gray-700 */
            border-color: #9ca3af;
            /* Màu viền đậm hơn khi bị vô hiệu hóa */
        }

        .select2-container--default .select2-selection--single {
            height: 38px;
            width: 100%;
            margin-top: 5px;
            /* Match the height of your other selects */
            padding: 4px 12px;
            /* Adjust padding */
            border: 1px solid #d1d5db;
            /* Match the border color */
            border-radius: 0.375rem;
            /* Match the border radius */
        }

        .select2-container {
            width: 100% !important;
            /* Ensure full width of the container */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 28px;

            /* Adjust line height */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 36px;
            margin-top: 5px;

            /* Adjust arrow height */
        }
    </style>
</head>

<body class="font-sans antialiased" id="body-user">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.navigation')

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
</body>

</html>