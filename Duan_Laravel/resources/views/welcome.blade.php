<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
        
    <style>
        .btn-green {
            display: inline-block;
            padding: 10px 20px;
            border: 2px solid #90EE90;
            /* Light green border */
            border-radius: 5px;
            background-color: #90EE90;
            /* Light green background */
            color: #fff;
            margin: 5px;
            font-weight: bold;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-green:hover {
            background-color: #32CD32;
            /* Darker green background on hover */
            border-color: #32CD32;
            /* Darker green border on hover */
        }
    </style>

</head>

<body class="antialiased">
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div class="card">
            @if (Route::has('login'))
                <div class="flex space-x-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="login-link btn-green">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="login-link btn-green">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="register-link btn-green">Register</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
</body>

</html>