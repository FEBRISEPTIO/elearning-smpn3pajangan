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
    <main id="mainContent" class="min-h-screen p-4 md:p-8 transition-all duration-300">
        <!-- Welcome Section -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4 mb-6">
                <div class="w-full flex justify-between items-center md:hidden mb-4">
                    <!-- Spacer for hamburger menu -->
                    <div class="w-8"></div>
                    <!-- Back Button Mobile -->
                    <button onclick="window.history.back()" class="bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>
                </div>

                <div>
                    @php
                    use App\Models\DataSekolah;
                    $sekolah = DataSekolah::first();
                    @endphp
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        Dashboard Admin
                    </h1>
                    <p class="text-gray-600 mt-1">
                        <span
                            class="text-blue-600 font-semibold">{{ $sekolah->nama_sekolah ?? 'E-Learning School' }}</span>
                        -
                        Selamat datang, Administrator!
                    </p>
                </div>
                <div class="bg-blue-50 px-4 py-2 rounded-lg">
                    <p class="text-sm font-medium text-blue-700">{{ now()->translatedFormat('l, d F Y') }}</p>
                </div>
            </div>

            {{-- <!-- Informasi Sekolah -->
            @if ($sekolah)
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 rounded-xl p-4 mb-8">
                    <div class="flex flex-col md:flex-row md:items-center gap-4">
                        @if ($sekolah->logo)
                            <div class="flex-shrink-0">
                                <img src="{{ Storage::url($sekolah->logo) }}" alt="Logo Sekolah"
            class="h-16 w-16 object-contain rounded-lg bg-white p-1 shadow">
        </div>
        @endif
        <div class="flex-grow">
            <div class="flex flex-wrap gap-4">
                <div>
                    <span
                        class="text-xs font-medium text-blue-700 uppercase tracking-wider">Alamat</span>
                    <p class="text-sm text-gray-700">{{ $sekolah->alamat }}</p>
                </div>
                <div>
                    <span
                        class="text-xs font-medium text-blue-700 uppercase tracking-wider">Telepon</span>
                    <p class="text-sm text-gray-700">{{ $sekolah->telepon }}</p>
                </div>
                <div>
                    <span
                        class="text-xs font-medium text-blue-700 uppercase tracking-wider">Email</span>
                    <p class="text-sm text-gray-700">{{ $sekolah->email }}</p>
                </div>
                @if ($sekolah->website)
                <div>
                    <span
                        class="text-xs font-medium text-blue-700 uppercase tracking-wider">Website</span>
                    <p class="text-sm text-blue-600">
                        <a href="{{ $sekolah->website }}" target="_blank" class="hover:underline">
                            {{ $sekolah->website }}
                        </a>
                    </p>
                </div>
                @endif
            </div>
        </div>
        <div class="flex-shrink-0">
            <a href="{{ route('admin.data-sekolah.edit') }}"
                class="inline-flex items-center px-4 py-2 bg-white border border-blue-300 rounded-lg text-sm font-medium text-blue-700 hover:bg-blue-50 transition">
                <i class="fas fa-edit mr-2"></i>
                Edit Data Sekolah
            </a>
        </div>
        </div>
        </div>
        @endif --}}

        @php
        use App\Models\Siswa;
        use App\Models\Guru;
        use App\Models\Kelas;
        use App\Models\MataPelajaran;
        use App\Models\Materi;
        use App\Models\Tugas;
        use App\Models\Admin;
        use App\Models\PengumpulanTugas;
        @endphp

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Siswa -->
            <div
                class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-5 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <i class="fas fa-user-graduate text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm opacity-90">Total Siswa</p>
                        <p class="text-2xl font-bold">{{ Siswa::count() }}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <a href="{{ route('admin.siswa.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center">
                        Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">
                        {{ Siswa::whereYear('created_at', now()->year)->count() }} tahun ini
                    </span>
                </div>
            </div>

            <!-- Total Guru -->
            <div
                class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-5 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <i class="fas fa-chalkboard-teacher text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm opacity-90">Total Guru</p>
                        <p class="text-2xl font-bold">{{ Guru::count() }}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <a href="{{ route('admin.guru.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center">
                        Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">
                        Aktif: {{ Guru::count() }}
                    </span>
                </div>
            </div>

            <!-- Total Kelas -->
            <div
                class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-5 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <i class="fas fa-school text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm opacity-90">Total Kelas</p>
                        <p class="text-2xl font-bold">{{ Kelas::count() }}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <a href="{{ route('admin.kelas.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center">
                        Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>
                    <span class="text-xs bg-white/20 px-2 py-1 rounded-full">
                        {{ Kelas::distinct('kelas')->count('kelas') }} kelas
                    </span>
                </div>
            </div>

            <!-- Total Mapel -->
            <div
                class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-xl p-5 shadow-lg hover:shadow-xl transition-shadow">
                <div class="flex items-center">
                    <div class="bg-white/20 p-3 rounded-full mr-4">
                        <i class="fas fa-book text-xl"></i>
                    </div>
                    <div>
                        <p class="text-sm opacity-90">Total Mata Pelajaran</p>
                        <p class="text-2xl font-bold">{{ MataPelajaran::count() }}</p>
                    </div>
                </div>
                <div class="flex justify-between items-center mt-3">
                    <a href="{{ route('admin.mapel.index') }}"
                        class="text-sm opacity-90 hover:opacity-100 inline-flex items-center">
                        Lihat semua <i class="fas fa-arrow-right ml-1 text-xs"></i>
                    </a>

                </div>
            </div>
        </div>

        <!-- Detail Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Data Sekolah & Sistem -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                        Informasi Sekolah
                    </h3>

                </div>

                <div class="space-y-4">
                    <!-- Informasi Sekolah -->
                    <div class="bg-blue-50 rounded-lg p-4">
                        <h4 class="font-medium text-blue-800 mb-2 text-sm flex items-center">
                            <i class="fas fa-school mr-2"></i>
                            Data Sekolah
                        </h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-600">Nama:</span>
                                <p class="font-medium">{{ $sekolah->nama_sekolah ?? 'Belum diatur' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Telepon:</span>
                                <p class="font-medium">{{ $sekolah->telepon ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Email:</span>
                                <p class="font-medium">{{ $sekolah->email ?? '-' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Website:</span>
                                <p class="font-medium">{{ $sekolah->website ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistik Sistem -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-file-alt text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Materi</p>
                                    <p class="text-lg font-bold">{{ Materi::count() }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-tasks text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Total Tugas</p>
                                    <p class="text-lg font-bold">{{ Tugas::count() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Aktivitas Bulan Ini -->
                    <div class="bg-gray-50 rounded-lg p-3">
                        <h5 class="font-medium text-gray-700 mb-2 text-sm">Aktivitas Bulan Ini</h5>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="text-gray-600">Materi Baru:</span>
                                <span class="font-medium text-green-600">
                                    {{ Materi::whereMonth('created_at', now()->month)->count() }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Tugas Baru:</span>
                                <span class="font-medium text-green-600">
                                    {{ Tugas::whereMonth('created_at', now()->month)->count() }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Pengumpulan:</span>
                                <span class="font-medium text-blue-600">
                                    {{ PengumpulanTugas::whereMonth('created_at', now()->month)->count() }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600">Pengguna Aktif:</span>
                                <span class="font-medium text-purple-600">
                                    {{ Admin::count() + Guru::count() + Siswa::count() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-bolt text-purple-600 mr-2"></i>
                        Aksi Cepat
                    </h3>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <!-- Data Sekolah -->
                    <a href="{{ route('admin.data-sekolah.edit') }}"
                        class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-4 text-center transition-all hover:shadow hover:scale-[1.02] group">
                        <div
                            class="bg-blue-100 group-hover:bg-blue-200 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 transition">
                            <i class="fas fa-school text-blue-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-blue-700">Data Sekolah</p>
                        <p class="text-xs text-gray-600 mt-1">Edit informasi sekolah</p>
                    </a>

                    <!-- Tambah Guru -->
                    <a href="{{ route('admin.guru.create') }}"
                        class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-xl p-4 text-center transition-all hover:shadow hover:scale-[1.02] group">
                        <div
                            class="bg-green-100 group-hover:bg-green-200 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 transition">
                            <i class="fas fa-user-plus text-green-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-green-700">Tambah Guru</p>
                        <p class="text-xs text-gray-600 mt-1">Daftarkan guru baru</p>
                    </a>

                    <!-- Tambah Siswa -->
                    <a href="{{ route('admin.siswa.create') }}"
                        class="bg-gradient-to-r from-indigo-50 to-indigo-100 border border-indigo-200 rounded-xl p-4 text-center transition-all hover:shadow hover:scale-[1.02] group">
                        <div
                            class="bg-indigo-100 group-hover:bg-indigo-200 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 transition">
                            <i class="fas fa-user-plus text-indigo-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-indigo-700">Tambah Siswa</p>
                        <p class="text-xs text-gray-600 mt-1">Daftarkan siswa baru</p>
                    </a>

                    <!-- Import Siswa -->
                    <a href="{{ route('admin.siswa.index') }}"
                        class="bg-gradient-to-r from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-4 text-center transition-all hover:shadow hover:scale-[1.02] group">
                        <div
                            class="bg-amber-100 group-hover:bg-amber-200 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 transition">
                            <i class="fas fa-file-import text-amber-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-amber-700">Import Siswa</p>
                        <p class="text-xs text-gray-600 mt-1">Upload data Excel</p>
                    </a>

                    <!-- Kelas & Mapel -->
                    <a href="{{ route('admin.kelas.index') }}"
                        class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-4 text-center transition-all hover:shadow hover:scale-[1.02] group">
                        <div
                            class="bg-purple-100 group-hover:bg-purple-200 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 transition">
                            <i class="fas fa-plus-circle text-purple-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-purple-700">Tambah Kelas</p>
                        <p class="text-xs text-gray-600 mt-1">Buat kelas baru</p>
                    </a>

                    <a href="{{ route('admin.mapel.create') }}"
                        class="bg-gradient-to-r from-pink-50 to-pink-100 border border-pink-200 rounded-xl p-4 text-center transition-all hover:shadow hover:scale-[1.02] group">
                        <div
                            class="bg-pink-100 group-hover:bg-pink-200 w-12 h-12 rounded-full flex items-center justify-center mx-auto mb-3 transition">
                            <i class="fas fa-book-medical text-pink-600 text-xl"></i>
                        </div>
                        <p class="text-sm font-medium text-pink-700">Tambah Mapel</p>
                        <p class="text-xs text-gray-600 mt-1">Buat mata pelajaran</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-history text-green-600 mr-2"></i>
                    Aktivitas Terbaru
                </h3>
            </div>

            <div class="space-y-6">
                @php
                $recentGuru = Guru::orderBy('created_at', 'desc')->limit(3)->get();
                $recentSiswa = Siswa::orderBy('created_at', 'desc')->limit(3)->get();
                $recentMateri = Materi::with('guru')->orderBy('created_at', 'desc')->limit(3)->get();
                @endphp

                <!-- Recent Guru -->
                <div>
                    <h4 class="font-medium mb-3 text-sm uppercase tracking-wider text-gray-500 flex items-center">
                        <i class="fas fa-chalkboard-teacher mr-2 text-blue-500"></i>
                        Guru Terbaru
                    </h4>
                    <div class="space-y-3">
                        @foreach ($recentGuru as $guru)
                        <div
                            class="flex items-center justify-between p-3 hover:bg-blue-50 rounded-lg transition group">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center mr-3 group-hover:bg-blue-200 transition">
                                    <i class="fas fa-user text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $guru->nama }}</p>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-id-card mr-1"></i>
                                        <span>{{ $guru->nip }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-envelope mr-1"></i>
                                        <span>{{ $guru->email }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-xs text-gray-500 block">{{ $guru->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Siswa -->
                <div>
                    <h4 class="font-medium mb-3 text-sm uppercase tracking-wider text-gray-500 flex items-center">
                        <i class="fas fa-user-graduate mr-2 text-green-500"></i>
                        Siswa Terbaru
                    </h4>
                    <div class="space-y-3">
                        @foreach ($recentSiswa as $siswa)
                        <div
                            class="flex items-center justify-between p-3 hover:bg-green-50 rounded-lg transition group">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3 group-hover:bg-green-200 transition">
                                    <i class="fas fa-user text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $siswa->nama }}</p>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-id-badge mr-1"></i>
                                        <span>{{ $siswa->nis }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-graduation-cap mr-1"></i>
                                        <span>{{ $siswa->kelas->kelas ?? 'Belum ada kelas' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-xs text-gray-500 block">{{ $siswa->created_at->diffForHumans() }}</span>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Materi -->
                <div>
                    <h4 class="font-medium mb-3 text-sm uppercase tracking-wider text-gray-500 flex items-center">
                        <i class="fas fa-file-alt mr-2 text-purple-500"></i>
                        Materi Terbaru
                    </h4>
                    <div class="space-y-3">
                        @foreach ($recentMateri as $materi)
                        <div
                            class="flex items-center justify-between p-3 hover:bg-purple-50 rounded-lg transition group">
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center mr-3 group-hover:bg-purple-200 transition">
                                    <i class="fas fa-book text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $materi->judul }}</p>
                                    <div class="flex items-center text-xs text-gray-500">
                                        <i class="fas fa-user-tie mr-1"></i>
                                        <span>{{ $materi->guru->nama ?? 'Guru tidak ditemukan' }}</span>
                                        <span class="mx-2">•</span>
                                        <i class="fas fa-calendar mr-1"></i>
                                        <span>{{ $materi->created_at->format('d/m/Y') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="text-right">
                                <span
                                    class="text-xs text-gray-500 block">{{ $materi->created_at->diffForHumans() }}</span>
                                {{-- <span
                                            class="text-xs px-2 py-1 rounded-full bg-{{ $materi->status == 'publish' ? 'green' : 'yellow' }}-100 text-{{ $materi->status == 'publish' ? 'green' : 'yellow' }}-800">
                                {{ ucfirst($materi->status) }}
                                </span> --}}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        </div>
    </main>

    <script>
        // Auto refresh setiap 10 menit
        setTimeout(() => window.location.reload(), 600000);

        // Animation for stats cards on hover
        document.querySelectorAll('.hover\\:shadow-xl').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Show current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            const dateElement = document.querySelector('.text-blue-700');
            if (dateElement) {
                dateElement.textContent = now.toLocaleDateString('id-ID', {
                    weekday: 'long',
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric'
                }) + ' • ' + timeString;
            }
        }

        // Update time every second
        setInterval(updateTime, 1000);
        updateTime();
    </script>
</body>

</html>