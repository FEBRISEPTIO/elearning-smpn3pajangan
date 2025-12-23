<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Guru - E-Learning</title>
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
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Data Guru</h1>
                    <p class="text-gray-600 mt-1">Isi form di bawah untuk menambahkan data guru baru</p>
                </div>
                <a href="{{ route('admin.guru.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">Ada kesalahan dalam pengisian form</h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form action="{{ route('admin.guru.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kolom 1 -->
                    <div class="space-y-4">
                        <!-- NIK -->
                        <div>
                            <label for="nik" class="block text-sm font-medium text-gray-700 mb-1">
                                NIK <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nik" name="nik" value="{{ old('nik') }}" required
                                maxlength="20"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Masukkan NIK guru">
                            <p class="mt-1 text-xs text-gray-500">Nomor Induk Guru (hanya angka)</p>
                        </div>

                        <!-- Nama -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Lengkap <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Masukkan nama lengkap">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="nama@email.com">
                        </div>
                    </div>

                    <!-- Kolom 2 -->
                    <div class="space-y-4">
                        <!-- Jenis Kelamin -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jenis Kelamin <span class="text-red-500">*</span>
                            </label>
                            <div class="flex space-x-6">
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jnsklmn" value="L"
                                        {{ old('jnsklmn') == 'L' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                                    <span class="ml-2 text-sm text-gray-700">Laki-laki</span>
                                </label>
                                <label class="inline-flex items-center">
                                    <input type="radio" name="jnsklmn" value="P"
                                        {{ old('jnsklmn') == 'P' ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 border-gray-300 focus:ring-blue-500" required>
                                    <span class="ml-2 text-sm text-gray-700">Perempuan</span>
                                </label>
                            </div>
                        </div>

                        <!-- No Telepon -->
                        <div>
                            <label for="no_telp" class="block text-sm font-medium text-gray-700 mb-1">
                                No. Telepon
                            </label>
                            <input type="text" id="no_telp" name="no_telp" value="{{ old('no_telp') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="08xxxxxxxxxx">
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-6">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat
                    </label>
                    <textarea id="alamat" name="alamat" rows="3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                </div>

                <!-- Info Login -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 mb-1">Informasi Login:</h4>
                            <div class="text-sm text-blue-700 space-y-1">
                                <p><strong class="font-medium">Username:</strong> NIK</p>
                                <p><strong class="font-medium">Password:</strong> NIK</p>
                            </div>
                            <p class="text-xs text-blue-600 mt-2">
                                Guru dapat mengganti password setelah login pertama kali.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center transition">
                        <i class="fas fa-save mr-2"></i>Simpan Data
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>

</html>
