<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - E-Learning</title>
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
    @include('siswa.partials.header')

    <!-- Main Content -->
    <main id="mainContent" class="min-h-screen p-4 md:p-8 transition-all duration-300">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Welcome Section -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                <div class="w-full flex justify-between items-center md:hidden mb-4">
                    <!-- Spacer for hamburger menu -->
                    <div class="w-8"></div>
                    <!-- Back Button Mobile -->
                    <button onclick="window.history.back()" class="bg-white border border-gray-300 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium hover:bg-gray-50 flex items-center shadow-sm">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </button>
                </div>

                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Dashboard Siswa</h1>
                    <p class="text-gray-600 mt-1">Selamat datang di sistem pembelajaran online</p>
                </div>
                <div class="text-sm text-gray-500 bg-white px-4 py-2 rounded-lg border border-gray-200 shadow-sm">
                    <i class="far fa-calendar-alt mr-1"></i>
                    {{ now()->translatedFormat('l, d F Y') }}
                </div>
            </div>

            @php
            $user = Auth::user();
            $siswa = $user->siswa ?? null;
            @endphp

            @if ($siswa)
            <!-- Informasi Pribadi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Kartu Informasi Pribadi -->
                <div
                    class="bg-gradient-to-br from-blue-50 to-white border border-blue-100 rounded-xl p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                        <i class="fas fa-user-circle mr-2 text-blue-600"></i> Informasi Pribadi
                    </h3>
                    <dl class="space-y-3">
                        <div class="flex items-center">
                            <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">NISN</dt>
                            <dd class="text-sm text-gray-900 font-medium">{{ $siswa->nisn }}</dd>
                        </div>
                        <div class="flex items-center">
                            <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">NIS</dt>
                            <dd class="text-sm text-gray-900 font-medium">{{ $siswa->nis }}</dd>
                        </div>
                        <div class="flex items-center">
                            <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">Nama</dt>
                            <dd class="text-sm text-gray-900 font-medium">{{ $siswa->nama }}</dd>
                        </div>
                        <div class="flex items-center">
                            <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">Kelas</dt>
                            <dd class="text-sm text-gray-900 font-medium">
                                @if ($siswa->kelas)
                                <span
                                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    <i class="fas fa-users mr-1 text-xs"></i>
                                    {{ $siswa->kelas->kelas }}
                                </span>
                                @else
                                <span class="text-yellow-600 font-medium text-sm">
                                    <i class="fas fa-exclamation-circle mr-1"></i>Belum ada kelas
                                </span>
                                @endif
                            </dd>
                        </div>
                    </dl>
                </div>

                <!-- Kartu Kontak & Informasi -->
                <div
                    class="bg-gradient-to-br from-green-50 to-white border border-green-100 rounded-xl p-5 shadow-sm">
                    <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                        <i class="fas fa-address-card mr-2 text-green-600"></i> Kontak & Informasi
                    </h3>
                    <dl class="space-y-3">
                        <div class="flex items-center">
                            <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">Email</dt>
                            <dd class="text-sm text-gray-900 truncate">{{ $siswa->email ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center">
                            <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">No. Telepon</dt>
                            <dd class="text-sm text-gray-900">{{ $siswa->no_telp ?? '-' }}</dd>
                        </div>
                        <div class="flex items-center">
                            <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">Jenis Kelamin</dt>
                            <dd class="text-sm text-gray-900">
                                @if ($siswa->jnsklmn == 'L')
                                <span class="inline-flex items-center">
                                    <i class="fas fa-mars text-blue-500 mr-2"></i> Laki-laki
                                </span>
                                @else
                                <span class="inline-flex items-center">
                                    <i class="fas fa-venus text-pink-500 mr-2"></i> Perempuan
                                </span>
                                @endif
                            </dd>
                        </div>
                        <div class="flex items-start">
                            <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0 pt-1">Alamat</dt>
                            <dd class="text-sm text-gray-900 line-clamp-2">{{ $siswa->alamat ?? '-' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Statistik Pembelajaran -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-line mr-2 text-blue-600"></i> Statistik Pembelajaran
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('siswa.materi.index') }}"
                        class="bg-gradient-to-br from-blue-50 to-white border border-blue-200 rounded-xl p-5 hover:shadow-md transition-all hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-blue-800">Materi</h4>
                                <p class="text-2xl font-bold text-blue-900 mt-2">
                                    {{ $totalMateri ?? 0 }}
                                </p>
                                <p class="text-sm text-gray-600">Materi tersedia</p>
                            </div>
                            <div class="bg-blue-100 rounded-full p-3">
                                <i class="fas fa-book text-blue-600 text-xl"></i>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('siswa.tugas.index') }}"
                        class="bg-gradient-to-br from-green-50 to-white border border-green-200 rounded-xl p-5 hover:shadow-md transition-all hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-green-800">Tugas</h4>
                                <p class="text-2xl font-bold text-green-900 mt-2">
                                    {{ $totalTugas ?? 0 }}
                                </p>
                                <p class="text-sm text-gray-600">Tugas diberikan</p>
                            </div>
                            <div class="bg-green-100 rounded-full p-3">
                                <i class="fas fa-tasks text-green-600 text-xl"></i>
                            </div>
                        </div>
                    </a>

                    <a href="{{ route('siswa.materi.index') }}"
                        class="bg-gradient-to-br from-purple-50 to-white border border-purple-200 rounded-xl p-5 hover:shadow-md transition-all hover:-translate-y-1">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="font-semibold text-purple-800">Materi Baru</h4>
                                <p class="text-2xl font-bold text-purple-900 mt-2">
                                    {{ $materiBaru ?? 0 }}
                                </p>
                                <p class="text-sm text-gray-600">7 hari terakhir</p>
                            </div>
                            <div class="bg-purple-100 rounded-full p-3">
                                <i class="fas fa-bell text-purple-600 text-xl"></i>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            @else
            <!-- Alert jika data siswa tidak ditemukan -->
            <div
                class="bg-gradient-to-br from-yellow-50 to-white border border-yellow-200 rounded-xl p-5 mb-6 shadow-sm">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 text-xl"></i>
                    <div>
                        <p class="text-yellow-700 font-medium">Data siswa tidak ditemukan</p>
                        <p class="text-yellow-600 text-sm">Silakan hubungi administrator untuk melengkapi data
                            Anda.
                        </p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Nilai Terbaru -->
            @if (isset($nilaiTerbaru) && $nilaiTerbaru->count() > 0)
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-graduation-cap mr-2 text-purple-600"></i> Nilai Terbaru
                    </h3>
                    <a href="{{ route('siswa.nilai.index') }}"
                        class="text-sm text-purple-600 hover:text-purple-800 font-medium transition-colors flex items-center">
                        Lihat Semua Nilai <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($nilaiTerbaru as $nilai)
                    <div class="bg-white border border-purple-100 rounded-xl p-4 shadow-sm hover:shadow-md transition-all">
                        <div class="flex justify-between items-start mb-2">
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900 text-sm line-clamp-1">{{ $nilai->mapel->nama_mapel ?? '-' }}</h4>
                                <p class="text-xs text-gray-500 line-clamp-1">{{ $nilai->tugas->judul_tugas ?? 'Penilaian Langsung' }}</p>
                                @if($nilai->keterangan)
                                <p class="text-[10px] text-blue-600 mt-1 italic line-clamp-1" title="{{ $nilai->keterangan }}">
                                    <i class="fas fa-comment-dots mr-1"></i>{{ $nilai->keterangan }}
                                </p>
                                @endif
                            </div>
                            <div class="bg-purple-100 text-purple-700 font-bold px-3 py-1 rounded-lg text-lg">
                                {{ number_format($nilai->nilai, 0) }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-400 mt-3 pt-3 border-t border-gray-50 flex justify-between items-center">
                            <span title="Guru: {{ $nilai->guru->nama ?? '-' }}">
                                <i class="fas fa-user-tie mr-1 text-xs"></i> {{ Str::limit($nilai->guru->nama ?? '-', 15) }}
                            </span>
                            <span>
                                <i class="far fa-calendar-alt mr-1"></i> {{ $nilai->created_at->format('d/m/Y') }}
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Akses Cepat -->
            <div class="mb-8">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-bolt mr-2 text-amber-500"></i> Akses Cepat
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Materi Card -->
                    <a href="{{ route('siswa.materi.index') }}"
                        class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                        <div class="flex items-center mb-4">
                            <div
                                class="flex-shrink-0 bg-gradient-to-r from-blue-500 to-blue-600 rounded-xl p-3 shadow-sm">
                                <i class="fas fa-book text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Materi Pembelajaran</h3>
                                <p class="text-sm text-gray-500">Akses semua materi pelajaran</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <span
                                class="text-blue-600 group-hover:text-blue-700 text-sm font-medium transition-colors">
                                Lihat Semua Materi
                            </span>
                            <i
                                class="fas fa-arrow-right text-blue-500 group-hover:translate-x-2 transition-transform duration-300"></i>
                        </div>
                    </a>

                    <!-- Tugas Card -->
                    <a href="{{ route('siswa.tugas.index') }}"
                        class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                        <div class="flex items-center mb-4">
                            <div
                                class="flex-shrink-0 bg-gradient-to-r from-green-500 to-green-600 rounded-xl p-3 shadow-sm">
                                <i class="fas fa-tasks text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Tugas</h3>
                                <p class="text-sm text-gray-500">Lihat dan kerjakan tugas</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <span
                                class="text-green-600 group-hover:text-green-700 text-sm font-medium transition-colors">
                                Lihat Semua Tugas
                            </span>
                            <i
                                class="fas fa-arrow-right text-green-500 group-hover:translate-x-2 transition-transform duration-300"></i>
                        </div>
                    </a>

                    <!-- Nilai Card -->
                    <a href="{{ route('siswa.nilai.index') }}"
                        class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1 group">
                        <div class="flex items-center mb-4">
                            <div
                                class="flex-shrink-0 bg-gradient-to-r from-purple-500 to-purple-600 rounded-xl p-3 shadow-sm">
                                <i class="fas fa-chart-bar text-white text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-semibold text-gray-900">Nilai</h3>
                                <p class="text-sm text-gray-500">Lihat nilai dan perkembangan</p>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center justify-between">
                            <span
                                class="text-purple-600 group-hover:text-purple-700 text-sm font-medium transition-colors">
                                Lihat Nilai Saya
                            </span>
                            <i
                                class="fas fa-arrow-right text-purple-500 group-hover:translate-x-2 transition-transform duration-300"></i>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Materi Terbaru -->
            @if (isset($materiTerbaru) && $materiTerbaru->count() > 0)
            <div class="mb-8">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-clock mr-2 text-blue-600"></i> Materi Terbaru
                    </h3>
                    <a href="{{ route('siswa.materi.index') }}"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors flex items-center">
                        Lihat Semua <i
                            class="fas fa-arrow-right ml-2 transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($materiTerbaru as $materi)
                    <a href="{{ route('siswa.materi.show', $materi->id) }}"
                        class="bg-gradient-to-br from-white to-gray-50 border border-gray-200 rounded-xl p-4 hover:shadow-md transition-all duration-300 hover:-translate-y-1 group">
                        <div class="flex items-start mb-3">
                            @if ($materi->file)
                            @php
                            $extension = pathinfo($materi->file, PATHINFO_EXTENSION);
                            $iconClass =
                            [
                            'pdf' => 'fas fa-file-pdf text-red-500',
                            'doc' => 'fas fa-file-word text-blue-500',
                            'docx' => 'fas fa-file-word text-blue-500',
                            'ppt' => 'fas fa-file-powerpoint text-orange-500',
                            'pptx' => 'fas fa-file-powerpoint text-orange-500',
                            'jpg' => 'fas fa-file-image text-green-500',
                            'jpeg' => 'fas fa-file-image text-green-500',
                            'png' => 'fas fa-file-image text-green-500',
                            'xlsx' => 'fas fa-file-excel text-green-600',
                            'xls' => 'fas fa-file-excel text-green-600',
                            'txt' => 'fas fa-file-alt text-gray-500',
                            'zip' => 'fas fa-file-archive text-yellow-600',
                            'rar' => 'fas fa-file-archive text-yellow-600',
                            ][strtolower($extension)] ?? 'fas fa-file text-gray-500';
                            @endphp
                            <div class="{{ $iconClass }} text-xl mr-3"></div>
                            @else
                            <div class="fas fa-file-alt text-gray-400 text-xl mr-3"></div>
                            @endif
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-1">
                                    {{ $materi->judul_materi }}
                                </h4>
                                <p class="text-xs text-gray-500 mb-2 line-clamp-1">
                                    {{ $materi->mataPelajaran->nama_mapel ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-between text-xs text-gray-500 pt-3 border-t border-gray-100">
                            <span class="flex items-center">
                                <i class="far fa-clock mr-1"></i>
                                {{ $materi->created_at->diffForHumans() }}
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-eye mr-1"></i> {{ $materi->views ?? 0 }}
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Tips Belajar -->
            <div class="bg-gradient-to-br from-blue-50 to-white border border-blue-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-start">
                    <div
                        class="flex-shrink-0 bg-gradient-to-r from-amber-500 to-yellow-500 rounded-lg p-3 mr-4 shadow-sm">
                        <i class="fas fa-lightbulb text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800 mb-3">Tips Belajar Efektif:</h4>
                        <ul class="text-sm text-blue-700 space-y-2">
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                <span>Periksa materi terbaru secara berkala</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                <span>Download materi untuk belajar offline</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                <span>Gunakan fitur komentar untuk bertanya kepada guru</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                <span>Atur jadwal belajar yang konsisten</span>
                            </li>
                            <li class="flex items-start">
                                <i class="fas fa-check-circle text-green-500 mr-2 mt-0.5"></i>
                                <span>Diskusikan dengan teman sekelas untuk pemahaman yang lebih baik</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Auto-refresh untuk notifikasi (opsional)
        setTimeout(() => {
            location.reload();
        }, 300000); // Refresh setiap 5 menit

        // Tambahkan smooth scrolling untuk pengalaman pengguna yang lebih baik
        document.addEventListener('DOMContentLoaded', function() {
            // Tambahkan event listener untuk semua link dengan hash
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth'
                        });
                    }
                });
            });
        });
    </script>
</body>

</html>