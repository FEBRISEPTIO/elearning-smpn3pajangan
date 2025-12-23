<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tugas - Siswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    @include('siswa.partials.header')

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">Tugas</h1>
                <p class="text-gray-600 mt-1">Kerjakan dan kumpulkan tugas dari guru Anda</p>
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

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('siswa.tugas.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Mata Pelajaran Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
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

                            <!-- Status Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                                <select name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Semua Tugas</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                        (Belum Deadline)</option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                        Selesai (Lewat Deadline)</option>
                                </select>
                            </div>

                            <!-- Search & Buttons -->
                            <div class="flex items-end space-x-2">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Cari Tugas</label>
                                    <input type="text" name="search" value="{{ request('search') }}"
                                        placeholder="Cari judul tugas..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                </div>
                                <button type="submit"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    <i class="fas fa-filter"></i>
                                </button>
                                <a href="{{ route('siswa.tugas.index') }}"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tugas Grid -->
            @if ($tugas->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($tugas as $item)
                        @php
                            $isDeadlinePassed = now()->greaterThan($item->deadline);
                            $timeRemaining = now()->diff($item->deadline);
                        @endphp

                        <div
                            class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-200">
                            <!-- Status Header -->
                            <div class="{{ $isDeadlinePassed ? 'bg-gray-100' : 'bg-green-50' }} p-4 border-b">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div
                                            class="fas fa-tasks {{ $isDeadlinePassed ? 'text-gray-500' : 'text-green-500' }} text-xl mr-2">
                                        </div>
                                        <span
                                            class="text-sm font-medium {{ $isDeadlinePassed ? 'text-gray-700' : 'text-green-700' }}">
                                            @if ($isDeadlinePassed)
                                                Tugas Selesai
                                            @else
                                                Tugas Aktif
                                            @endif
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-500">
                                        {{ $item->created_at->diffForHumans() }}
                                    </span>
                                </div>
                            </div>

                            <!-- Content -->
                            <div class="p-4">
                                <h3 class="font-semibold text-lg text-gray-900 mb-2">
                                    <a href="{{ route('siswa.tugas.show', $item) }}" class="hover:text-blue-600">
                                        {{ $item->judul_tugas }}
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
                                        class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-chalkboard-teacher mr-1"></i>
                                        {{ $item->guru->name ?? 'Guru' }}
                                    </span>
                                </div>

                                <!-- Deadline Info -->
                                <div
                                    class="mb-4 p-3 {{ $isDeadlinePassed ? 'bg-red-50 border border-red-100' : 'bg-yellow-50 border border-yellow-100' }} rounded">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i
                                                class="fas fa-clock {{ $isDeadlinePassed ? 'text-red-500' : 'text-yellow-500' }} mr-2"></i>
                                            <span
                                                class="text-sm {{ $isDeadlinePassed ? 'text-red-600' : 'text-yellow-700' }}">
                                                @if ($isDeadlinePassed)
                                                    Deadline lewat
                                                @else
                                                    {{ $timeRemaining->d }} hari {{ $timeRemaining->h }} jam lagi
                                                @endif
                                            </span>
                                        </div>
                                        <span
                                            class="text-xs {{ $isDeadlinePassed ? 'text-red-500' : 'text-yellow-600' }}">
                                            {{ $item->deadline->format('d M, H:i') }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="flex items-center justify-between text-sm text-gray-500 pt-3 border-t">
                                    <div class="flex items-center space-x-4">
                                        <span class="flex items-center">
                                            <i class="fas fa-comment mr-1"></i>
                                            {{ $item->komentar_count }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-users mr-1"></i>
                                            {{ $item->pengumpulan_count ?? 0 }}
                                        </span>
                                    </div>
                                    <a href="{{ route('siswa.tugas.show', $item) }}"
                                        class="text-blue-600 hover:text-blue-800 font-medium">
                                        Lihat Tugas →
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($tugas->hasPages())
                    <div class="mt-8">
                        {{ $tugas->links() }}
                    </div>
                @endif
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="mx-auto w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mb-6">
                        <i class="fas fa-tasks text-gray-400 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Belum ada tugas</h3>
                    <p class="text-gray-600 max-w-md mx-auto mb-6">
                        Saat ini belum ada tugas yang diberikan untuk kelas Anda.
                    </p>
                    <div class="text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Hubungi guru Anda jika ada tugas yang belum muncul
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
