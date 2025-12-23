<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kelas - E-Learning</title>
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
    @include('admin.partials.header')

    <!-- Main Content -->
    <main class="md:ml-64 min-h-screen p-4 md:p-6 transition-all duration-300">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Manajemen Kelas</h1>
                    <p class="text-gray-600 mt-1">Kelola data kelas di sistem E-Learning</p>
                </div>
                <a href="{{ route('admin.kelas.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Kelas
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

            <!-- Table -->
            <div class="overflow-x-auto border border-gray-200 rounded-xl">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama Kelas
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jumlah Siswa
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($kelas as $index => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <div class="flex items-center">
                                        <div
                                            class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                            <i class="fas fa-school text-blue-600 text-sm"></i>
                                        </div>
                                        <span class="font-medium">{{ $item->kelas }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div
                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium 
                                        {{ $item->siswa->count() > 0 ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                        <i class="fas fa-users mr-1"></i>
                                        {{ $item->siswa->count() }} siswa
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.kelas.edit', $item->id) }}"
                                            class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                            <i class="fas fa-edit mr-1"></i>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        <form action="{{ route('admin.kelas.destroy', $item->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus kelas ini?')">
                                                <i class="fas fa-trash mr-1"></i>
                                                <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-school text-4xl mb-3 opacity-50"></i>
                                        <p class="text-sm">Belum ada data kelas</p>
                                        <a href="{{ route('admin.kelas.create') }}"
                                            class="mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            <i class="fas fa-plus mr-1"></i>Tambah Kelas Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($kelas->count())
                <!-- Stats Summary -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-layer-group text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-blue-700">Total Kelas</p>
                                <p class="text-xl font-bold text-blue-800">{{ $kelas->count() }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-users text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-green-700">Total Siswa</p>
                                <p class="text-xl font-bold text-green-800">
                                    {{ $kelas->sum(function ($kelas) {return $kelas->siswa->count();}) }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-purple-50 border border-purple-200 rounded-xl p-4">
                        <div class="flex items-center">
                            <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-chart-bar text-purple-600"></i>
                            </div>
                            <div>
                                <p class="text-xs text-purple-700">Rata-rata Siswa/Kelas</p>
                                <p class="text-xl font-bold text-purple-800">
                                    {{ $kelas->count() > 0? round($kelas->sum(function ($kelas) {return $kelas->siswa->count();}) / $kelas->count(),1): 0 }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
</body>

</html>
