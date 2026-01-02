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
                @auth
                    E-Learning -
                    @if (Auth::user()->user_type === 'admin')
                        Admin
                    @elseif(Auth::user()->user_type === 'guru')
                        Guru
                    @else
                        Siswa
                    @endif
                @endauth
            </h1>
        </div>
    </div>

    <!-- Navigation links -->
    <nav class="flex-1 p-4 space-y-2">
        <a href="{{ route('guru.dashboard') }}"
            class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('guru.dashboard') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-tachometer-alt w-6 text-center"></i>
            <span class="ml-3 font-medium">Dashboard</span>
        </a>

        <a href="{{ route('guru.materi.index') }}"
            class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('guru.materi.*') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-book w-6 text-center"></i>
            <span class="ml-3 font-medium">Materi</span>
        </a>

        <a href="{{ route('guru.tugas.index') }}"
            class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('guru.tugas.*') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-tasks w-6 text-center"></i>
            <span class="ml-3 font-medium">Tugas</span>
        </a>

        <a href="{{ route('guru.nilai.index') }}"
            class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('guru.nilai.*') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-chart-bar w-6 text-center"></i>
            <span class="ml-3 font-medium">Nilai</span>
        </a>

      {{--   <a href="{{ route('guru.diskusi.index') }}"
            class="flex items-center p-3 text-gray-700 hover:bg-blue-50 hover:text-blue-600 rounded-lg {{ request()->routeIs('guru.diskusi.*') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-comments w-6 text-center"></i>
            <span class="ml-3 font-medium">Diskusi</span>
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
                <p class="text-xs text-gray-500">Guru</p>
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
