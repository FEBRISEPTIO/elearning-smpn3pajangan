<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $tugas->judul_tugas }} - E-Learning</title>
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
    <main class="md:ml-64 min-h-screen p-4 md:p-6 transition-all duration-300">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('siswa.tugas.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Tugas
            </a>
        </div>

        <!-- Flash Messages -->
        @if (session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-green-700">{{ session('success') }}</p>
                </div>
            </div>
        </div>
        @endif

        @if (session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-exclamation-circle text-red-500"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <div class="fas fa-tasks text-blue-500 text-2xl mr-3"></div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $tugas->judul_tugas }}</h1>
                                <div class="flex items-center mt-2 text-sm text-gray-600">
                                    <span class="mr-4">
                                        <i class="fas fa-user-graduate mr-1"></i>
                                        {{ $tugas->guru->name ?? 'Guru' }}
                                    </span>
                                    <span>
                                        <i class="far fa-calendar-alt mr-1"></i>
                                        Dibuat: {{ $tugas->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Badge -->
                    @php
                    $isDeadlinePassed = now()->greaterThan($tugas->deadline);
                    $timeRemaining = now()->diff($tugas->deadline);
                    @endphp

                    @if ($isDeadlinePassed)
                    <div class="mt-4 md:mt-0">
                        <span
                            class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full font-medium">
                            <i class="fas fa-clock mr-2"></i> Deadline Telah Lewat
                        </span>
                    </div>
                    @else
                    <div class="mt-4 md:mt-0">
                        <span
                            class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full font-medium">
                            <i class="fas fa-clock mr-2"></i>
                            Sisa: {{ $timeRemaining->d }} hari {{ $timeRemaining->h }} jam
                        </span>
                    </div>
                    @endif
                </div>

                <!-- Badges -->
                <div class="flex flex-wrap gap-2 mt-4">
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-users mr-1"></i>
                        {{ $tugas->kelas->kelas ?? '-' }}
                    </span>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-book mr-1"></i>
                        {{ $tugas->mataPelajaran->nama_mapel ?? '-' }}
                    </span>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                        <i class="fas fa-comment mr-1"></i> {{ $tugas->komentar_count }} komentar
                    </span>
                    @if ($pengumpulan)
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-check-circle mr-1"></i> Sudah Dikumpulkan
                    </span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Description -->
                @if ($tugas->deskripsi)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Tugas</h3>
                    <div class="prose max-w-none text-gray-700 bg-gray-50 border border-gray-200 rounded-xl p-6">
                        {!! nl2br(e($tugas->deskripsi)) !!}
                    </div>
                </div>
                @endif

                <!-- Tugas Content -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Instruksi dan Materi Tugas</h3>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-6">
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($tugas->tugas)) !!}
                        </div>

                        @if ($tugas->file)
                        <!-- Lampiran dari Guru -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">
                                <i class="fas fa-paperclip mr-2"></i> Lampiran dari Guru
                            </h4>
                            <div class="flex items-center justify-between bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                                <div class="flex items-center">
                                    @php
                                    $extension = pathinfo($tugas->file, PATHINFO_EXTENSION);
                                    $iconClass = [
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
                                    <div class="{{ $iconClass }} text-2xl mr-3"></div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ basename($tugas->file) }}</p>
                                        <p class="text-xs text-gray-500">{{ strtoupper($extension) }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('siswa.tugas.download-file', $tugas) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition shadow-sm">
                                    <i class="fas fa-download mr-2"></i> Download
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Deadline Info -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Deadline</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div
                            class="bg-white border {{ $isDeadlinePassed ? 'border-red-200 bg-red-60' : 'border-blue-200 bg-blue-50' }} rounded-xl p-6">
                            <div class="flex items-center mb-2">
                                <div
                                    class="w-8 h-8 {{ $isDeadlinePassed ? 'bg-red-100' : 'bg-blue-100' }} rounded-full flex items-center justify-center mr-3">
                                    <i
                                        class="fas fa-calendar-alt {{ $isDeadlinePassed ? 'text-red-600' : 'text-blue-600' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tanggal Deadline</p>
                                    <p class="font-medium text-gray-900">{{ $tugas->deadline->format('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div
                            class="bg-white border {{ $isDeadlinePassed ? 'border-red-200 bg-red-60' : 'border-green-200 bg-green-60' }} rounded-xl p-6">
                            <div class="flex items-center mb-2">
                                <div
                                    class="w-8 h-8 {{ $isDeadlinePassed ? 'bg-red-100' : 'bg-green-100' }} rounded-full flex items-center justify-center mr-3">
                                    <i
                                        class="fas fa-clock {{ $isDeadlinePassed ? 'text-red-600' : 'text-green-600' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Waktu Deadline</p>
                                    <p class="font-medium {{ $isDeadlinePassed ? 'text-red-600' : 'text-green-600' }}">
                                        {{ $tugas->deadline->format('H:i') }}
                                        @if ($isDeadlinePassed)
                                        <span class="block text-sm">(Sudah lewat)</span>
                                        @else
                                        <span class="block text-sm">
                                            ({{ $timeRemaining->d }} hari {{ $timeRemaining->h }} jam
                                            {{ $timeRemaining->i }} menit lagi)
                                        </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Pengumpulan Tugas -->
                @if (!$isDeadlinePassed)
                <div class="mb-8 bg-green-60 border border-green-210 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-upload mr-2"></i> Kumpulkan Tugas
                    </h3>

                    @if (!$pengumpulan)
                    <form action="{{ route('siswa.tugas.submit', $tugas->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                File Tugas <span class="text-red-500">*</span>
                            </label>
                            <input type="file" name="file_tugas"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                accept=".pdf,.doc,.docx,.ppt,.pptx,.jpg,.jpeg,.png,.zip,.rar" required>
                            <p class="mt-2 text-xs text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Format: PDF, DOC, PPT, Gambar, atau ZIP (Maks. 10MB)
                            </p>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Keterangan (Opsional)
                            </label>
                            <textarea name="keterangan" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Tambahkan catatan atau penjelasan tentang tugas yang dikumpulkan..."></textarea>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center transition">
                                <i class="fas fa-paper-plane mr-2"></i> Kumpulkan Tugas
                            </button>
                        </div>
                    </form>
                    @else
                    <div class="bg-white border border-green-300 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="flex items-center mb-2">
                                    <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                                    <div>
                                        <h4 class="font-medium text-gray-900">Tugas Sudah Dikumpulkan</h4>
                                        <p class="text-sm text-gray-500">
                                            Dikumpulkan pada:
                                            {{ $pengumpulan->submitted_at->format('d M Y, H:i') }}
                                        </p>
                                    </div>
                                </div>

                                @if ($pengumpulan->keterangan)
                                <p class="text-sm text-gray-600 mt-2">
                                    <span class="font-medium">Keterangan:</span>
                                    {{ $pengumpulan->keterangan }}
                                </p>
                                @endif
                            </div>

                            @if ($pengumpulan->file)
                            <a href="{{ Storage::url($pengumpulan->file) }}" target="_blank"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                                <i class="fas fa-download mr-2"></i> Lihat File
                            </a>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                @else
                <div class="mb-8 bg-red-60 border border-red-200 rounded-xl p-6">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
                        <div>
                            <h4 class="font-medium text-gray-900">Waktu Pengumpulan Telah Berakhir</h4>
                            <p class="text-sm text-gray-600">
                                Deadline tugas telah lewat pada {{ $tugas->deadline->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>

                    @if ($pengumpulan)
                    <div class="mt-4 bg-white border border-green-300 rounded-xl p-4">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                            <div>
                                <h4 class="font-medium text-gray-900">Tugas Telah Dikumpulkan</h4>
                                <p class="text-sm text-gray-500">
                                    Anda telah mengumpulkan tugas pada
                                    {{ $pengumpulan->submitted_at->format('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Komentar Section -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        <i class="fas fa-comments mr-2"></i> Diskusi Tugas
                        <span class="text-sm font-normal text-gray-500">({{ $tugas->komentar_count }}
                            komentar)</span>
                    </h3>

                    <!-- Form Komentar -->
                    <div class="mb-8">
                        <form action="{{ route('siswa.tugas.komentar.store', $tugas->id) }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Komentar</label>
                                <textarea name="isi_komentar" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Tanya tentang tugas, klarifikasi, atau diskusikan dengan teman..." required></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center transition">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Komentar
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Daftar Komentar -->
                    @if ($tugas->komentar_count > 0)
                    <div class="space-y-6">
                        @foreach ($komentars as $komentar)
                        @php
                        $profile = $komentar->getUserProfile();
                        @endphp
                        <div
                            class="border border-gray-200 rounded-xl p-4 {{ $profile['type'] === 'guru' ? 'bg-blue-50' : 'bg-gray-50' }}">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    <div
                                        class="w-10 h-10 {{ $profile['type'] === 'guru' ? 'bg-blue-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                        <i
                                            class="{{ $profile['avatar'] }} {{ $profile['type'] === 'guru' ? 'text-blue-600' : 'text-gray-600' }}"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <div class="flex items-center">
                                                <h4 class="font-semibold text-gray-900 mr-2">
                                                    {{ $profile['name'] }}
                                                </h4>
                                                @if ($profile['type'] === 'guru')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-chalkboard-teacher mr-1"></i> Guru
                                                </span>
                                                @elseif($profile['type'] === 'siswa')
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                                    <i class="fas fa-user-graduate mr-1"></i> Siswa
                                                </span>
                                                @endif
                                            </div>
                                            <p class="text-sm text-gray-500">
                                                {{ $komentar->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        @if (
                                        ($profile['type'] === 'siswa' && Auth::user()->siswa && Auth::user()->siswa->id == $komentar->user_id) ||
                                        ($profile['type'] === 'guru' && Auth::id() == $komentar->user_id))
                                        <form
                                            action="{{ route('siswa.tugas.komentar.destroy', $komentar->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-500 hover:text-red-700 p-1 hover:bg-red-60 rounded">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                    <p class="text-gray-700">{{ $komentar->isi_komentar }}</p>

                                    @if ($profile['type'] === 'siswa' && isset($profile['kelas']))
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $profile['kelas'] }}
                                    </p>
                                    @elseif($profile['type'] === 'guru' && isset($profile['mapel']))
                                    <p class="text-xs text-gray-500 mt-1">
                                        {{ $profile['mapel'] }}
                                    </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination Komentar -->
                    @if ($komentars->hasPages())
                    <div class="mt-6">
                        {{ $komentars->links() }}
                    </div>
                    @endif
                    @else
                    <div class="text-center py-8 border border-gray-200 rounded-xl">
                        <div
                            class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-comment-slash text-gray-400 text-2xl"></i>
                        </div>
                        <p class="text-gray-500">Belum ada komentar. Mulailah diskusi tentang tugas ini!</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer Stats -->
            <div class="bg-gray-50 px-6 py-4 border-t">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between text-sm text-gray-500">
                    <div class="flex flex-wrap items-center gap-6 mb-2 md:mb-0">
                        <span>
                            <i class="fas fa-clock mr-1"></i>
                            Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}
                        </span>
                        <span>
                            <i class="fas fa-comment mr-1"></i> {{ $tugas->komentar_count }} komentar
                        </span>
                        <span>
                            <i class="fas fa-user-graduate mr-1"></i>
                            {{ $tugas->guru->name ?? 'Guru' }}
                        </span>
                    </div>
                    <span>
                        Terakhir update: {{ $tugas->updated_at->diffForHumans() }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-amber-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips Mengerjakan Tugas:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Baca instruksi dengan seksama sebelum mengerjakan</li>
                        <li>• Perhatikan deadline dan kumpulkan sebelum waktu habis</li>
                        <li>• Gunakan fitur komentar untuk bertanya jika ada yang kurang jelas</li>
                        <li>• Periksa kembali file yang akan dikumpulkan</li>
                        <li>• Diskusikan dengan teman jika diperbolehkan</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Auto-hide flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 3000);

        // Deadline countdown timer
        @if(!$isDeadlinePassed)

        function updateCountdown() {
            const deadline = new Date('{{ $tugas->deadline->format('
                Y - m - d H: i: s ') }}').getTime();
            const now = new Date().getTime();
            const timeRemaining = deadline - now;

            if (timeRemaining <= 0) {
                document.getElementById('countdown-timer').innerHTML = 'Deadline telah lewat!';
                document.getElementById('countdown-badge').innerHTML =
                    '<i class="fas fa-clock mr-2"></i> Deadline Telah Lewat';
                document.getElementById('countdown-badge').className =
                    'inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full font-medium';
                return;
            }

            const days = Math.floor(timeRemaining / (1000 * 60 * 60 * 24));
            const hours = Math.floor((timeRemaining % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((timeRemaining % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((timeRemaining % (1000 * 60)) / 1000);

            const countdownText = `${days} hari ${hours} jam ${minutes} menit ${seconds} detik`;
            document.getElementById('countdown-timer').innerHTML = countdownText;
        }

        // Update countdown every second
        setInterval(updateCountdown, 1000);
        updateCountdown(); // Initial call
        @endif
    </script>
</body>

</html>