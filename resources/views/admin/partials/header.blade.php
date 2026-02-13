<style>
    /* Google Fonts Import - if not already in layout */
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

    body {
        font-family: 'Inter', sans-serif;
    }

    /* Active link styling */
    .active-sidebar-link {
        background: linear-gradient(to right, #2563eb, #3b82f6);
        color: white !important;
        box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.2), 0 2px 4px -1px rgba(37, 99, 235, 0.1);
    }

    .active-sidebar-link:hover {
        background: linear-gradient(to right, #1d4ed8, #2563eb);
    }

    .active-sidebar-link i {
        color: white !important;
    }

    /* Sidebar Base */
    .sidebar {
        font-family: 'Inter', sans-serif;
        background-color: white;
        display: flex;
        flex-direction: column;
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
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
            border-right: 1px solid #f3f4f6;
            box-shadow: 4px 0 24px rgba(0, 0, 0, 0.1);
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(17, 24, 39, 0.6);
            backdrop-filter: blur(2px);
            z-index: 40;
            transition: opacity 0.3s ease;
        }

        .mobile-menu-btn {
            display: block;
            position: fixed;
            top: 1.25rem;
            left: 1rem;
            z-index: 30;
            background-color: white;
            color: #374151;
            border: 1px solid #e5e7eb;
            padding: 0.5rem;
            border-radius: 0.5rem;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }

        .mobile-menu-btn:hover {
            background-color: #f9fafb;
        }

        /* Adjust main content padding when on mobile */
        main {
            padding-top: 5rem;
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
            border-right: 1px solid #e5e7eb;
            box-shadow: none;
            /* Cleaner look on desktop */
        }

        #mainContent,
        main {
            margin-left: 280px !important;
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
    <div class="p-6 border-b flex justify-between items-center">
        <div class="flex items-center">
            @php
            use App\Models\DataSekolah;
            $sekolah = DataSekolah::first();
            @endphp

            @if ($sekolah && $sekolah->logo)
            <img src="{{ Storage::url($sekolah->logo) }}" alt="Logo Sekolah"
                class="w-10 h-10 object-contain mr-3 rounded bg-white p-1 border">
            @else
            <i class="fas fa-graduation-cap text-blue-600 text-3xl mr-3"></i>
            @endif
            <div>
                <h1 class="text-lg font-bold text-gray-900 leading-tight">
                    @if ($sekolah)
                    {{ Str::limit($sekolah->nama_sekolah, 20) }}
                    @else
                    E-Learning
                    @endif
                </h1>
                <p class="text-xs text-blue-600 font-semibold mt-0.5">
                    @auth
                    @if (Auth::user()->user_type === 'admin')
                    Administrator
                    @elseif(Auth::user()->user_type === 'guru')
                    Panel Guru
                    @else
                    Panel Siswa
                    @endif
                    @endauth
                </p>
            </div>
        </div>
        <!-- Mobile Close Button -->
        <button type="button" onclick="document.getElementById('mobileToggle').checked = false" class="md:hidden text-gray-500 hover:text-red-500 focus:outline-none p-2 rounded-lg hover:bg-red-50 transition-colors">
            <i class="fas fa-times text-xl"></i>
        </button>
    </div>

    <!-- Navigation links -->
    <nav class="flex-1 px-4 py-4 space-y-1.5 overflow-y-auto">
        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Menu Utama</p>

        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-xl transition-all duration-200 group hover:bg-gray-50 hover:text-blue-600 {{ request()->routeIs('admin.dashboard') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-tachometer-alt w-5 h-5 text-center mr-3 transition-colors {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
            <span>Dashboard</span>
        </a>

        <!-- Menu Data Sekolah -->
        <a href="{{ route('admin.data-sekolah.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-xl transition-all duration-200 group hover:bg-gray-50 hover:text-blue-600 {{ request()->routeIs('admin.data-sekolah.*') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-school w-5 h-5 text-center mr-3 transition-colors {{ request()->routeIs('admin.data-sekolah.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
            <span>Data Sekolah</span>
        </a>

        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Manajemen User</p>

        <a href="{{ route('admin.siswa.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-xl transition-all duration-200 group hover:bg-gray-50 hover:text-blue-600 {{ request()->routeIs('admin.siswa.*') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-users w-5 h-5 text-center mr-3 transition-colors {{ request()->routeIs('admin.siswa.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
            <span>Data Siswa</span>
        </a>

        <a href="{{ route('admin.guru.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-xl transition-all duration-200 group hover:bg-gray-50 hover:text-blue-600 {{ request()->routeIs('admin.guru.*') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-chalkboard-teacher w-5 h-5 text-center mr-3 transition-colors {{ request()->routeIs('admin.guru.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
            <span>Data Guru</span>
        </a>

        <p class="px-4 text-xs font-semibold text-gray-400 uppercase tracking-wider mt-6 mb-2">Akademik</p>

        <a href="{{ route('admin.kelas.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-xl transition-all duration-200 group hover:bg-gray-50 hover:text-blue-600 {{ request()->routeIs('admin.kelas.*') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-chalkboard w-5 h-5 text-center mr-3 transition-colors {{ request()->routeIs('admin.kelas.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
            <span>Data Kelas</span>
        </a>

        <a href="{{ route('admin.mapel.index') }}"
            class="flex items-center px-4 py-3 text-sm font-medium text-gray-600 rounded-xl transition-all duration-200 group hover:bg-gray-50 hover:text-blue-600 {{ request()->routeIs('admin.mapel.*') ? 'active-sidebar-link' : '' }}">
            <i class="fas fa-book-open w-5 h-5 text-center mr-3 transition-colors {{ request()->routeIs('admin.mapel.*') ? 'text-white' : 'text-gray-400 group-hover:text-blue-500' }}"></i>
            <span>Mata Pelajaran</span>
        </a>
    </nav>

    <!-- User info and logout -->
    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center gap-3 mb-4 px-2">
            <div class="w-10 h-10 bg-gradient-to-tr from-blue-100 to-blue-50 rounded-xl flex items-center justify-center border border-blue-100 text-blue-600 shadow-sm">
                <i class="fas fa-user-shield"></i>
            </div>
            <div class="flex-1 overflow-hidden">
                <p class="text-sm font-bold text-gray-900 truncate tracking-tight">{{ Auth::user()->username }}</p>
                <p class="text-xs text-gray-500 font-medium capitalize">Administrator</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center justify-center w-full p-2.5 text-red-600 hover:bg-red-50 rounded-xl transition-all duration-200 border border-transparent hover:border-red-100 group">
                <i class="fas fa-sign-out-alt w-5 text-center group-hover:scale-110 transition-transform"></i>
                <span class="ml-2 font-medium text-sm">Keluar</span>
            </button>
        </form>
    </div>
</div>

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
        const links = document.querySelectorAll('.sidebar a');

        links.forEach(link => {
            const href = link.getAttribute('href');
            // Exact match or sub-path match logic can be refined here
            if (href === window.location.href || (href !== '#' && window.location.href.startsWith(href))) {
                link.classList.add('active-sidebar-link');
            }
        });
    });

    // Keyboard shortcut for sidebar (Esc to close)
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && window.innerWidth <= 768) {
            document.getElementById('mobileToggle').checked = false;
        }
    });
</script>