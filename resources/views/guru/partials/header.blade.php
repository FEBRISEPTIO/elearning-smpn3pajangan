<header class="bg-white shadow">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-6">
            <div class="flex items-center">
                <i class="fas fa-graduation-cap text-blue-600 text-2xl mr-3"></i>
                <h1 class="text-2xl font-bold text-gray-900">E-Learning - Guru</h1>
            </div>
            <div class="flex items-center space-x-4">
                <nav class="flex space-x-4">
                    <a href="{{ route('guru.dashboard') }}"
                        class="text-gray-700 hover:text-blue-600 {{ request()->routeIs('guru.dashboard') ? 'text-blue-600 font-semibold' : '' }}">Dashboard</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Materi</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Tugas</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Nilai</a>
                    <a href="#" class="text-gray-700 hover:text-blue-600">Diskusi</a>
                </nav>
                <span class="text-gray-700">{{ Auth::user()->username }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
