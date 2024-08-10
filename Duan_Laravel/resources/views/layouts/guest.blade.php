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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Scripts -->
    @viteReactRefresh
    @vite(['resources/css/app.css', 'resources/js/app.js'])


    <style>
        .login-form {
            max-width: 400px;
            margin: 0 auto;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 1rem;
        }

        .input-group {
            position: relative;
            margin-bottom: 0.5rem;
        }

        .input-group i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
        }

        .input-group input {
            width: 100%;
            padding: 10px 10px 10px 35px;
            border: 1px solid #e5e7eb;
            border-radius: 4px;
            background-color: #f3f4f6;
        }

        .remember-me {
            margin-bottom: 1rem;
        }

        .submit-button {
            text-align: right;
        }

        .submit-button button {
            background-color: #60a5fa;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .submit-button button:hover {
            background-color: #3b82f6;
        }

        .mt-2 {
            margin-top: 0.5rem;
        }

        .text-red-600 {
            color: #dc2626;
            font-size: 0.875rem;
        }

        /* Add this CSS to your stylesheets */
        .popup {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .popup.show {
            display: flex;
        }

        .popup-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: slideIn 0.5s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }

        .popup-header h2 {
            margin: 0;
            font-size: 1.5em;
        }

        .popup-header .close {
            cursor: pointer;
            font-size: 1.5em;
        }

        .popup-body {
            font-size: 1.2em;
        }

        .popup-body i {
            color: #e74c3c;
            font-size: 2em;
            margin-bottom: 10px;
        }

        .fixed {
            position: fixed;
            top: 1rem;
            /* Điều chỉnh khoảng cách từ đỉnh nếu cần */
            right: 1rem;
            /* Điều chỉnh khoảng cách từ cạnh phải nếu cần */
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
        <div>
            <a href="/">
                <!-- <x-application-logo class="w-20 h-20 fill-current text-gray-500" /> -->
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            {{ $slot }}
        </div>
    </div>
</body>

</html>