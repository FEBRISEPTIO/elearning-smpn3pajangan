<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Materi - Dashboard Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    @include('guru.partials.header')

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Kelola Materi</h2>
                    <p class="text-gray-600 mt-1">Kelola dan atur semua materi pembelajaran Anda</p>
                </div>
                <a href="{{ route('guru.materi.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>Tambah Materi
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                        <button type="button" onclick="this.parentElement.parentElement.remove()"
                            class="ml-auto text-green-700 hover:text-green-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                        <button type="button" onclick="this.parentElement.parentElement.remove()"
                            class="ml-auto text-red-700 hover:text-red-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            @if (session('file_info'))
                <div class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-file-upload mr-2"></i>
                        <span>File berhasil diupload: {{ session('file_info') }}</span>
                        <button type="button" onclick="this.parentElement.parentElement.remove()"
                            class="ml-auto text-blue-700 hover:text-blue-900">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 p-3 rounded-md">
                                <i class="fas fa-book text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Materi</dt>
                                <dd class="text-2xl font-semibold text-gray-900">{{ $materi->total() }}</dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 p-3 rounded-md">
                                <i class="fas fa-eye text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Dilihat</dt>
                                <dd class="text-2xl font-semibold text-gray-900">
                                    {{ $materi->sum('views') ?? 0 }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 p-3 rounded-md">
                                <i class="fas fa-comments text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Komentar</dt>
                                <dd class="text-2xl font-semibold text-gray-900">
                                    {{ $materi->sum('komentar_count') ?? 0 }}
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-100 p-3 rounded-md">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <dt class="text-sm font-medium text-gray-500 truncate">Terakhir Upload</dt>
                                <dd class="text-lg font-semibold text-gray-900">
                                    @if ($materi->count() > 0)
                                        {{ $materi->first()->created_at->diffForHumans() }}
                                    @else
                                        -
                                    @endif
                                </dd>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Materi</h3>
                    <form method="GET" action="{{ route('guru.materi.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Kelas Filter -->
                            <div>
                                <label for="kelas" class="block text-sm font-medium text-gray-700">
                                    Kelas
                                </label>
                                <select id="kelas" name="kelas"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                                <label for="mapel" class="block text-sm font-medium text-gray-700">
                                    Mata Pelajaran
                                </label>
                                <select id="mapel" name="mapel"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
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
                                <label for="search" class="block text-sm font-medium text-gray-700">
                                    Pencarian
                                </label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}"
                                    placeholder="Cari judul materi..."
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('guru.materi.index') }}"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                                <i class="fas fa-redo mr-2"></i>Reset
                            </a>
                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-filter mr-2"></i>Filter
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Materi List -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex justify-between items-center mb-6">
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
                                <div
                                    class="border border-gray-200 rounded-lg p-6 hover:bg-gray-50 transition duration-200">
                                    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                        <!-- Left Content -->
                                        <div class="flex-1">
                                            <div class="flex items-start space-x-4">
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
                                                    <h4 class="text-xl font-semibold text-gray-900 mb-2">
                                                        <a href="{{ route('guru.materi.show', $item) }}"
                                                            class="hover:text-blue-600">
                                                            {{ $item->judul_materi }}
                                                        </a>
                                                    </h4>

                                                    <!-- Badges -->
                                                    <div class="flex flex-wrap gap-2 mb-3">
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
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            <i class="fas fa-eye mr-1"></i> {{ $item->views ?? 0 }}
                                                            dilihat
                                                        </span>
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
                                                    <div class="flex items-center text-sm text-gray-500 space-x-4">
                                                        <span>
                                                            <i class="far fa-calendar-alt mr-1"></i>
                                                            {{ $item->created_at->format('d M Y') }}
                                                        </span>
                                                        <span>
                                                            <i class="far fa-clock mr-1"></i>
                                                            {{ $item->created_at->format('H:i') }}
                                                        </span>
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
                                        <div class="flex flex-col space-y-2">
                                            <!-- View Button -->
                                            <a href="{{ route('guru.materi.show', $item) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-700 font-medium rounded-md hover:bg-blue-100 transition">
                                                <i class="fas fa-eye mr-2"></i> Lihat
                                            </a>

                                            <!-- Edit Button -->
                                            <a href="{{ route('guru.materi.edit', $item) }}"
                                                class="inline-flex items-center justify-center px-4 py-2 bg-green-50 text-green-700 font-medium rounded-md hover:bg-green-100 transition">
                                                <i class="fas fa-edit mr-2"></i> Edit
                                            </a>

                                            <!-- Delete Button -->
                                            <form action="{{ route('guru.materi.destroy', $item) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-700 font-medium rounded-md hover:bg-red-100 transition">
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
                                <div class="flex items-center justify-between">
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
                                    <div>
                                        @if ($materi->previousPageUrl())
                                            <a href="{{ $materi->previousPageUrl() }}"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                <i class="fas fa-chevron-left mr-2"></i> Sebelumnya
                                            </a>
                                        @endif

                                        @if ($materi->nextPageUrl())
                                            <a href="{{ $materi->nextPageUrl() }}"
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 ml-2">
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
                                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700">
                                <i class="fas fa-plus mr-2"></i>
                                Buat Materi Pertama
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Info -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips:</h4>
                <ul class="text-sm text-blue-700 list-disc list-inside">
                    <li>Gunakan judul yang jelas dan deskriptif untuk setiap materi</li>
                    <li>Pastikan file yang diupload dalam format yang sesuai dengan kebutuhan siswa</li>
                    <li>Periksa kembali link video sebelum membagikan ke siswa</li>
                    <li>Gunakan filter untuk mencari materi dengan lebih mudah</li>
                </ul>
            </div>
        </div>
    </main>

    <script>
        // Auto-hide flash messages after 5 seconds
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('[class*="bg-"]');
            flashMessages.forEach(msg => {
                if (msg.getAttribute('role') === 'alert') {
                    msg.style.transition = 'opacity 0.5s';
                    msg.style.opacity = '0';
                    setTimeout(() => msg.remove(), 500);
                }
            });
        }, 5000);
    </script>
</body>

</html>
