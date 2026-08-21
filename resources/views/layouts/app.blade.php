<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('images/logo.png') }}">
    <title>
        @yield('title', 'QR Attendance System')
    </title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

    <link href="{{ asset('asset/style.css') }}" rel="stylesheet">

    @stack('styles')
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <a class="navbar-brand fw-bold d-flex align-items-center gap-2" href="/">
                <img src="{{ asset('images/logo.png') }}" alt="QR Attendance" height="32" class="navbar-logo">
                QR Attendance
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarContent">

                @auth

                    <ul class="navbar-nav me-auto">

                        @if (auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/admin') }}">
                                    Dashboard
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/admin/invitations') }}">
                                    Invitations
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/admin/attendance') }}">
                                    Attendance
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/admin/events') }}">
                                    Events
                                </a>
                            </li>
                        @endif

                        @if (auth()->user()->role === 'scanner' || auth()->user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ url('/') }}">
                                    Scanner
                                </a>
                            </li>
                        @endif

                    </ul>

                    <div class="dropdown">

                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <span class="dropdown-item-text">
                                    {{ auth()->user()->email }}
                                </span>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf

                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right"></i>
                                        Logout
                                    </button>
                                </form>
                            </li>

                        </ul>

                    </div>
                @else
                    <div class="ms-auto">

                        <!-- <a href="{{ route('login') }}" class="btn btn-outline-light">
                            Login
                        </a> -->
                        <a class="btn btn-outline-light" href="{{ url('/invitation-request') }}">
                            Invitation Request
                        </a>
                    </div>

                @endauth

            </div>
        </div>
    </nav>


    <!-- Flash Messages -->

    <div class="container mt-3">

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle"></i>

                {{ session('error') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif


        @if ($errors->any())

            <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <strong>Please fix the following:</strong>

                <ul class="mb-0 mt-2">

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach

                </ul>

                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

            </div>

        @endif

    </div>
    <main class="py-4">

        @yield('content')

    </main>

    @if (session('success'))
        <div id="layoutSuccessPopup" class="success-popup">
            <div class="success-popup-card">
                <button type="button" class="success-popup-close" id="layoutSuccessClose" aria-label="Close">
                    <i class="bi bi-x-lg"></i>
                </button>
                <div class="success-popup-icon">
                    <i class="bi bi-check-lg"></i>
                </div>
                <p class="success-popup-kicker">Success</p>
                <h3>Done!</h3>
                <p class="success-popup-name">{{ session('success') }}</p>
                <button type="button" class="btn btn-primary mt-3" id="layoutSuccessOk">OK</button>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function () {
            const popup = document.getElementById('layoutSuccessPopup');
            if (!popup) {
                return;
            }

            const closePopup = function () {
                popup.classList.add('d-none');
            };

            document.getElementById('layoutSuccessClose')?.addEventListener('click', closePopup);
            document.getElementById('layoutSuccessOk')?.addEventListener('click', closePopup);

            popup.addEventListener('click', function (event) {
                if (event.target === popup) {
                    closePopup();
                }
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
