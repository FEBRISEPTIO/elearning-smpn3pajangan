<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $materi->judul_materi }} - Materi Pembelajaran</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    @include('siswa.partials.header')

    <main class="max-w-6xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('siswa.materi.index') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Materi
                </a>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Main Content -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b">
                    <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                        <div class="flex-1">
                            <div class="flex items-center mb-2">
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
                                    <div class="{{ $iconClass }} text-2xl mr-3"></div>
                                @elseif(str_contains($materi->materi, '[Video]'))
                                    <div class="fas fa-video text-purple-500 text-2xl mr-3"></div>
                                @else
                                    <div class="fas fa-file-alt text-gray-500 text-2xl mr-3"></div>
                                @endif

                                <div>
                                    <h1 class="text-2xl font-bold text-gray-900">{{ $materi->judul_materi }}</h1>
                                    <div class="flex items-center mt-2 text-sm text-gray-600">
                                        <span class="mr-4">
                                            <i class="fas fa-user-graduate mr-1"></i>
                                            {{ $materi->guru->name ?? 'Guru' }}
                                        </span>
                                        <span>
                                            <i class="far fa-calendar-alt mr-1"></i>
                                            {{ $materi->created_at->format('d M Y, H:i') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-4 md:mt-0">
                            @if ($materi->file)
                                <a href="{{ route('siswa.materi.download', $materi->id) }}"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                    <i class="fas fa-download mr-2"></i> Download
                                </a>
                            @endif
                        </div>
                    </div>

                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2 mt-4">
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <i class="fas fa-users mr-1"></i>
                            {{ $materi->kelas->kelas ?? '-' }}
                        </span>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                            <i class="fas fa-book mr-1"></i>
                            {{ $materi->mataPelajaran->nama_mapel ?? '-' }}
                        </span>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                            <i class="fas fa-eye mr-1"></i> {{ $materi->views }} dilihat
                        </span>
                        @if ($materi->downloads > 0)
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <i class="fas fa-download mr-1"></i> {{ $materi->downloads }} download
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Description -->
                    @if ($materi->deskripsi)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Materi</h3>
                            <div class="prose max-w-none text-gray-700">
                                {!! nl2br(e($materi->deskripsi)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Materi Content (Teks atau Link) -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Isi Materi</h3>
                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                            @if (str_contains($materi->materi, '[Video]'))
                                @php
                                    // Ekstrak link video dari teks
                                    $videoContent = $materi->materi;
                                    if (preg_match('/\[Video\](.*?)\[\/Video\]/s', $videoContent, $matches)) {
                                        $videoUrl = trim($matches[1]);
                                        // Cek apakah YouTube, Vimeo, atau video lokal
                                        if (
                                            str_contains($videoUrl, 'youtube.com') ||
                                            str_contains($videoUrl, 'youtu.be')
                                        ) {
                                            // Konversi ke embed URL untuk YouTube
                                            $videoId = '';
                                            if (
                                                preg_match(
                                                    '/youtube\.com\/watch\?v=([^\&\?\/]+)/',
                                                    $videoUrl,
                                                    $idMatches,
                                                )
                                            ) {
                                                $videoId = $idMatches[1];
                                            } elseif (preg_match('/youtu\.be\/([^\&\?\/]+)/', $videoUrl, $idMatches)) {
                                                $videoId = $idMatches[1];
                                            }

                                            if ($videoId) {
                                                echo '<div class="aspect-w-16 aspect-h-9 mb-4">';
                                                echo '<iframe class="w-full h-64 md:h-96 rounded-lg" src="https://www.youtube.com/embed/' .
                                                    $videoId .
                                                    '" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
                                                echo '</div>';
                                            }
                                        } else {
                                            // Tampilkan link video biasa
                                            echo '<div class="text-center p-4">';
                                            echo '<a href="' .
                                                $videoUrl .
                                                '" target="_blank" class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium">';
                                            echo '<i class="fas fa-play mr-2"></i> Tonton Video Pembelajaran';
                                            echo '</a>';
                                            echo '</div>';
                                        }
                                    }
                                @endphp
                            @else
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($materi->materi)) !!}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- File Attachment -->
                    @if ($materi->file)
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">File Lampiran</h3>
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @php
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
                                        <div class="{{ $iconClass }} text-2xl mr-3"></div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ basename($materi->file) }}</p>
                                            <p class="text-sm text-gray-500">{{ strtoupper($extension) }} •
                                                {{ round(filesize(storage_path('app/public/' . $materi->file)) / 1024) }}
                                                KB</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('siswa.materi.download', $materi->id) }}"
                                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                        <i class="fas fa-download mr-2"></i> Download
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Komentar Section -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">
                            <i class="fas fa-comments mr-2"></i> Diskusi Materi
                            <span class="text-sm font-normal text-gray-500">({{ $materi->komentar_count }}
                                komentar)</span>
                        </h3>

                        <!-- Form Komentar -->
                        <div class="mb-8">
                            <form action="{{ route('siswa.materi.komentar.store', $materi->id) }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Komentar</label>
                                    <textarea name="isi_komentar" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        placeholder="Tulis pertanyaan atau pendapat Anda tentang materi ini..." required></textarea>
                                </div>
                                <div class="flex justify-end">
                                    <button type="submit"
                                        class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                        <i class="fas fa-paper-plane mr-2"></i> Kirim Komentar
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Daftar Komentar -->
                        <!-- PERBAIKAN DI SINI: ganti $materi->komentar->count dengan $materi->komentar_count -->
                        @if ($materi->komentar_count > 0)
                            <div class="space-y-6">
                                @foreach ($materi->komentar as $komentar)
                                    @php
                                        $profile = $komentar->getUserProfile();
                                    @endphp
                                    <div
                                        class="border border-gray-200 rounded-lg p-4 {{ $profile['type'] === 'guru' ? 'bg-blue-50' : '' }}">
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
                                                            action="{{ route('siswa.materi.komentar.destroy', $komentar->id) }}"
                                                            method="POST"
                                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="text-red-500 hover:text-red-700">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                                <p class="text-gray-700">
                                                    {{ $komentar->isi_komentar }}
                                                </p>

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
                        @else
                            <div class="text-center py-8 border border-gray-200 rounded-lg">
                                <i class="fas fa-comment-slash text-3xl text-gray-300 mb-3"></i>
                                <p class="text-gray-500">Belum ada komentar. Jadilah yang pertama berdiskusi!</p>
                            </div>
                        @endif
                    </div>

                    <!-- Footer Stats -->
                    <div class="bg-gray-50 px-6 py-4 border-t">
                        <div class="flex items-center justify-between text-sm text-gray-500">
                            <div class="flex items-center space-x-6">
                                <span>
                                    <i class="far fa-eye mr-1"></i> {{ $materi->views }} dilihat
                                </span>
                                <span>
                                    <i class="far fa-comment mr-1"></i> {{ $materi->komentar_count }} komentar
                                </span>
                                @if ($materi->downloads > 0)
                                    <span>
                                        <i class="fas fa-download mr-1"></i> {{ $materi->downloads }} download
                                    </span>
                                @endif
                            </div>
                            <span>
                                Terakhir update: {{ $materi->updated_at->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                        <i class="fas fa-lightbulb mr-2"></i> Tips Belajar:
                    </h4>
                    <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                        <li>Baca materi dengan seksama sebelum bertanya</li>
                        <li>Gunakan fitur komentar untuk bertanya jika ada yang kurang jelas</li>
                        <li>Download file materi untuk belajar offline</li>
                        <li>Diskusikan dengan teman sekelas untuk pemahaman yang lebih baik</li>
                    </ul>
                </div>
            </div>
    </main>

    <script>
        // Auto-hide flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('[role="alert"]');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 3000);
    </script>
</body>

</html>
