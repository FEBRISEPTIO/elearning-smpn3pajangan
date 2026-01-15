<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($tugas) ? 'Edit' : 'Buat' }} Tugas - E-Learning</title>
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
                        <i class="fas fa-tasks mr-2"></i>
                        {{ isset($tugas) ? 'Edit Tugas' : 'Buat Tugas Baru' }}
                    </h1>
                    <p class="text-gray-600 mt-1">
                        {{ isset($tugas) ? 'Perbarui informasi tugas' : 'Buat tugas baru untuk siswa' }}
                    </p>
                </div>
                <a href="{{ route('guru.tugas.index') }}"
                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            <!-- Flash Messages -->
            @if (session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                    <div class="flex">
                        <div class="shrink-0">
                            <i class="fas fa-exclamation-circle text-red-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

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
            <form action="{{ isset($tugas) ? route('guru.tugas.update', $tugas) : route('guru.tugas.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if (isset($tugas))
                    @method('PUT')
                @endif

                <!-- Judul Tugas -->
                <div class="mb-6">
                    <label for="judul" class="block text-sm font-medium text-gray-700 mb-1">
                        Judul Tugas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="judul" name="judul" required
                        value="{{ old('judul', $tugas->judul_tugas ?? '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Masukkan judul tugas">
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                        Deskripsi Tugas
                    </label>
                    <textarea id="deskripsi" name="deskripsi" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Berikan deskripsi singkat tentang tugas">{{ old('deskripsi', $tugas->deskripsi ?? '') }}</textarea>
                </div>

                <!-- Kelas dan Mata Pelajaran -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Kelas -->
                    <div>
                        <label for="kelas_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select id="kelas_id" name="kelas_id" required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas as $kelasItem)
                                <option value="{{ $kelasItem->id }}"
                                    {{ old('kelas_id', $tugas->kelas_id ?? '') == $kelasItem->id ? 'selected' : '' }}>
                                    {{ $kelasItem->kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

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
                                    {{ old('mata_pelajaran_id', $tugas->mapel_id ?? '') == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Deadline -->
                <div class="mb-6">
                    <label for="deadline" class="block text-sm font-medium text-gray-700 mb-1">
                        Deadline <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" id="deadline" name="deadline" required
                        value="{{ old('deadline', isset($tugas) ? $tugas->deadline->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        min="{{ date('Y-m-d\TH:i') }}">
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>Tentukan batas waktu pengumpulan tugas
                    </p>
                </div>

                <!-- Konten Tugas -->
                <div class="mb-6">
                    <label for="konten_tugas" class="block text-sm font-medium text-gray-700 mb-1">
                        Instruksi dan Konten Tugas
                    </label>
                    <textarea id="konten_tugas" name="konten_tugas" rows="6"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        placeholder="Tulis instruksi lengkap, materi pendukung, atau persyaratan tugas">{{ old('konten_tugas', $tugas->tugas ?? '') }}</textarea>
                    <p class="mt-2 text-xs text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>Gunakan untuk memberikan petunjuk lengkap tentang tugas
                    </p>
                </div>

                <!-- File Upload -->
                <div class="mb-6">
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-1">
                        File Tugas (Opsional)
                    </label>
                    <input type="file" id="file" name="file"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.jpg,.jpeg,.png">
                    <p class="mt-2 text-xs text-gray-500">
                        Format: PDF, DOC, DOCX, PPT, PPTX, TXT, JPG, JPEG, PNG (Maks. 20MB)
                    </p>
                </div>

                @if (isset($tugas) && $tugas->file)
                    <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <i class="fas fa-file text-blue-600 mr-3"></i>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ basename($tugas->file) }}</p>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <a href="{{ Storage::url($tugas->file) }}" target="_blank"
                                            class="text-xs text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-eye mr-1"></i> Lihat
                                        </a>
                                        <a href="{{ Storage::url($tugas->file) }}" download
                                            class="text-xs text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-download mr-1"></i> Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="hapus_file" value="1" class="sr-only">
                                <span class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </label>
                        </div>
                    </div>
                @endif

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
                            <input type="checkbox" id="allow_komentar" name="allow_komentar" value="1"
                                {{ old('allow_komentar', true) ? 'checked' : '' }}
                                class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <label for="allow_komentar" class="ml-3 text-sm text-gray-700">
                                Izinkan komentar pada tugas
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row justify-between gap-3 pt-4 border-t">
                    <!-- Delete Button -->
                    @if (isset($tugas))
                        <button type="button" onclick="confirmDelete()"
                            class="bg-red-600 hover:bg-red-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center justify-center transition">
                            <i class="fas fa-trash mr-2"></i>Hapus Tugas
                        </button>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('guru.tugas.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg text-sm font-medium text-center transition">
                            Batal
                        </a>
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg text-sm font-medium inline-flex items-center justify-center transition">
                            <i class="fas fa-save mr-2"></i>
                            {{ isset($tugas) ? 'Perbarui Tugas' : 'Simpan Tugas' }}
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
                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips Membuat Tugas:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Berikan judul yang jelas dan deskriptif</li>
                        <li>• Tentukan deadline yang realistis</li>
                        <li>• Sertakan instruksi yang jelas dan lengkap</li>
                        <li>• Berikan contoh jika diperlukan</li>
                        <li>• Sebutkan kriteria penilaian jika ada</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    @if (isset($tugas))
        <div id="deleteModal"
            class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Tugas?</h3>
                    <p class="text-gray-600 mb-6">
                        Apakah Anda yakin ingin menghapus tugas "{{ $tugas->judul_tugas }}"?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <form action="{{ route('guru.tugas.destroy', $tugas) }}" method="POST" class="flex-1">
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
    @endif

    <script>
        // Delete confirmation
        function confirmDelete() {
            document.getElementById('deleteModal').classList.remove('hidden');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
        }

        // Delete file confirmation
        document.querySelector('input[name="hapus_file"]')?.addEventListener('change', function(e) {
            if (e.target.checked && !confirm('Anda yakin ingin menghapus file ini?')) {
                e.target.checked = false;
            }
        });

        // Set default deadline: 7 hari dari sekarang
        document.addEventListener('DOMContentLoaded', function() {
            const deadlineInput = document.getElementById('deadline');
            if (deadlineInput && !deadlineInput.value) {
                const now = new Date();
                now.setDate(now.getDate() + 7);
                const formattedDate = now.toISOString().slice(0, 16);
                deadlineInput.value = formattedDate;
            }
        });
    </script>
</body>

</html>
