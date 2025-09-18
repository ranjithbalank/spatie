<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> MyDMW </title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Figtree', sans-serif;
            color: #1F2937;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;

            background: linear-gradient(135deg, #ffffff, #ffffff, #ffffff, #ffffff);
            background-size: 400% 400%;
            animation: backgroundShift 15s ease infinite;
        }

        @keyframes backgroundShift {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-5px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        @keyframes shadowPulse {

            0%,
            100% {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0), 0 4px 6px -2px rgba(0, 0, 0, 0);
            }

            50% {
                box-shadow: 0 15px 20px -3px rgba(0, 0, 0, 0), 0 6px 8px -2px rgba(0, 0, 0, 0);
            }
        }

        .page-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2.5rem 1rem;
        }

        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
            animation: float 4s ease-in-out infinite;
        }

        .logo-text {
            font-size: 1.125rem;
            /* smaller for mobile */
            font-weight: 600;
            transition: transform 0.3s ease-in-out;
            color: #4A5568;
            text-align: center;
        }

        .logo-section:hover .logo-text {
            transform: scale(1.05);
        }

        .card-container {
            width: 100%;
            max-width: 24rem;
            /* smaller width for mobile */
            padding: 2rem 1rem;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 0.75rem;
            border: 1px solid rgba(199, 134, 50, 0.5);
            opacity: 0;
            animation: fadeIn 0.8s ease-out forwards, shadowPulse 4s ease-in-out infinite;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Responsive tweaks */
        @media (min-width: 640px) {
            .logo-text {
                font-size: 1.25rem;
            }

            .card-container {
                max-width: 28rem;
                padding: 2.5rem 1.5rem;
            }
        }
    </style>
</head>

<body>
    <div class="page-container">
        <div class="logo-section">
            <a href="/" class="flex flex-col items-center">
                <x-application-logo class="w-16 h-16 sm:w-20 sm:h-20 fill-current text-gray-500" />
                <span class="logo-text pt-5">MyDMW </span>
                <!-- <span class="logo-text">work made easy</span> -->
            </a>
        </div>

        <div class="card-container">
            {{ $slot }}
        </div>
    </div>
</body>

</html>