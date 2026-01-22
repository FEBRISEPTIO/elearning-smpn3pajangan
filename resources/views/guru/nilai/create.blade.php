<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Nilai Baru - E-Learning</title>
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
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">
                        <i class="fas fa-plus-circle mr-2"></i>
                        Tambah Nilai Baru
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Isi formulir untuk menambahkan nilai siswa baru
                    </p>
                </div>
                <a href="{{ route('guru.nilai.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="shrink-0">
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
            <form action="{{ route('guru.nilai.store') }}" method="POST">
                @csrf

                <!-- Pilih Kelas -->
                <div class="mb-6">
                    <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Kelas <span class="text-red-500">*</span>
                    </label>
                    <select id="kelas_id" name="kelas_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Pilih Kelas</option>
                        @foreach ($kelas as $k)
                            <option value="{{ $k->id }}" {{ old('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->kelas ?? $k->nama_kelas }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Siswa -->
                <div class="mb-6">
                    <label for="siswa_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Siswa <span class="text-red-500">*</span>
                    </label>
                    <select id="siswa_id" name="siswa_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Pilih Siswa</option>
                        @foreach ($siswa as $s)
                            <option value="{{ $s->id }}" {{ old('siswa_id') == $s->id ? 'selected' : '' }}>
                                {{ $s->nama }} (NISN: {{ $s->nisn }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Pilih Mata Pelajaran -->
                <div class="mb-6">
                    <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Mata Pelajaran <span class="text-red-500">*</span>
                    </label>
                    <select id="mata_pelajaran_id" name="mata_pelajaran_id" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Pilih Mata Pelajaran</option>
                        @foreach ($mapels as $mapel)
                            <option value="{{ $mapel->id }}"
                                {{ old('mata_pelajaran_id') == $mapel->id ? 'selected' : '' }}>
                                {{ $mapel->nama_mapel }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Jenis Nilai -->
                <div class="mb-6">
                    <label for="jenis" class="block text-sm font-medium text-gray-700 mb-1">
                        Jenis Nilai <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis" name="jenis" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        <option value="">Pilih Jenis Nilai</option>
                        @foreach ($jenis as $key => $value)
                            <option value="{{ $key }}" {{ old('jenis') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Nilai -->
                <div class="mb-6">
                    <label for="nilai" class="block text-sm font-medium text-gray-700 mb-1">
                        Nilai <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="nilai" name="nilai" min="0" max="100" step="0.1"
                        required value="{{ old('nilai') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Masukkan nilai (0-100)">
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>Nilai harus antara 0 - 100
                    </p>
                </div>

                <!-- Keterangan -->
                <div class="mb-6">
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan (Opsional)
                    </label>
                    <textarea id="keterangan" name="keterangan" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Contoh: Tugas Harian, Ulangan Bab 1, UTS Semester 1">{{ old('keterangan') }}</textarea>
                </div>

                <!-- Additional Settings -->
                <div class="mb-6 bg-gray-50 border border-gray-200 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengaturan Tambahan</h3>
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input type="checkbox" id="notify_siswa" name="notify_siswa" value="1"
                                {{ old('notify_siswa', true) ? 'checked' : '' }}
                                class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="notify_siswa" class="ml-3 text-sm text-gray-700">
                                Kirim notifikasi ke siswa
                            </label>
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox" id="tampilkan_ranking" name="tampilkan_ranking" value="1"
                                {{ old('tampilkan_ranking', true) ? 'checked' : '' }}
                                class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="tampilkan_ranking" class="ml-3 text-sm text-gray-700">
                                Tampilkan dalam ranking kelas
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between gap-3 pt-4 border-t">
                    <a href="{{ route('guru.nilai.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg text-sm font-medium text-center transition">
                        <i class="fas fa-times mr-2"></i>Batal
                    </a>
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center justify-center transition">
                        <i class="fas fa-save mr-2"></i>Simpan Nilai
                    </button>
                </div>
            </form>
        </div>

        <!-- Tips -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-amber-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Panduan Pengisian Nilai:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Pastikan memilih siswa yang tepat dari daftar</li>
                        <li>• Pilih jenis nilai yang sesuai (Tugas, Ulangan, UTS, UAS)</li>
                        <li>• Masukkan nilai dengan format angka (0-100)</li>
                        <li>• Berikan keterangan yang jelas untuk memudahkan pencatatan</li>
                        <li>• Gunakan notifikasi untuk memberitahu siswa tentang nilai baru</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Validasi nilai
        document.getElementById('nilai').addEventListener('input', function(e) {
            const nilai = parseFloat(e.target.value);
            if (nilai < 0 || nilai > 100) {
                e.target.setCustomValidity('Nilai harus antara 0 - 100');
            } else {
                e.target.setCustomValidity('');
            }
        });

        // Filter siswa berdasarkan kelas
        document.getElementById('kelas_id').addEventListener('change', function() {
            const kelasId = this.value;
            const siswaSelect = document.getElementById('siswa_id');

            if (kelasId) {
                // In a real application, you would fetch students via AJAX
                // For now, we'll just show a message
                siswaSelect.innerHTML = '<option value="">Loading siswa...</option>';

                // Simulate AJAX call
                setTimeout(() => {
                    // This should be replaced with actual AJAX call
                    siswaSelect.innerHTML = '<option value="">Pilih Siswa</option>';
                    @foreach ($siswa as $s)
                        if ({{ $s->kelas_id }} == kelasId) {
                            siswaSelect.innerHTML +=
                                '<option value="{{ $s->id }}">{{ $s->nama }} (NISN: {{ $s->nisn }})</option>';
                        }
                    @endforeach
                }, 300);
            }
        });
    </script>
</body>

</html>
