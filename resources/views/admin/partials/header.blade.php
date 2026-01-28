<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Learning</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
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

            #mainContent {
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

            #mainContent {
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
                @php
                    use App\Models\DataSekolah;
                    $sekolah = DataSekolah::first();
                @endphp

                @if ($sekolah && $sekolah->logo)
                    <img src="{{ Storage::url($sekolah->logo) }}" alt="Logo Sekolah"
                        class="w-8 h-8 object-contain mr-3 rounded">
                @else
                    <i class="fas fa-graduation-cap text-blue-600 text-2xl mr-3"></i>
                @endif
                <div>
                    <h1 class="text-xl font-bold text-gray-900">
                        @if ($sekolah)
                            {{ Str::limit($sekolah->nama_sekolah, 20) }}
                        @else
                            E-Learning
                        @endif
                    </h1>
                    <p class="text-xs text-gray-500 mt-1">
                        @auth
                            @if (Auth::user()->user_type === 'admin')
                                Panel Admin
                            @elseif(Auth::user()->user_type === 'guru')
                                Panel Guru
                            @else
                                Panel Siswa
                            @endif
                        @endauth
                    </p>
                </div>
            </div>
        </div>

        <!-- Navigation links -->
        <nav class="flex-1 p-4 space-y-2">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('admin.dashboard') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-tachometer-alt w-6 text-center"></i>
                <span class="ml-3 font-medium">Dashboard</span>
            </a>

            <!-- Menu Data Sekolah -->
            <a href="{{ route('admin.data-sekolah.index') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('admin.data-sekolah.*') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-school w-6 text-center"></i>
                <span class="ml-3 font-medium">Data Sekolah</span>
            </a>

            <a href="{{ route('admin.siswa.index') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('admin.siswa.*') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-users w-6 text-center"></i>
                <span class="ml-3 font-medium">Siswa</span>
            </a>

            <a href="{{ route('admin.guru.index') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('admin.guru.*') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-chalkboard-teacher w-6 text-center"></i>
                <span class="ml-3 font-medium">Guru</span>
            </a>

            <a href="{{ route('admin.kelas.index') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('admin.kelas.*') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-chalkboard w-6 text-center"></i>
                <span class="ml-3 font-medium">Kelas</span>
            </a>

            <a href="{{ route('admin.mapel.index') }}"
                class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('admin.mapel.*') ? 'active-sidebar-link' : '' }}">
                <i class="fas fa-book-open w-6 text-center"></i>
                <span class="ml-3 font-medium">Mata Pelajaran</span>
            </a>
        </nav>

        <!-- User info and logout -->
        <div class="p-4 border-t">
            <div class="flex items-center mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-user-cog text-blue-600"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-gray-700">{{ Auth::user()->username }}</p>
                    <p class="text-xs text-gray-500 capitalize">
                        @if (Auth::user()->user_type === 'admin')
                            Administrator
                        @elseif(Auth::user()->user_type === 'guru')
                            Guru
                        @else
                            Siswa
                        @endif
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center justify-center w-full p-3 text-gray-700 hover:bg-red-50 hover:text-red-600 rounded-lg transition-colors">
                    <i class="fas fa-sign-out-alt w-6 text-center"></i>
                    <span class="ml-3 font-medium">Logout</span>
                </button>
            </form>

            <!-- Info Sekolah Footer -->
            @if ($sekolah)
                <div class="mt-4 pt-4 border-t border-gray-200">
                    <div class="text-xs text-gray-500">
                        <p class="font-medium mb-1">{{ Str::limit($sekolah->nama_sekolah, 30) }}</p>
                        <p class="truncate">{{ Str::limit($sekolah->alamat, 40) }}</p>
                        <p class="mt-1"><i class="fas fa-phone mr-1 text-xs"></i> {{ $sekolah->telepon }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Main content area -->
    {{-- <div id="mainContent" class="transition-all">
        @yield('content')
    </div> --}}

    <script>
        // Close sidebar when clicking outside on mobile
        document.querySelector('.overlay').addEventListener('click', function() {
            document.getElementById('mobileToggle').checked = false;
        });

        // Close sidebar when clicking a link on mobile
        document.querySelectorAll('.sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    document.getElementById('mobileToggle').checked = false;
                }
            });
        });

        // Add active class to current page
        document.addEventListener('DOMContentLoaded', function() {
            const currentPath = window.location.pathname;
            document.querySelectorAll('.sidebar a').forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active-sidebar-link');
                }
            });
        });

        // Auto-close sidebar on mobile after click
        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar a').forEach(link => {
                link.addEventListener('click', () => {
                    document.getElementById('mobileToggle').checked = false;
                });
            });
        }

        // Keyboard shortcut for sidebar (Esc to close)
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && window.innerWidth <= 768) {
                document.getElementById('mobileToggle').checked = false;
            }
        });

        // Update sidebar based on screen size
        window.addEventListener('resize', function() {
            if (window.innerWidth > 768) {
                document.getElementById('mobileToggle').checked = true;
            }
        });

        // Initialize based on screen size
        if (window.innerWidth > 768) {
            document.getElementById('mobileToggle').checked = true;
        }
    </script>
</body>

</html>
