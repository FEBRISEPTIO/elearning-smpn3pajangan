<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Materi - E-Learning</title>
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
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Kelola Materi</h1>
                    <p class="text-gray-600 mt-1">Kelola dan atur semua materi pembelajaran Anda</p>
                </div>
                <a href="{{ route('guru.materi.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Materi
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

            @if (session('file_info'))
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-file-upload text-blue-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-blue-700">File berhasil diupload: {{ session('file_info') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-book text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Total Materi</p>
                            <p class="text-2xl font-bold">{{ $materi->total() }}</p>
                        </div>
                    </div>
                </div>

                {{-- <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-eye text-xl"></i>
                        </div>
                        {{-- <div>
                            <p class="text-sm opacity-90">Total Dilihat</p>
                            <p class="text-2xl font-bold">{{ $materi->sum('views') ?? 0 }}</p>
                        </div> --}}
                    {{-- </div>
                </div> --}}

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-comments text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Total Komentar</p>
                            <p class="text-2xl font-bold">{{ $materi->sum('komentar_count') ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-500 to-amber-600 text-white rounded-xl p-5 shadow-lg">
                    <div class="flex items-center">
                        <div class="bg-white/20 p-3 rounded-full mr-4">
                            <i class="fas fa-clock text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm opacity-90">Terakhir Upload</p>
                            <p class="text-xl font-bold">
                                @if ($materi->count() > 0)
                                    {{ $materi->first()->created_at->diffForHumans() }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Materi</h3>
                <form method="GET" action="{{ route('guru.materi.index') }}">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Kelas Filter -->
                        <div>
                            <label for="kelas" class="block text-sm font-medium text-gray-700 mb-1">
                                Kelas
                            </label>
                            <select id="kelas" name="kelas"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Semua kelas</option>
                                @foreach ($kelas as $k)
                                    <option value="{{ $k->id }}"
                                        {{ request('kelas') == $k->id ? 'selected' : '' }}>
                                        {{ $k->kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Mata Pelajaran Filter -->
                        <div>
                            <label for="mapel" class="block text-sm font-medium text-gray-700 mb-1">
                                Mata Pelajaran
                            </label>
                            <select id="mapel" name="mapel"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                                <option value="">Semua mapel</option>
                                @foreach ($mapels as $mapel)
                                    <option value="{{ $mapel->id }}"
                                        {{ request('mapel') == $mapel->id ? 'selected' : '' }}>
                                        {{ $mapel->nama_mapel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search -->
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 mb-1">
                                Pencarian
                            </label>
                            <input type="text" id="search" name="search" value="{{ request('search') }}"
                                placeholder="Cari judul materi..."
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <a href="{{ route('guru.materi.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg text-sm font-medium text-center transition">
                            <i class="fas fa-redo mr-2"></i>Reset
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center transition">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Materi List -->
            <div class="border border-gray-200 rounded-xl overflow-hidden">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900">
                            Daftar Materi ({{ $materi->total() }})
                        </h3>
                        @if ($materi->count() > 0)
                            <p class="text-sm text-gray-500">
                                Halaman {{ $materi->currentPage() }} dari {{ $materi->lastPage() }}
                            </p>
                        @endif
                    </div>

                    @if ($materi->count() > 0)
                        <div class="space-y-6">
                            @foreach ($materi as $item)
                                <div class="border border-gray-200 rounded-xl p-6 hover:bg-gray-50 transition">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-6">
                                        <!-- Left Content -->
                                        <div class="flex-1">
                                            <div class="flex items-start gap-4">
                                                <!-- File Icon -->
                                                <div class="flex-shrink-0">
                                                    @if ($item->file && Storage::disk('public')->exists($item->file))
                                                        @php
                                                            $extension = pathinfo($item->file, PATHINFO_EXTENSION);
                                                            $iconClass =
                                                                [
                                                                    'pdf' => 'fas fa-file-pdf text-red-500',
                                                                    'doc' => 'fas fa-file-word text-blue-500',
                                                                    'docx' => 'fas fa-file-word text-blue-500',
                                                                    'ppt' => 'fas fa-file-powerpoint text-orange-500',
                                                                    'pptx' => 'fas fa-file-powerpoint text-orange-500',
                                                                    'txt' => 'fas fa-file-alt text-gray-500',
                                                                    'jpg' => 'fas fa-file-image text-green-500',
                                                                    'jpeg' => 'fas fa-file-image text-green-500',
                                                                    'png' => 'fas fa-file-image text-green-500',
                                                                ][strtolower($extension)] ??
                                                                'fas fa-file text-gray-500';
                                                        @endphp
                                                        <div class="{{ $iconClass }} text-3xl"></div>
                                                    @elseif($item->materi && str_contains($item->materi, '[Video]'))
                                                        <div class="fas fa-video text-purple-500 text-3xl"></div>
                                                    @else
                                                        <div class="fas fa-file-alt text-gray-400 text-3xl"></div>
                                                    @endif
                                                </div>

                                                <!-- Content -->
                                                <div class="flex-1">
                                                    <h4 class="text-xl font-semibold text-gray-900 mb-3">
                                                        <a href="{{ route('guru.materi.show', $item) }}"
                                                            class="hover:text-blue-600">
                                                            {{ $item->judul_materi }}
                                                        </a>
                                                    </h4>

                                                    <!-- Badges -->
                                                    <div class="flex flex-wrap gap-2 mb-4">
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <i class="fas fa-users mr-1"></i>
                                                            {{ $item->kelas->kelas ?? '-' }}
                                                        </span>
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <i class="fas fa-book mr-1"></i>
                                                            {{ $item->mataPelajaran->nama_mapel ?? '-' }}
                                                        </span>
                                                        {{-- <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <i class="fas fa-eye mr-1"></i> {{ $item->views ?? 0 }}
                                                            dilihat
                                                        </span> --}}
                                                        @if ($item->file)
                                                            <span
                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                <i class="fas fa-paperclip mr-1"></i> Ada File
                                                            </span>
                                                        @endif
                                                    </div>

                                                    <!-- Description -->
                                                    @if ($item->deskripsi)
                                                        <p class="text-gray-600 mb-4">
                                                            {{ Str::limit($item->deskripsi, 200) }}
                                                        </p>
                                                    @endif

                                                    <!-- Metadata -->
                                                    <div class="flex items-center text-sm text-gray-500 gap-4">
                                                        <span>
                                                            <i class="far fa-calendar-alt mr-1"></i>
                                                            {{ $item->created_at->format('d M Y') }}
                                                        </span>
                                                        {{-- <span>
                                                            <i class="far fa-clock mr-1"></i>
                                                            {{ $item->created_at->format('H:i') }}
                                                        </span> --}}
                                                        @if ($item->file && Storage::disk('public')->exists($item->file))
                                                            <span>
                                                                <i class="fas fa-file mr-1"></i>
                                                                {{ basename($item->file) }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Right Actions -->
                                        <div class="flex flex-col gap-3">
                                            <a href="{{ route('guru.materi.show', $item) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-700 font-medium rounded-lg hover:bg-blue-100 transition">
                                                <i class="fas fa-eye mr-2"></i> Lihat
                                            </a>

                                            <a href="{{ route('guru.materi.edit', $item) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-green-50 text-green-700 font-medium rounded-lg hover:bg-green-100 transition">
                                                <i class="fas fa-edit mr-2"></i> Edit
                                            </a>

                                            <form action="{{ route('guru.materi.destroy', $item) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-700 font-medium rounded-lg hover:bg-red-100 transition">
                                                    <i class="fas fa-trash mr-2"></i> Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        @if ($materi->hasPages())
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                    <div>
                                        <p class="text-sm text-gray-700">
                                            Menampilkan
                                            <span class="font-medium">{{ $materi->firstItem() }}</span>
                                            sampai
                                            <span class="font-medium">{{ $materi->lastItem() }}</span>
                                            dari
                                            <span class="font-medium">{{ $materi->total() }}</span>
                                            materi
                                        </p>
                                    </div>
                                    <div class="flex gap-2">
                                        @if ($materi->previousPageUrl())
                                            <a href="{{ $materi->previousPageUrl() }}"
                                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                                                <i class="fas fa-chevron-left mr-2"></i> Sebelumnya
                                            </a>
                                        @endif

                                        @if ($materi->nextPageUrl())
                                            <a href="{{ $materi->nextPageUrl() }}"
                                                class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50">
                                                Selanjutnya <i class="fas fa-chevron-right ml-2"></i>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-12">
                            <div
                                class="mx-auto w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                                <i class="fas fa-book-open text-gray-400 text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada materi</h3>
                            <p class="text-gray-600 mb-8 max-w-md mx-auto">
                                Anda belum membuat materi pembelajaran. Mulai dengan membuat materi pertama untuk siswa
                                Anda.
                            </p>
                            <a href="{{ route('guru.materi.create') }}"
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-plus mr-2"></i>
                                Buat Materi Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips:</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Gunakan judul yang jelas dan deskriptif untuk setiap materi</li>
                            <li>• Pastikan file yang diupload dalam format yang sesuai dengan kebutuhan siswa</li>
                            <li>• Periksa kembali link video sebelum membagikan ke siswa</li>
                            <li>• Gunakan filter untuk mencari materi dengan lebih mudah</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
