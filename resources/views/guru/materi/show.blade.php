<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $materi->judul_materi }} - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>
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
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('guru.materi.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali ke Kelola Materi
            </a>
        </div>

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

        <!-- Main Content -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 border-b">
                <div class="flex flex-col md:flex-row md:items-start md:justify-between">
                    <div class="flex-1">
                        <div class="flex items-center mb-3">
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
                                        <i class="fas fa-chalkboard-teacher mr-1"></i>
                                        {{ Auth::user()->name }}
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
                    <div class="mt-4 md:mt-0 flex gap-3">
                        <a href="{{ route('guru.materi.edit', $materi) }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 font-medium transition">
                            <i class="fas fa-edit mr-2"></i> Edit
                        </a>
                        @if ($materi->file)
                        <a href="{{ Storage::url($materi->file) }}" target="_blank"
                            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
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
                </div>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Description -->
                @if ($materi->deskripsi)
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Deskripsi Materi</h3>
                    <div class="text-gray-700 bg-gray-50 p-4 rounded-lg">
                        {!! nl2br(e($materi->deskripsi)) !!}
                    </div>
                </div>
                @endif

                <!-- Materi Content -->
                <div class="mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Isi Materi</h3>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-6">
                        @if (str_contains($materi->materi, '[Video]'))
                        @php
                        $videoContent = $materi->materi;
                        if (preg_match('/\[Video\]\s*(.*?)(?:\n|$)/', $videoContent, $matches)) {
                        $videoUrl = trim($matches[1]);
                        // Embed YouTube
                        if (
                        preg_match(
                        '/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^\&\?\/]+)/',
                        $videoUrl,
                        $idMatches,
                        )
                        ) {
                        $videoId = $idMatches[1];
                        echo '<div class="mb-4">';
                            echo '<iframe class="w-full h-64 md:h-96 rounded-lg" src="https://www.youtube.com/embed/' .
                                            $videoId .
                                            '" frameborder="0" allowfullscreen></iframe>';
                            echo '</div>';
                        } else {
                        echo '<p class="mb-4"><strong>Link Video:</strong></p>';
                        echo '<a href="' .
                                            $videoUrl .
                                            '" target="_blank" class="text-blue-600 hover:underline">' .
                            $videoUrl .
                            '</a>';
                        }
                        }
                        @endphp
                        @endif

                        <!-- Teks materi -->
                        @php
                        $textContent = preg_replace('/\[Video\].*?(?:\n|$)/', '', $materi->materi);
                        @endphp
                        @if (trim($textContent) && trim($textContent) !== 'Tidak ada konten teks')
                        <div class="text-gray-700 mt-4">
                            {!! nl2br(e(trim($textContent))) !!}
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
                                        KB
                                    </p>
                                </div>
                            </div>
                            <a href="{{ Storage::url($materi->file) }}" target="_blank"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
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
                        <span class="text-sm font-normal text-gray-500">({{ $komentars->total() }} komentar)</span>
                    </h3>

                    <!-- Form Komentar untuk Guru -->
                    <div class="mb-8">
                        <form action="{{ route('guru.materi.komentar.store', $materi) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Komentar sebagai
                                    Guru</label>
                                <textarea id="summernote" name="isi_komentar" rows="3"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                    placeholder="Berikan penjelasan tambahan atau jawab pertanyaan siswa..." required></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium transition">
                                    <i class="fas fa-paper-plane mr-2"></i> Kirim Komentar
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Daftar Komentar -->
                    @if ($komentars->count() > 0)
                    <div class="space-y-6">
                        @foreach ($komentars as $komentar)
                        @php
                        // Tentukan jenis user
                        if ($komentar->user_type === 'guru') {
                        $userName = $komentar->user->guru->nama ?? ($komentar->user->name ?? 'Guru');
                        $badgeColor = 'bg-blue-100 text-blue-800';
                        $badgeIcon = 'fas fa-chalkboard-teacher';
                        $bgColor = 'bg-blue-50';
                        } else {
                        $userName = $komentar->user->siswa->nama ?? ($komentar->user->name ?? 'Siswa');
                        $badgeColor = 'bg-gray-100 text-gray-800';
                        $badgeIcon = 'fas fa-user-graduate';
                        $bgColor = '';
                        }
                        @endphp
                        <div class="border border-gray-200 rounded-lg p-4 {{ $bgColor }}">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    <div
                                        class="w-10 h-10 {{ $komentar->user_type === 'guru' ? 'bg-blue-100' : 'bg-gray-100' }} rounded-full flex items-center justify-center">
                                        <i
                                            class="{{ $badgeIcon }} {{ $komentar->user_type === 'guru' ? 'text-blue-600' : 'text-gray-600' }}"></i>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-2">
                                        <div>
                                            <div class="flex items-center">
                                                <h4 class="font-semibold text-gray-900 mr-2">
                                                    {{ $userName }}
                                                </h4>
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $badgeColor }}">
                                                    <i class="{{ $badgeIcon }} mr-1"></i>
                                                    {{ $komentar->user_type === 'guru' ? 'Guru' : 'Siswa' }}
                                                </span>
                                            </div>
                                            <p class="text-sm text-gray-500">
                                                {{ $komentar->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                        @if (Auth::user()->guru->nip == $materi->guru_nip ||
                                        ($komentar->user_type === 'guru' && $komentar->user_id == Auth::id()))
                                        <form
                                            action="{{ route('guru.materi.komentar.destroy', $komentar) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus komentar ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                    <div class="text-gray-700 prose max-w-none">
                                        {!! $komentar->isi_komentar !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Pagination Komentar -->
                    @if ($komentars->hasPages())
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        {{ $komentars->links() }}
                    </div>
                    @endif
                    @else
                    <div class="text-center py-8 border border-gray-200 rounded-lg">
                        <i class="fas fa-comment-slash text-3xl text-gray-300 mb-3"></i>
                        <p class="text-gray-500">Belum ada komentar.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                placeholder: 'Tulis komentar...',
                tabsize: 2,
                height: 150,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'underline', 'clear']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                callbacks: {
                    onImageUpload: function(files) {
                        sendFile(files[0]);
                    }
                }
            });

            function sendFile(file) {
                var data = new FormData();
                data.append("file", file);
                $.ajax({
                    data: data,
                    type: "POST",
                    url: "{{ route('upload.image') }}",
                    cache: false,
                    contentType: false,
                    processData: false,
                    headers: {
                        'X-CSRF-TOKEN': $('input[name="_token"]').val()
                    },
                    success: function(url) {
                        $('#summernote').summernote('insertImage', url.location);
                    },
                    error: function(data) {
                        console.log(data);
                        alert('Gagal upload gambar');
                    }
                });
            }
        });
    </script>
</body>

</html>
