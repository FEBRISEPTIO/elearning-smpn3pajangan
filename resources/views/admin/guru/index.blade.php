<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Guru - E-Learning</title>
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
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Manajemen Guru</h1>
                    <p class="text-gray-600 mt-1">Kelola data guru di sistem E-Learning</p>
                </div>
                <a href="{{ route('admin.guru.create') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                    <i class="fas fa-plus mr-2"></i>Tambah Guru
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
                                NIK
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Jenis Kelamin
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No. Telepon
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($guru as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    {{ $item->nik }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->nama }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->email }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $item->jnsklmn == 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800' }}">
                                        {{ $item->jnsklmn == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $item->no_telp ?? '-' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('admin.guru.edit', $item->nik) }}"
                                            class="text-blue-600 hover:text-blue-900 inline-flex items-center">
                                            <i class="fas fa-edit mr-1"></i>
                                            <span class="hidden sm:inline">Edit</span>
                                        </a>
                                        <form action="{{ route('admin.guru.destroy', $item->nik) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-red-600 hover:text-red-900 inline-flex items-center"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus guru ini?')">
                                                <i class="fas fa-trash mr-1"></i>
                                                <span class="hidden sm:inline">Hapus</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center justify-center text-gray-500">
                                        <i class="fas fa-users text-4xl mb-3 opacity-50"></i>
                                        <p class="text-sm">Belum ada data guru</p>
                                        <a href="{{ route('admin.guru.create') }}"
                                            class="mt-3 text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            <i class="fas fa-plus mr-1"></i>Tambah Guru Pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($guru->count())
                <!-- Info Box -->
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 mb-1">Informasi Login Guru:</h4>
                            <div class="text-sm text-blue-700 space-y-1">
                                <p><strong class="font-medium">Username:</strong> NIK</p>
                                <p><strong class="font-medium">Password:</strong> NIK (bisa direset melalui tombol reset
                                    password)</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>
</body>

</html>
