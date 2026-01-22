<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nilai - E-Learning</title>
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
                        <i class="fas fa-edit mr-2"></i>
                        Edit Nilai Siswa
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Perbarui informasi nilai siswa
                    </p>
                </div>
                <a href="{{ route('guru.nilai.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <!-- Student Info -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-user-graduate text-blue-600 text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">{{ $siswa->nama }}</h3>
                        <div class="mt-1 space-y-1">
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-id-card mr-1"></i>NISN: {{ $siswa->nisn }}
                            </p>
                            <p class="text-sm text-gray-600">
                                <i class="fas fa-school mr-1"></i>Kelas:
                                {{ $siswa->kelas->nama_kelas ?? $siswa->kelas->kelas }}
                            </p>
                        </div>
                    </div>
                </div>
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

            <!-- Form Edit -->
            <form action="{{ route('guru.nilai.update', $siswa->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">

                <div class="space-y-6">
                    <!-- Mata Pelajaran -->
                    <div>
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
                    <div>
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
                    <div>
                        <label for="nilai" class="block text-sm font-medium text-gray-700 mb-1">
                            Nilai <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="nilai" name="nilai" min="0" max="100" step="0.1"
                            required value="{{ old('nilai') }}"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Masukkan nilai (0-100)">
                    </div>

                    <!-- Keterangan -->
                    <div>
                        <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                            Keterangan (Opsional)
                        </label>
                        <textarea id="keterangan" name="keterangan" rows="3"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                            placeholder="Contoh: Tugas Harian, Ulangan Bab 1">{{ old('keterangan') }}</textarea>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between gap-3 pt-8 border-t">
                    <button type="button" onclick="confirmDelete()"
                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center justify-center transition">
                        <i class="fas fa-trash mr-2"></i>Hapus Nilai
                    </button>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('guru.nilai.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg text-sm font-medium text-center transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center justify-center transition">
                            <i class="fas fa-save mr-2"></i>Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Tips -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-amber-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips Edit Nilai:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Periksa kembali data sebelum menyimpan perubahan</li>
                        <li>• Pastikan nilai yang dimasukkan sesuai dengan kriteria penilaian</li>
                        <li>• Berikan keterangan yang jelas untuk perubahan yang dilakukan</li>
                        <li>• Update nilai secara berkala untuk akurasi data</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
            <div class="text-center">
                <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Nilai?</h3>
                <p class="text-gray-600 mb-6">
                    Apakah Anda yakin ingin menghapus semua nilai untuk siswa "{{ $siswa->nama }}"?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="flex space-x-3">
                    <button type="button" onclick="closeDeleteModal()"
                        class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                        Batal
                    </button>
                    <form action="{{ route('guru.nilai.destroy', $siswa->id) }}" method="POST" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-3 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Delete confirmation
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Validasi nilai
        document.getElementById('nilai').addEventListener('input', function(e) {
            const nilai = parseFloat(e.target.value);
            if (nilai < 0 || nilai > 100) {
                e.target.setCustomValidity('Nilai harus antara 0 - 100');
            } else {
                e.target.setCustomValidity('');
            }
        });
    </script>
</body>

</html>
