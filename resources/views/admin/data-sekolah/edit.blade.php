<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Data Sekolah - E-Learning</title>
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
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Edit Data Sekolah</h1>
                    <p class="text-gray-600 mt-1">Ubah informasi data sekolah yang akan ditampilkan di halaman login</p>
                </div>
                <a href="{{ route('admin.data-sekolah.index') }}"
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

            @if(session('success'))
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

            <!-- Form -->
            <form action="{{ route('admin.data-sekolah.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kolom 1 -->
                    <div class="space-y-4">
                        <!-- Nama Sekolah -->
                        <div>
                            <label for="nama_sekolah" class="block text-sm font-medium text-gray-700 mb-1">
                                Nama Sekolah <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="nama_sekolah" name="nama_sekolah" 
                                value="{{ old('nama_sekolah', $data->nama_sekolah ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="Masukkan nama sekolah lengkap">
                        </div>

                        <!-- Telepon -->
                        <div>
                            <label for="telepon" class="block text-sm font-medium text-gray-700 mb-1">
                                Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="telepon" name="telepon" 
                                value="{{ old('telepon', $data->telepon ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="(021) 1234-5678">
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <input type="email" id="email" name="email" 
                                value="{{ old('email', $data->email ?? '') }}" required
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="info@sekolah.ac.id">
                        </div>
                    </div>

                    <!-- Kolom 2 -->
                    <div class="space-y-4">
                        <!-- Website -->
                        <div>
                            <label for="website" class="block text-sm font-medium text-gray-700 mb-1">
                                Website
                            </label>
                            <input type="url" id="website" name="website" 
                                value="{{ old('website', $data->website ?? '') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                                placeholder="https://sekolah.ac.id">
                        </div>

                        <!-- Logo Sekolah -->
                        <div>
                            <label for="logo" class="block text-sm font-medium text-gray-700 mb-1">
                                Logo Sekolah
                            </label>
                            <div class="flex items-center space-x-4">
                                <div class="flex-1">
                                    <div class="relative">
                                        <input type="file" id="logo" name="logo" 
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                            accept="image/jpeg,image/png,image/jpg">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG (Max: 2MB)</p>
                                </div>
                                
                                @if($data && $data->logo)
                                    <div class="flex-shrink-0">
                                        <div class="relative group">
                                            <img src="{{ Storage::url($data->logo) }}" 
                                                alt="Logo Sekolah"
                                                class="h-16 w-16 object-contain rounded-lg border border-gray-200">
                                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 rounded-lg flex items-center justify-center transition-opacity">
                                                <span class="text-white text-xs font-medium">Logo Saat Ini</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Alamat -->
                <div class="mb-6">
                    <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Lengkap <span class="text-red-500">*</span>
                    </label>
                    <textarea id="alamat" name="alamat" rows="3" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Masukkan alamat lengkap sekolah">{{ old('alamat', $data->alamat ?? '') }}</textarea>
                </div>

                <!-- Visi dan Misi -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Visi -->
                    <div>
                        <label for="visi" class="block text-sm font-medium text-gray-700 mb-1">
                            Visi Sekolah
                        </label>
                        <textarea id="visi" name="visi" rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Tuliskan visi sekolah">{{ old('visi', $data->visi ?? '') }}</textarea>
                    </div>

                    <!-- Misi -->
                    <div>
                        <label for="misi" class="block text-sm font-medium text-gray-700 mb-1">
                            Misi Sekolah
                        </label>
                        <textarea id="misi" name="misi" rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Tuliskan misi sekolah (gunakan baris baru untuk membuat list)">{{ old('misi', $data->misi ?? '') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">Ganti baris untuk membuat list</p>
                    </div>
                </div>

                <!-- Info -->
                <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                        <div>
                            <h4 class="text-sm font-semibold text-blue-800 mb-1">Informasi Penting:</h4>
                            <div class="text-sm text-blue-700 space-y-1">
                                <p><i class="fas fa-check-circle mr-1"></i> Data ini akan ditampilkan di halaman login sistem E-Learning</p>
                                <p><i class="fas fa-check-circle mr-1"></i> Pastikan semua informasi akurat dan terkini</p>
                                <p><i class="fas fa-check-circle mr-1"></i> Logo akan ditampilkan di sidebar dan halaman login</p>
                                <p><i class="fas fa-check-circle mr-1"></i> Visi dan misi membantu mengenalkan sekolah kepada pengguna</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end space-x-4">
                    <a href="{{ route('admin.data-sekolah.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center transition">
                        <i class="fas fa-save mr-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Update file input label
        document.getElementById('logo').addEventListener('change', function(e) {
            var fileName = e.target.files[0] ? e.target.files[0].name : 'Pilih file...';
            var label = this.nextElementSibling;
            if (label && label.classList.contains('file:border-0')) {
                // Customize the displayed text
                this.setAttribute('data-file-name', fileName);
            }
        });

        // Character counter for textareas
        document.querySelectorAll('textarea').forEach(textarea => {
            const updateCounter = () => {
                const counter = textarea.nextElementSibling;
                const maxLength = textarea.getAttribute('maxlength');
                
                if (maxLength) {
                    if (!counter || !counter.classList.contains('char-counter')) {
                        const newCounter = document.createElement('div');
                        newCounter.className = 'char-counter text-xs text-gray-500 mt-1';
                        textarea.parentNode.insertBefore(newCounter, textarea.nextSibling);
                    }
                    
                    const currentLength = textarea.value.length;
                    textarea.nextElementSibling.textContent = `${currentLength}/${maxLength} karakter`;
                }
            };

            textarea.addEventListener('input', updateCounter);
            updateCounter();
        });

        // Auto resize textareas
        function autoResize(textarea) {
            textarea.style.height = 'auto';
            textarea.style.height = (textarea.scrollHeight) + 'px';
        }

        document.querySelectorAll('textarea').forEach(textarea => {
            textarea.addEventListener('input', function() {
                autoResize(this);
            });
            autoResize(textarea);
        });
    </script>
</body>

</html>