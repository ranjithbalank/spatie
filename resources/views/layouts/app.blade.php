<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>MyDMW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Loading Screen Styles */
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #fafafa 0%, #fdfeff 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        .loading-logo {
            width: 100px;
            height: 100px;
            margin-bottom: 20px;
            background: white;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            animation: pulse 1.5s infinite alternate;
            padding: 15px;
        }

        .loading-logo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        /* .loading-spinner {
            width: 50px;
            height: 50px;
            border: 5px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: #f87103;
            animation: spin 1s ease-in-out infinite;
            margin-bottom: 20px;
        } */

        .loading-text {
            color: rgb(14, 1, 1);
            font-size: 1.5rem;
            font-weight: 500;
            margin-bottom: 10px;
            font-family: 'Figtree', sans-serif;
        }

        .loading-subtext {
            color: rgba(20, 1, 1, 0.8);
            font-size: 1rem;
            font-family: 'Figtree', sans-serif;
        }

        .progress-container {
            width: 200px;
            height: 4px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            margin-top: 20px;
            overflow: hidden;
        }

        .progress-bar {
            height: 100%;
            width: 0%;
            background: #f87103;
            border-radius: 4px;
            transition: width 0.4s ease;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            from {
                transform: scale(1);
                opacity: 1;
            }

            to {
                transform: scale(1.05);
                opacity: 0.8;
            }
        }

        .loaded #loading-screen {
            opacity: 0;
            pointer-events: none;
        }

        /* Prevent scrolling while loading */
        body:not(.loaded) {
            overflow: hidden;
        }
    </style>
    <style>
        /* Make Select2 single select same height as Tailwind input */
        .select2-container .select2-selection--single {
            height: 40px !important;
            /* Tailwind h-10 */
            border: 1px solid #d1d5db !important;
            /* Tailwind border-gray-300 */
            border-radius: 0.375rem !important;
            /* Tailwind rounded-md */
            display: flex;
            align-items: center;
            padding: 0 0.5rem;
            font-size: 0.875rem;
            /* Tailwind text-sm */
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 38px !important;
            /* slightly less than height */
            color: #374151;
            /* Tailwind text-gray-700 */
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100% !important;
            top: 0 !important;
        }
    </style>
</head>

<body class="font-sans antialiased bg-gray-100">
    <!-- Loading Screen -->
    <div id="loading-screen">
        <div class="loading-logo">
            <img src="/images/logo.png" alt="MyDMW Logo">
        </div>
        <div class="loading-spinner"></div>
        <div class="loading-text">MyDMW</div>
        <div class="loading-subtext">Redirecting ..</div>
        <div class="progress-container">
            <div class="progress-bar" id="loading-progress"></div>
        </div>
    </div>

    <!-- Topbar -->
    <header class="bg-white border-b shadow fixed w-full z-20">
        @include('layouts.navigation')
    </header>

    <div class="flex pt-16 min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white border-r shadow hidden md:block">
            @include('layouts.sidebar')
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col">
            <main class="flex-1 p-6">
                <!-- Page header (x-slot) -->
                @isset($header)
                    <section class="bg-white shadow mb-6 p-4 rounded">
                        {{ $header }}
                    </section>
                @endisset

                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous">
    </script>
    <!-- Toast container -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
        @if (session('success'))
            <div id="successToast" class="toast align-items-center text-bg-success border-0 show" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('success') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div id="errorToast" class="toast align-items-center text-bg-danger border-0 show" role="alert"
                aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body">
                        {{ session('error') }}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                        aria-label="Close"></button>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Loading screen functionality
        document.addEventListener('DOMContentLoaded', function() {
            const loadingScreen = document.getElementById('loading-screen');
            const progressBar = document.getElementById('loading-progress');
            const body = document.body;

            // Simulate progress (you can replace this with actual loading events)
            let progress = 0;
            const interval = setInterval(() => {
                progress += Math.random() * 10;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(interval);

                    // Add loaded class to body to trigger fade out
                    body.classList.add('loaded');

                    // Remove loading screen after fade out completes
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                        body.style.overflow = 'auto'; // Re-enable scrolling
                    }, 500);
                }
                progressBar.style.width = progress + '%';
            }, 200);

            // Alternatively, use window load event for real page loading
            window.addEventListener('load', function() {
                progressBar.style.width = '100%';
                setTimeout(() => {
                    body.classList.add('loaded');
                    setTimeout(() => {
                        loadingScreen.style.display = 'none';
                        body.style.overflow = 'auto';
                    }, 500);
                }, 300);
            });
        });
    </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    @stack('scripts')
</body>

</html>
