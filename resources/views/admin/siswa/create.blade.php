<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Siswa - E-Learning</title>
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
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Tambah Data Siswa</h1>
                    <p class="text-gray-600 mt-1">Isi form di bawah untuk menambahkan siswa baru</p>
                </div>
                <a href="{{ route('admin.siswa.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>
        </div>
        <!-- Form -->
        <form action="{{ route('admin.siswa.store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Kolom 1 -->
                <div class="space-y-4">
                    <!-- NISN -->
                    <div>
                        <label for="nisn" class="block text-sm font-medium text-gray-700 mb-1">
                            NISN <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nisn" name="nisn" value="{{ old('nisn') }}" required
                            maxlength="10"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Masukkan 10 digit NISN" pattern="[0-9]{10}"
                            title="Masukkan 10 digit angka NISN">
                        <p class="mt-2 text-xs text-gray-500">10 digit angka NISN</p>
                    </div>

                    <!-- NIS -->
                    <div>
                        <label for="nis" class="block text-sm font-medium text-gray-700 mb-1">
                            NIS <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nis" name="nis" value="{{ old('nis') }}" required
                            maxlength="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Masukkan 4 digit NIS" pattern="[0-9]{4}" title="Masukkan 4 digit angka NIS">
                        <p class="mt-2 text-xs text-gray-500">4 digit angka NIS</p>
                    </div>

                    <!-- Nama -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                            Email
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="nama@email.com">
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">
                            Tempat Lahir
                        </label>
                        <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Kota Kelahiran">
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Lahir
                        </label>
                        <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                    </div>

                    <!-- Agama -->
                    <div>
                        <label for="agama" class="block text-sm font-medium text-gray-700 mb-1">
                            Agama
                        </label>
                        <select id="agama" name="agama"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Pilih Agama</option>
                            <option value="Islam" {{ old('agama') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>
                </div>

                <!-- Kolom 2 -->
                <div class="space-y-4">
                    <!-- Kelas -->
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select id="kelas_id" name="kelas_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas as $item)
                            <option value="{{ $item->id }}"
                                {{ old('kelas_id') == $item->id ? 'selected' : '' }}>
                                {{ $item->kelas }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tingkat -->
                    <div>
                        <label for="tingkat" class="block text-sm font-medium text-gray-700 mb-1">
                            Tingkat
                        </label>
                        <select id="tingkat" name="tingkat"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Pilih Tingkat</option>
                            <option value="7" {{ old('tingkat') == '7' ? 'selected' : '' }}>Kelas 7</option>
                            <option value="8" {{ old('tingkat') == '8' ? 'selected' : '' }}>Kelas 8</option>
                            <option value="9" {{ old('tingkat') == '9' ? 'selected' : '' }}>Kelas 9</option>
                        </select>
                    </div>

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
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="08xxxxxxxxxx" pattern="[0-9]{10,13}" title="Masukkan 10-13 digit angka">
                    </div>
                </div>
            </div>

            <!-- Alamat -->
            <div class="mb-6">
                <label for="alamat" class="block text-sm font-medium text-gray-700 mb-1">
                    Alamat
                </label>
                <textarea id="alamat" name="alamat" rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                    placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
            </div>

            <!-- Info Login -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800 mb-1">Informasi Login:</h4>
                        <div class="text-sm text-blue-700 space-y-1">
                            <p><strong class="font-medium">Username:</strong> NISN</p>
                            <p><strong class="font-medium">Password:</strong> NIS</p>
                        </div>
                        <p class="text-xs text-blue-600 mt-2">
                            Siswa dapat mengganti password setelah login pertama kali.
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

<script>
    function toggleUploadForm() {
        const form = document.getElementById('uploadForm');
        form.classList.toggle('hidden');
    }

    // File input handler
    document.getElementById('file').addEventListener('change', function(e) {
        const fileName = document.getElementById('fileName');
        if (this.files.length > 0) {
            fileName.textContent = 'File terpilih: ' + this.files[0].name;
            fileName.classList.add('text-green-600', 'font-medium');
        } else {
            fileName.textContent = '';
        }
    });

    // Drag and drop functionality
    const dropArea = document.querySelector('label[for="file"]');

    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        dropArea.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropArea.addEventListener(eventName, unhighlight, false);
    });

    function highlight(e) {
        dropArea.classList.add('bg-blue-50', 'border-blue-300');
    }

    function unhighlight(e) {
        dropArea.classList.remove('bg-blue-50', 'border-blue-300');
    }

    dropArea.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        const input = document.getElementById('file');
        input.files = files;

        // Trigger change event
        const event = new Event('change');
        input.dispatchEvent(event);
    }
</script>

</html>