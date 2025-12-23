<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi Pembelajaran - Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    @include('siswa.partials.header')

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Materi Pembelajaran</h1>
                    <p class="text-gray-600 mt-1">Akses materi pembelajaran dari guru Anda</p>
                </div>
                <div class="text-sm text-gray-500">
                    <i class="fas fa-users mr-1"></i>
                    Kelas: {{ Auth::user()->siswa->kelas->kelas ?? '-' }}
                </div>
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

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('siswa.materi.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Mata Pelajaran Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Mata Pelajaran
                                </label>
                                <select name="mapel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Semua Mata Pelajaran</option>
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
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    Cari Materi
                                </label>
                                <div class="relative">
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Cari judul atau deskripsi..."
                                        class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-end">
                                <button type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium">
                                    <i class="fas fa-filter mr-2"></i> Filter
                                </button>
                                <a href="{{ route('siswa.materi.index') }}"
                                    class="ml-2 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-md">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Materi Grid -->
            @if ($materi->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($materi as $item)
                        <div
                            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <!-- File Type Header -->
                            <div class="{{ $item->file ? 'bg-blue-50' : 'bg-purple-50' }} p-4 border-b">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if ($item->file)
                                            @php
                                                $extension = pathinfo($item->file, PATHINFO_EXTENSION);
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
                                            <div class="{{ $iconClass }} text-xl mr-2"></div>
                                            <span class="text-sm font-medium text-gray-700">File
                                                {{ strtoupper($extension) }}</span>
                                        @elseif(str_contains($item->materi, '[Video]'))
                                            <div class="fas fa-video text-purple-500 text-xl mr-2"></div>
                                            <span class="text-sm font-medium text-gray-700">Video Pembelajaran</span>
                                        @else
                                            <div class="fas fa-file-alt text-gray-500 text-xl mr-2"></div>
                                            <span class="text-sm font-medium text-gray-700">Materi Teks</span>
                                        @endif
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $item->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="font-semibold text-lg text-gray-900 mb-2">
                                    <a href="{{ route('siswa.materi.show', $item) }}" class="hover:text-blue-600">
                                        {{ $item->judul_materi }}
                                    </a>
                                </h3>

                                <p class="text-gray-600 text-sm mb-4">
                                    {{ Str::limit($item->deskripsi, 100) }}
                                </p>

                                <!-- Badges -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                        <i class="fas fa-book mr-1"></i>
                                        {{ $item->mataPelajaran->nama_mapel ?? '-' }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-chalkboard-teacher mr-1"></i>
                                        {{ $item->guru->name ?? 'Guru' }}
                                    </span>
                                </div>

                                <!-- Stats -->
                                <div class="flex items-center justify-between text-sm text-gray-500 pt-3 border-t">
                                    <div class="flex items-center space-x-4">
                                        <span class="flex items-center">
                                            <i class="fas fa-eye mr-1"></i>
                                            {{ $item->views }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-comment mr-1"></i>
                                            {{ $item->komentar_count }}
                                        </span>
                                        @if ($item->file)
                                            <span class="flex items-center">
                                                <i class="fas fa-download mr-1"></i>
                                                {{ $item->downloads ?? 0 }}
                                            </span>
                                        @endif
                                    </div>
                                    <a href="{{ route('siswa.materi.show', $item) }}"
                                        class="text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat Materi →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($materi->hasPages())
                    <div class="mt-8">
                        {{ $materi->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-book-open text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada materi</h3>
                    <p class="text-gray-600 max-w-md mx-auto mb-6">
                        Saat ini belum ada materi pembelajaran yang tersedia untuk kelas Anda.
                    </p>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Hubungi guru Anda jika membutuhkan materi pembelajaran
                    </div>
                </div>
            @endif
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
