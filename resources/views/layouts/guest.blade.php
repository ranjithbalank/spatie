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
        /* Base Styles */
        body {
            font-family: 'Figtree', sans-serif;
            color: #1F2937;
            /* Dark gray for text */
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;

            /* Pleasant Feel Gradient Animation */
            background: linear-gradient(135deg, #f0f4f8, #ffc403b7, #d4dee8, #ffc403b7);
            background-size: 400% 400%;
            animation: backgroundShift 15s ease infinite;
        }

        /* Keyframe for the gentle background animation */
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

        /* Floating animation for elements */
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

        /* Floating shadow effect for the card */
        @keyframes shadowPulse {

            0%,
            100% {
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            50% {
                box-shadow: 0 15px 20px -3px rgba(0, 0, 0, 0.15), 0 6px 8px -2px rgba(0, 0, 0, 0.08);
            }
        }

        /* Container for the page */
        .page-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 2.5rem 0;
        }

        /* Logo and Title */
        .logo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 2rem;
            animation: float 4s ease-in-out infinite;
            /* Apply the floating animation here */
        }

        .logo-section .logo-text {
            font-size: 1.25rem;
            font-weight: 600;
            transition: transform 0.3s ease-in-out;
            color: #4A5568;
        }

        .logo-section:hover .logo-text {
            transform: scale(1.05);
        }

        /* Card container for forms */
        .card-container {
            width: 100%;
            max-width: 28rem;
            margin-top: 1.5rem;
            padding: 2.5rem 1.5rem;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 0.75rem;
            border: 1px solid rgba(199, 134, 50, 0.5);
            opacity: 0;
            animation: fadeIn 0.8s ease-out forwards, shadowPulse 4s ease-in-out infinite;
            /* Apply multiple animations */
        }

        /* Keyframe for a smooth fade-in */
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
    </style>
</head>

<body>
    <div class="page-container">
        <div class="logo-section">
            <a href="/" class="flex flex-col items-center">
                <x-application-logo class="w-20 h-10 fill-current text-gray-500" />
                <span class="logo-text">MyDMW - Your Digital Management Workspace</span>
            </a>
        </div>

        <div class="card-container">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
