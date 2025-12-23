<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    @include('siswa.partials.header')

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Welcome Section -->
            <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Dashboard Siswa</h2>
                        <div class="text-sm text-gray-500">
                            <i class="far fa-calendar-alt mr-1"></i>
                            {{ now()->translatedFormat('l, d F Y') }}
                        </div>
                    </div>

                    @php
                        $user = Auth::user();
                        $siswa = $user->siswa ?? null; // Menggunakan relasi siswa langsung
                    @endphp

                    @if ($siswa)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-blue-800 mb-4 flex items-center">
                                    <i class="fas fa-user-circle mr-2"></i> Informasi Pribadi
                                </h3>
                                <dl class="space-y-3">
                                    <div class="flex items-start">
                                        <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">NISN</dt>
                                        <dd class="text-sm text-gray-900 font-medium">{{ $siswa->nisn }}</dd>
                                    </div>
                                    <div class="flex items-start">
                                        <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">NIS</dt>
                                        <dd class="text-sm text-gray-900 font-medium">{{ $siswa->nis }}</dd>
                                    </div>
                                    <div class="flex items-start">
                                        <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">Nama</dt>
                                        <dd class="text-sm text-gray-900 font-medium">{{ $siswa->nama }}</dd>
                                    </div>
                                    <div class="flex items-start">
                                        <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">Kelas</dt>
                                        <dd class="text-sm text-gray-900 font-medium">
                                            @if ($siswa->kelas)
                                                <span
                                                    class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-users mr-1"></i>
                                                    {{ $siswa->kelas->kelas }}
                                                </span>
                                            @else
                                                <span class="text-yellow-600 font-medium">Belum ada kelas</span>
                                            @endif
                                        </dd>
                                    </div>
                                </dl>
                            </div>
                            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-green-800 mb-4 flex items-center">
                                    <i class="fas fa-address-card mr-2"></i> Kontak & Informasi
                                </h3>
                                <dl class="space-y-3">
                                    <div class="flex items-start">
                                        <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">Email</dt>
                                        <dd class="text-sm text-gray-900">{{ $siswa->email ?? '-' }}</dd>
                                    </div>
                                    <div class="flex items-start">
                                        <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">No. Telepon
                                        </dt>
                                        <dd class="text-sm text-gray-900">{{ $siswa->no_telp ?? '-' }}</dd>
                                    </div>
                                    <div class="flex items-start">
                                        <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">Jenis Kelamin
                                        </dt>
                                        <dd class="text-sm text-gray-900">
                                            @if ($siswa->jnsklmn == 'L')
                                                <span class="inline-flex items-center">
                                                    <i class="fas fa-mars text-blue-500 mr-1"></i> Laki-laki
                                                </span>
                                            @else
                                                <span class="inline-flex items-center">
                                                    <i class="fas fa-venus text-pink-500 mr-1"></i> Perempuan
                                                </span>
                                            @endif
                                        </dd>
                                    </div>
                                    <div class="flex items-start">
                                        <dt class="w-32 text-sm font-medium text-gray-500 flex-shrink-0">Alamat</dt>
                                        <dd class="text-sm text-gray-900">{{ $siswa->alamat ?? '-' }}</dd>
                                    </div>
                                </dl>
                            </div>
                        </div>

                        <!-- Statistik Materi & Tugas -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-chart-line mr-2"></i> Statistik Pembelajaran
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <a href="{{ route('siswa.materi.index') }}"
                                    class="bg-blue-50 border border-blue-200 rounded-lg p-4 hover:bg-blue-100 transition-colors">
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

                                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
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
                                </div>

                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
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
                                </div>

                                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h4 class="font-semibold text-orange-800">Aktifitas</h4>
                                            <p class="text-2xl font-bold text-orange-900 mt-2">
                                                {{ $aktifitas ?? 0 }}
                                            </p>
                                            <p class="text-sm text-gray-600">Komentar & diskusi</p>
                                        </div>
                                        <div class="bg-orange-100 rounded-full p-3">
                                            <i class="fas fa-comments text-orange-600 text-xl"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-triangle text-yellow-500 mr-3 text-xl"></i>
                                <div>
                                    <p class="text-yellow-700 font-medium">Data siswa tidak ditemukan</p>
                                    <p class="text-yellow-600 text-sm">Silakan hubungi administrator untuk melengkapi
                                        data Anda.</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Quick Actions -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-bolt mr-2"></i> Akses Cepat
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Materi Card -->
                            <a href="{{ route('siswa.materi.index') }}"
                                class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-all hover:scale-[1.02] group">
                                <div class="flex items-center mb-4">
                                    <div
                                        class="flex-shrink-0 bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-3">
                                        <i class="fas fa-book text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Materi Pembelajaran</h3>
                                        <p class="text-sm text-gray-500">Akses semua materi pelajaran</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-blue-600 group-hover:text-blue-500 text-sm font-medium">
                                        Lihat Semua Materi
                                    </span>
                                    <i
                                        class="fas fa-arrow-right text-blue-500 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </a>

                            <!-- Tugas Card (AKTIF) -->
                            <a href="{{ route('siswa.tugas.index') }}"
                                class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-all hover:scale-[1.02] group">
                                <div class="flex items-center mb-4">
                                    <div
                                        class="flex-shrink-0 bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-3">
                                        <i class="fas fa-tasks text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Tugas</h3>
                                        <p class="text-sm text-gray-500">Lihat dan kerjakan tugas</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-green-600 group-hover:text-green-500 text-sm font-medium">
                                        Lihat Semua Tugas
                                    </span>
                                    <i
                                        class="fas fa-arrow-right text-green-500 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </a>

                            <!-- Nilai Card -->
                            <a href="{{ route('siswa.nilai.index') }}"
                                class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-all hover:scale-[1.02] group">
                                <div class="flex items-center mb-4">
                                    <div
                                        class="flex-shrink-0 bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-3">
                                        <i class="fas fa-chart-bar text-white text-xl"></i>
                                    </div>
                                    <div class="ml-4">
                                        <h3 class="text-lg font-semibold text-gray-900">Nilai & Rapor</h3>
                                        <p class="text-sm text-gray-500">Lihat nilai dan perkembangan</p>
                                    </div>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-purple-600 group-hover:text-purple-500 text-sm font-medium">
                                        Lihat Nilai Saya
                                    </span>
                                    <i
                                        class="fas fa-arrow-right text-purple-500 group-hover:translate-x-1 transition-transform"></i>
                                </div>
                            </a>
                        </div>
                    </div>

                    <!-- Materi Terbaru -->
                    @if (isset($materiTerbaru) && $materiTerbaru->count() > 0)
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <i class="fas fa-clock mr-2"></i> Materi Terbaru
                                </h3>
                                <a href="{{ route('siswa.materi.index') }}"
                                    class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                                </a>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($materiTerbaru as $materi)
                                    <a href="{{ route('siswa.materi.show', $materi->id) }}"
                                        class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
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
                                                        ][strtolower($extension)] ?? 'fas fa-file text-gray-500';
                                                @endphp
                                                <div class="{{ $iconClass }} text-lg mr-3"></div>
                                            @else
                                                <div class="fas fa-file-alt text-gray-400 text-lg mr-3"></div>
                                            @endif
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900 text-sm mb-1 line-clamp-1">
                                                    {{ $materi->judul_materi }}</h4>
                                                <p class="text-xs text-gray-500 mb-2">
                                                    {{ $materi->mataPelajaran->nama_mapel ?? '-' }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between text-xs text-gray-500">
                                            <span>{{ $materi->created_at->diffForHumans() }}</span>
                                            <span class="flex items-center">
                                                <i class="fas fa-eye mr-1"></i> {{ $materi->views ?? 0 }}
                                            </span>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h4 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                            <i class="fas fa-lightbulb mr-2"></i> Tips Belajar:
                        </h4>
                        <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                            <li>Periksa materi terbaru secara berkala</li>
                            <li>Download materi untuk belajar offline</li>
                            <li>Gunakan fitur komentar untuk bertanya kepada guru</li>
                            <li>Atur jadwal belajar yang konsisten</li>
                            <li>Diskusikan dengan teman sekelas untuk pemahaman yang lebih baik</li>
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
    </script>
</body>

</html>
