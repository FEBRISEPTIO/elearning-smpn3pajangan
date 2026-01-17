<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Sidebar responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar-open {
                transform: translateX(0);
            }

            .overlay {
                display: none;
            }

            .overlay-active {
                display: block;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 40;
            }
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen">
    @include('admin.partials.header')

    <!-- Main Content -->
    <main class="md:ml-64 min-h-screen p-4 md:p-6 transition-all duration-300">
        <!-- Welcome Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Admin</h1>
                    <p class="text-gray-600 mt-1">Selamat datang, {{ Auth::user()->name }}!</p>
                </div>
                <div class="bg-blue-50 px-4 py-2 rounded-lg">
                    <p class="text-sm font-medium text-blue-700">{{ now()->format('l, d F Y') }}</p>
                </div>
            </div>

            @php
                use App\Models\Siswa;
                use App\Models\Guru;
                use App\Models\Kelas;
                use App\Models\MataPelajaran;
                use App\Models\Materi;
                use App\Models\Tugas;
            @endphp

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Siswa -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Total Siswa</p>
                            <p class="text-2xl font-bold">{{ Siswa::count() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.siswa.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center mt-3">
                        Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>

                <!-- Total Guru -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-chalkboard-teacher text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Total Guru</p>
                            <p class="text-2xl font-bold">{{ Guru::count() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.guru.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center mt-3">
                        Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>

                <!-- Total Kelas -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-school text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Total Kelas</p>
                            <p class="text-2xl font-bold">{{ Kelas::count() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.kelas.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center mt-3">
                        Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>

                <!-- Total Mapel -->
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-book text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Total Mata Pelajaran</p>
                            <p class="text-2xl font-bold">{{ MataPelajaran::count() }}</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.mapel.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center mt-3">
                        Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Detail Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <!-- Stats Grid -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-chart-bar text-blue-600 mr-2"></i>
                        Statistik Detail
                    </h3>
                    <div class="space-y-3">
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Total Materi</span>
                            <span class="text-sm font-semibold text-gray-900">{{ Materi::count() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Total Tugas</span>
                            <span class="text-sm font-semibold text-gray-900">{{ Tugas::count() }}</span>
                        </div>
                        <div class="flex justify-between items-center py-3 border-b border-gray-100">
                            <span class="text-sm font-medium text-gray-600">Materi Bulan Ini</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ Materi::whereMonth('created_at', now()->month)->count() }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center py-3">
                            <span class="text-sm font-medium text-gray-600">Tugas Bulan Ini</span>
                            <span class="text-sm font-semibold text-gray-900">
                                {{ Tugas::whereMonth('created_at', now()->month)->count() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-bolt text-purple-600 mr-2"></i>
                        Aksi Cepat
                    </h3>
                    <div class="grid grid-cols-2 gap-4">
                        <a href="{{ route('admin.guru.create') }}"
                            class="bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-xl p-4 text-center transition-all hover:shadow">
                            <i class="fas fa-user-plus text-blue-600 text-xl mb-2"></i>
                            <p class="text-sm font-medium text-blue-700">Tambah Guru</p>
                        </a>
                        <a href="{{ route('admin.siswa.create') }}"
                            class="bg-green-50 hover:bg-green-100 border border-green-200 rounded-xl p-4 text-center transition-all hover:shadow">
                            <i class="fas fa-user-plus text-green-600 text-xl mb-2"></i>
                            <p class="text-sm font-medium text-green-700">Tambah Siswa</p>
                        </a>
                        <a href="{{ route('admin.kelas.create') }}"
                            class="bg-purple-50 hover:bg-purple-100 border border-purple-200 rounded-xl p-4 text-center transition-all hover:shadow">
                            <i class="fas fa-plus-circle text-purple-600 text-xl mb-2"></i>
                            <p class="text-sm font-medium text-purple-700">Tambah Kelas</p>
                        </a>
                        <a href="{{ route('admin.mapel.create') }}"
                            class="bg-amber-50 hover:bg-amber-100 border border-amber-200 rounded-xl p-4 text-center transition-all hover:shadow">
                            <i class="fas fa-book-medical text-amber-600 text-xl mb-2"></i>
                            <p class="text-sm font-medium text-amber-700">Tambah Mapel</p>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-history text-green-600 mr-2"></i>
                    Aktivitas Terbaru
                </h3>
                <div class="space-y-6">
                    @php
                        $recentGuru = Guru::orderBy('created_at', 'desc')->limit(3)->get();
                        $recentSiswa = Siswa::orderBy('created_at', 'desc')->limit(3)->get();
                    @endphp

                    <!-- Recent Guru -->
                    <div>
                        <h4 class="font-medium mb-3 text-sm uppercase tracking-wider text-gray-500">Guru
                            Terbaru</h4>
                        <div class="space-y-3">
                            @foreach ($recentGuru as $guru)
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $guru->nama }}</p>
                                            <p class="text-xs text-gray-500">{{ $guru->nik }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs text-gray-500">{{ $guru->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Recent Siswa -->
                    <div>
                        <h4 class="font-medium mb-3 text-sm uppercase tracking-wider text-gray-500">Siswa
                            Terbaru</h4>
                        <div class="space-y-3">
                            @foreach ($recentSiswa as $siswa)
                                <div
                                    class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-lg transition">
                                    <div class="flex items-center">
                                        <div
                                            class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-green-600"></i>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $siswa->nama }}</p>
                                            <p class="text-xs text-gray-500">{{ $siswa->nis }}</p>
                                        </div>
                                    </div>
                                    <span
                                        class="text-xs text-gray-500">{{ $siswa->created_at->diffForHumans() }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Auto refresh every 5 minutes
        setTimeout(() => window.location.reload(), 300000);
    </script>
</body>

</html>
