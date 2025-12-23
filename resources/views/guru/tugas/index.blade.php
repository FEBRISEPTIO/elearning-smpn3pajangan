<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Tugas - Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    @include('guru.partials.header')

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Manajemen Tugas</h1>
                    <p class="text-gray-600 mt-1">Kelola tugas untuk siswa Anda</p>
                </div>
                <a href="{{ route('guru.tugas.create') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                    <i class="fas fa-plus mr-2"></i> Buat Tugas Baru
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

            <!-- Filter Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                <div class="p-4">
                    <form method="GET" action="{{ route('guru.tugas.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <!-- Kelas Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                                <select name="kelas"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Semua Kelas</option>
                                    @foreach ($kelas as $kelasItem)
                                        <option value="{{ $kelasItem->id }}"
                                            {{ request('kelas') == $kelasItem->id ? 'selected' : '' }}>
                                            {{ $kelasItem->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Mapel Filter -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Mata Pelajaran</label>
                                <select name="mapel"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Semua Mapel</option>
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
                                    <option value="">Semua Status</option>
                                    <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif
                                    </option>
                                    <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>
                                        Selesai</option>
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
                                <a href="{{ route('guru.tugas.index') }}"
                                    class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">
                                    <i class="fas fa-redo"></i>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tugas List -->
            @if ($tugas->count() > 0)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Judul Tugas
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kelas & Mapel
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deadline
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Komentar
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($tugas as $item)
                                    @php
                                        $isDeadlinePassed = now()->greaterThan($item->deadline);
                                        $timeRemaining = now()->diff($item->deadline);
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="font-medium text-gray-900">{{ $item->judul_tugas }}</div>
                                            <div class="text-sm text-gray-500 mt-1">
                                                {{ Str::limit($item->deskripsi, 50) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">{{ $item->kelas->kelas ?? '-' }}</div>
                                            <div class="text-sm text-gray-500">
                                                {{ $item->mataPelajaran->nama_mapel ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $item->deadline->format('d M Y, H:i') }}
                                            </div>
                                            <div
                                                class="text-sm {{ $isDeadlinePassed ? 'text-red-500' : 'text-green-500' }}">
                                                @if ($isDeadlinePassed)
                                                    <i class="fas fa-clock mr-1"></i> Deadline lewat
                                                @else
                                                    <i class="fas fa-clock mr-1"></i> {{ $timeRemaining->d }} hari
                                                    {{ $timeRemaining->h }} jam
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($isDeadlinePassed)
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i> Selesai
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Aktif
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center text-sm text-gray-500">
                                                <i class="fas fa-comment mr-2"></i>
                                                {{ $item->komentar_count }} komentar
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ route('guru.tugas.show', $item) }}"
                                                    class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('guru.tugas.edit', $item) }}"
                                                    class="text-yellow-600 hover:text-yellow-900">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('guru.tugas.destroy', $item) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus tugas ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if ($tugas->hasPages())
                    <div class="mt-4">
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
                        Anda belum membuat tugas untuk siswa. Buat tugas pertama Anda sekarang.
                    </p>
                    <a href="{{ route('guru.tugas.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">
                        <i class="fas fa-plus mr-2"></i> Buat Tugas Pertama
                    </a>
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
