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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Materi -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-book text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Total Materi</p>
                            <p class="text-2xl font-bold">{{ $totalMateri }}</p>
                        </div>
                    </div>
                    <a href="{{ route('guru.materi.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center mt-3">
                        Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>

                <!-- Total Tugas -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-tasks text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Total Tugas</p>
                            <p class="text-2xl font-bold">{{ $totalTugas }}</p>
                        </div>
                    </div>
                    <a href="{{ route('guru.tugas.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center mt-3">
                        Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>

                <!-- Total Siswa -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-users text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Total Siswa</p>
                            <p class="text-2xl font-bold">{{ $totalSiswa }}</p>
                        </div>
                    </div>
                    <a href="{{ route('guru.nilai.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center mt-3">
                        Lihat nilai <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>

                <!-- Perlu Dinilai -->
                <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Perlu Dinilai</p>
                            <p class="text-2xl font-bold">{{ $tugasBelumDinilai }}</p>
                        </div>
                    </div>
                    <a href="{{ route('guru.tugas.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center mt-3">
                        Cek tugas <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                </div>
            </div>

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
                    <div class="lg:col-span-2">
                        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-chalkboard-teacher text-green-600 mr-2"></i>
                                Kelas yang Diampu
                            </h3>
                            @php
                                $mapelKelas = $guruData->mapelKelas ?? collect();
                            @endphp

                            @if ($mapelKelas->count() > 0)
                                <div class="space-y-4">
                                    @foreach ($mapelKelas->groupBy('kelas_id') as $kelasGroup)
                                        @php
                                            $first = $kelasGroup->first();
                                            $kelas = $first->kelas ?? null;
                                        @endphp

                                        @if ($kelas)
                                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                                <div class="flex justify-between items-start">
                                                    <div>
                                                        <h4 class="font-semibold text-gray-800">
                                                            {{ $kelas->nama_kelas ?? 'Kelas' }}
                                                        </h4>
                                                        <p class="text-sm text-gray-600 mt-1">
                                                            Tahun Ajaran: {{ $kelas->tahun_ajaran ?? '-' }}
                                                        </p>
                                                    </div>
                                                    <span
                                                        class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                                        {{ $kelasGroup->count() }} Mapel
                                                    </span>
                                                </div>
                                                <div class="mt-3">
                                                    <p class="text-sm font-medium text-gray-700 mb-2">Mata Pelajaran:
                                                    </p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach ($kelasGroup as $mapelKelasItem)
                                                            <span
                                                                class="bg-green-100 text-green-800 text-xs font-medium px-3 py-1 rounded-full">
                                                                {{ $mapelKelasItem->mataPelajaran->nama_mapel ?? 'Mapel' }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <i class="fas fa-exclamation-circle text-yellow-500 mr-3"></i>
                                        <div>
                                            <p class="text-yellow-700">Belum ada kelas yang diampu.</p>
                                            <p class="text-sm text-yellow-600 mt-1">Silakan hubungi administrator.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
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

            <!-- Recent Activity & Deadlines -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Tugas Mendekati Deadline -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-clock text-red-600 mr-2"></i>
                        Tugas Mendekati Deadline
                    </h3>
                    @php
                        $tugasDeadline = App\Models\Tugas::where('guru_nik', $user->id)
                            ->where('deadline', '>=', now())
                            ->where('deadline', '<=', now()->addDays(7))
                            ->orderBy('deadline', 'asc')
                            ->limit(5)
                            ->get();
                    @endphp

                    @if ($tugasDeadline->count() > 0)
                        <div class="space-y-3">
                            @foreach ($tugasDeadline as $tugas)
                                <div
                                    class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $tugas->judul }}</p>
                                        <p class="text-sm text-gray-600">{{ $tugas->kelas->nama_kelas ?? 'N/A' }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="font-semibold {{ $tugas->deadline->diffInDays(now()) <= 1 ? 'text-red-600' : 'text-yellow-600' }}">
                                            {{ $tugas->deadline->diffForHumans() }}
                                        </p>
                                        <p class="text-xs text-gray-500">{{ $tugas->deadline->format('d M H:i') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 text-center">
                            <a href="{{ route('guru.tugas.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                Lihat semua tugas <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-check-circle text-green-400 text-3xl mb-3"></i>
                            <p class="text-gray-600">Tidak ada tugas yang mendekati deadline</p>
                        </div>
                    @endif
                </div>

                <!-- Aktivitas Terbaru -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-history text-blue-600 mr-2"></i>
                        Aktivitas Terbaru
                    </h3>
                    @php
                        $recentActivities = collect();

                        $recentMateri = App\Models\Materi::where('guru_nik', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get()
                            ->map(function ($item) {
                                return [
                                    'type' => 'materi',
                                    'title' => $item->judul,
                                    'time' => $item->created_at,
                                    'icon' => 'fas fa-book',
                                    'color' => 'text-blue-600',
                                    'link' => route('guru.materi.show', $item->id),
                                ];
                            });

                        $recentTugas = App\Models\Tugas::where('guru_nik', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->limit(3)
                            ->get()
                            ->map(function ($item) {
                                return [
                                    'type' => 'tugas',
                                    'title' => $item->judul,
                                    'time' => $item->created_at,
                                    'icon' => 'fas fa-tasks',
                                    'color' => 'text-green-600',
                                    'link' => route('guru.tugas.show', $item->id),
                                ];
                            });

                        $recentActivities = $recentMateri->merge($recentTugas)->sortByDesc('time')->take(5);
                    @endphp

                    @if ($recentActivities->count() > 0)
                        <div class="space-y-4">
                            @foreach ($recentActivities as $activity)
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 mt-1">
                                        <i class="{{ $activity['icon'] }} {{ $activity['color'] }} text-lg"></i>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <a href="{{ $activity['link'] }}"
                                            class="text-sm font-medium text-gray-900 hover:text-blue-600">
                                            {{ $activity['title'] }}
                                        </a>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $activity['time']->diffForHumans() }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <i class="fas fa-info-circle text-gray-400 text-3xl mb-3"></i>
                            <p class="text-gray-600">Belum ada aktivitas terbaru</p>
                            <p class="text-sm text-gray-500 mt-1">Mulai dengan membuat materi atau tugas</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>
</body>

</html>
