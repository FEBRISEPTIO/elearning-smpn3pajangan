<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Learning - Siswa')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Active link styling */
        .active-sidebar-link {
            background-color: #3b82f6;
            color: white;
        }

        .active-sidebar-link:hover {
            background-color: #2563eb;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                position: fixed;
                z-index: 50;
                height: 100vh;
                top: 0;
                left: 0;
                width: 280px;
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }

            .mobile-menu-btn {
                display: block;
                position: fixed;
                top: 20px;
                left: 20px;
                z-index: 30;
                background-color: #3b82f6;
                color: white;
                border: none;
                padding: 10px;
                border-radius: 6px;
                cursor: pointer;
            }

            .main-content {
                margin-left: 0 !important;
                width: 100%;
                padding-top: 80px;
            }
        }

        @media (min-width: 769px) {
            .mobile-menu-btn {
                display: none;
            }

            .overlay {
                display: none !important;
            }

            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                width: 280px;
                z-index: 40;
                background-color: white;
                box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            }

            .main-content {
                margin-left: 280px;
                width: calc(100% - 280px);
                min-height: 100vh;
            }
        }

        /* CSS-only toggle for mobile sidebar */
        #mobileToggle:checked~.sidebar {
            transform: translateX(0);
        }

        #mobileToggle:checked~.overlay {
            display: block;
        }
    </style>
</head>

<body class="bg-gray-100">
    <!-- Hidden checkbox for CSS-only toggle -->
    <input type="checkbox" id="mobileToggle" class="hidden">

    <!-- Mobile menu button -->
    <label for="mobileToggle" class="mobile-menu-btn">
        <i class="fas fa-bars text-xl"></i>
    </label>

    <!-- Overlay for mobile -->
    <label for="mobileToggle" class="overlay"></label>

    <!-- Sidebar -->
    <div class="sidebar flex flex-col">
        <!-- Sidebar header -->
        <div class="p-6 border-b">
            <div class="flex items-center">
                <i class="fas fa-graduation-cap text-blue-600 text-2xl mr-3"></i>
                <h1 class="text-2xl font-bold text-gray-900">
                    E-Learning - Siswa
                </h1>
            </div>
        </div>

        <!-- Navigation links -->
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('siswa.dashboard') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('siswa.dashboard') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-tachometer-alt w-6 text-center"></i>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>

            <a href="{{ route('siswa.materi.index') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('siswa.materi.*') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-book w-6 text-center"></i>
                <span class="ml-3 font-medium">Materi</span>
            </a>

            <a href="{{ route('siswa.tugas.index') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('siswa.tugas.*') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-tasks w-6 text-center"></i>
                <span class="ml-3 font-medium">Tugas</span>
            </a>

            <a href="{{ route('siswa.nilai.index') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('siswa.nilai.*') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-chart-bar w-6 text-center"></i>
                <span class="ml-3 font-medium">Nilai</span>
            </a>

            {{-- <a href="{{ route('siswa.rapor.index') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('siswa.rapor.*') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-file-alt w-6 text-center"></i>
                <span class="ml-3 font-medium">Rapor</span>
            </a> --}}
        </nav>

        <!-- User info and logout -->
        <div class="p-4 border-t">
            <div class="flex items-center mb-4">
                <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700">{{ Auth::user()->username }}</p>
                    <p class="text-xs text-gray-500">Siswa</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center w-full p-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg">
                    <i class="fas fa-sign-out-alt w-6 text-center"></i>
                    <span class="ml-3 font-medium">Logout</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    {{-- <main class="main-content p-4 md:p-6 transition-all duration-300"> --}}
        <!-- Flash Messages -->
        @if (session('success'))
            <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                <div class="flex">
                    <div class="shrink-0">
                        <i class="fas fa-check-circle text-green-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-green-700">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <div class="shrink-0">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Auto-hide flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 3000);
    </script>
</body>

</html>
