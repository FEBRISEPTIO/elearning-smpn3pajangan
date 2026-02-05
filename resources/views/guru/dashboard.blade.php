<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Guru - E-Learning</title>
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
    @include('guru.partials.header')

    <!-- Main Content -->
    <main class="md:ml-64 min-h-screen p-4 md:p-6 transition-all duration-300">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Welcome Section -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Guru</h1>
                    <p class="text-gray-600 mt-1">Selamat datang, {{ Auth::user()->name }}!</p>
                </div>
                <div class="bg-blue-50 px-4 py-2 rounded-lg">
                    <p class="text-sm font-medium text-blue-700">{{ now()->format('l, d F Y') }}</p>
                </div>
            </div>

            @php
                $user = Auth::user();
                $guruData = $guru ?? null;

                if (!$guruData && class_exists('App\Models\Guru')) {
                    $guruData = App\Models\Guru::where('user_id', $user->id)->first();
                }

                $totalMateri = $totalMateri ?? 0;
                $totalTugas = $totalTugas ?? 0;
                $totalSiswa = $totalSiswa ?? 0;
                $tugasBelumDinilai = $tugasBelumDinilai ?? 0;
                $tugasDeadline = $tugasDeadline ?? collect();
                $recentMateri = $recentMateri ?? collect();
                $recentTugas = $recentTugas ?? collect();
            @endphp

            

            @if ($guruData)
                <!-- Information Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Informasi Guru -->
                    <div class="lg:col-span-1">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-user-tie text-blue-600 mr-2"></i>
                                Informasi Guru
                            </h3>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">NIK</span>
                                    <span
                                        class="text-sm font-semibold text-gray-900">{{ $guruData->nik ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Nama</span>
                                    <span
                                        class="text-sm font-semibold text-gray-900">{{ $guruData->nama ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Email</span>
                                    <span class="text-sm text-gray-900">{{ $guruData->email ?? '-' }}</span>
                                </div>
                                <div class="flex justify-between items-center py-3">
                                    <span class="text-sm font-medium text-gray-600">No. Telepon</span>
                                    <span class="text-sm text-gray-900">{{ $guruData->no_telp ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Kelas yang Diampu -->

                </div>
            @else
                <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6 mb-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-yellow-500 mr-4"></i>
                        <div>
                            <h3 class="font-semibold text-yellow-800">Data guru tidak ditemukan</h3>
                            <p class="text-yellow-700 mt-1">Silakan hubungi administrator untuk melengkapi data Anda.
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt text-purple-600 mr-2"></i>
                    Aksi Cepat
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('guru.materi.create') }}"
                        class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 text-center group">
                        <div class="flex flex-col items-center">
                            <div class="bg-blue-500 rounded-lg p-3 mb-3 group-hover:bg-blue-600 transition-colors">
                                <i class="fas fa-plus text-white text-xl"></i>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900">Tambah Materi</h3>
                            <p class="text-xs text-gray-500 mt-1">Upload materi baru</p>
                        </div>
                    </a>

                    <a href="{{ route('guru.tugas.create') }}"
                        class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 text-center group">
                        <div class="flex flex-col items-center">
                            <div class="bg-green-500 rounded-lg p-3 mb-3 group-hover:bg-green-600 transition-colors">
                                <i class="fas fa-tasks text-white text-xl"></i>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900">Buat Tugas</h3>
                            <p class="text-xs text-gray-500 mt-1">Buat tugas baru</p>
                        </div>
                    </a>

                    <a href="{{ route('guru.nilai.index') }}"
                        class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 text-center group">
                        <div class="flex flex-col items-center">
                            <div class="bg-purple-500 rounded-lg p-3 mb-3 group-hover:bg-purple-600 transition-colors">
                                <i class="fas fa-chart-bar text-white text-xl"></i>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900">Kelola Nilai</h3>
                            <p class="text-xs text-gray-500 mt-1">Input dan kelola nilai</p>
                        </div>
                    </a>

                    <a href="{{ route('guru.materi.index') }}"
                        class="bg-white border border-gray-200 rounded-xl p-5 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 text-center group">
                        <div class="flex flex-col items-center">
                            <div class="bg-orange-500 rounded-lg p-3 mb-3 group-hover:bg-orange-600 transition-colors">
                                <i class="fas fa-comments text-white text-xl"></i>
                            </div>
                            <h3 class="text-base font-semibold text-gray-900">Lihat Diskusi</h3>
                            <p class="text-xs text-gray-500 mt-1">Lihat komentar siswa</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
