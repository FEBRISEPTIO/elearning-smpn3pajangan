<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($materi) ? 'Edit Materi' : 'Buat Materi Baru' }} - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    @include('guru.partials.header')

    <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ isset($materi) ? 'Edit Materi' : 'Tambah Materi' }}
                    </h2>
                    <a href="{{ route('guru.materi.index') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
                <p class="text-gray-600">
                    {{ isset($materi) ? 'Perbarui materi untuk siswa' : 'Buat materi baru untuk siswa' }}
                </p>
            </div>

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                    role="alert">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form
                        action="{{ isset($materi) ? route('guru.materi.update', $materi->id) : route('guru.materi.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($materi))
                            @method('PUT')
                        @endif

                        <!-- Judul -->
                        <div class="mb-4">
                            <label for="judul" class="block text-sm font-medium text-gray-700">
                                Judul Materi *
                            </label>
                            <input type="text" id="judul" name="judul" required
                                value="{{ old('judul', $materi->judul_materi ?? '') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Masukkan judul materi">
                        </div>

                        <!-- Kelas dan Mata Pelajaran -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <!-- Kelas -->
                            <div>
                                <label for="kelas_id" class="block text-sm font-medium text-gray-700">
                                    Kelas *
                                </label>
                                <select id="kelas_id" name="kelas_id" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}"
                                            {{ old('kelas_id', $materi->kelas_id ?? '') == $k->id ? 'selected' : '' }}>
                                            {{ $k->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Mata Pelajaran -->
                            <div>
                                <label for="mata_pelajaran_id" class="block text-sm font-medium text-gray-700">
                                    Mata Pelajaran *
                                </label>
                                <select id="mata_pelajaran_id" name="mata_pelajaran_id" required
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($mapels as $mapel)
                                        <option value="{{ $mapel->id }}"
                                            {{ old('mata_pelajaran_id', $materi->mapel_id ?? '') == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-4">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                                Deskripsi Materi
                            </label>
                            <textarea id="deskripsi" name="deskripsi" rows="3"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Deskripsikan materi">{{ old('deskripsi', $materi->deskripsi ?? '') }}</textarea>
                        </div>

                        <!-- Konten Teks -->
                        <div class="mb-4">
                            <label for="konten_teks" class="block text-sm font-medium text-gray-700">
                                Konten Materi
                            </label>
                            <textarea id="konten_teks" name="konten_teks" rows="8"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Tulis konten materi Anda di sini...">{{ old('konten_teks', $materi->materi ?? '') }}</textarea>
                        </div>

                        <!-- File Upload -->
                        <div class="mb-4">
                            <label for="file" class="block text-sm font-medium text-gray-700">
                                File Materi (Opsional)
                            </label>
                            <div class="mt-1 flex items-center">
                                <input type="file" id="file" name="file"
                                    class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    accept=".pdf,.doc,.docx,.ppt,.pptx,.txt,.jpg,.jpeg,.png">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">
                                Format: PDF, DOC, DOCX, PPT, PPTX, TXT, JPG, JPEG, PNG (Maks. 20MB)
                            </p>
                        </div>

                        @if (isset($materi) && $materi->file)
                            <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <i class="fas fa-file text-blue-600 mr-3"></i>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ basename($materi->file) }}
                                            </p>
                                            <a href="{{ Storage::url($materi->file) }}" target="_blank"
                                                class="text-xs text-blue-600 hover:text-blue-800">
                                                <i class="fas fa-eye mr-1"></i> Lihat
                                            </a>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ Storage::url($materi->file) }}" download
                                            class="text-blue-600 hover:text-blue-800">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <label class="cursor-pointer text-red-600 hover:text-red-800">
                                            <input type="checkbox" name="hapus_file" value="1" class="sr-only">
                                            <i class="fas fa-trash"></i>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Link Video -->
                        <div class="mb-4">
                            <label for="link_video" class="block text-sm font-medium text-gray-700">
                                Link Video (Opsional)
                            </label>
                            <input type="url" id="link_video" name="link_video"
                                value="{{ old('link_video', $materi->link_video ?? '') }}"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="https://youtube.com/watch?v=...">
                            <p class="mt-1 text-xs text-gray-500">
                                Contoh: YouTube, Vimeo, atau link video lainnya
                            </p>
                        </div>

                        <!-- Additional Settings -->
                        <div class="mb-6 p-4 bg-gray-50 border border-gray-200 rounded-lg">
                            <h3 class="text-sm font-semibold text-gray-900 mb-3">Pengaturan Tambahan</h3>

                            <div class="space-y-2">
                                <div class="flex items-center">
                                    <input type="checkbox" id="notify_siswa" name="notify_siswa" value="1"
                                        {{ old('notify_siswa', true) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="notify_siswa" class="ml-2 block text-sm text-gray-700">
                                        Kirim notifikasi ke siswa
                                    </label>
                                </div>

                                <div class="flex items-center">
                                    <input type="checkbox" id="allow_komentar" name="allow_komentar" value="1"
                                        {{ old('allow_komentar', true) ? 'checked' : '' }}
                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                    <label for="allow_komentar" class="ml-2 block text-sm text-gray-700">
                                        Izinkan komentar pada materi
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-between space-x-3">
                            <div>
                                @if (isset($materi))
                                    <!-- Delete Button -->
                                    <button type="button" onclick="confirmDelete()"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm">
                                        <i class="fas fa-trash mr-2"></i>Hapus Materi
                                    </button>
                                @endif
                            </div>

                            <div class="flex space-x-3">
                                <a href="{{ route('guru.materi.index') }}"
                                    class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm">
                                    <i class="fas fa-times mr-2"></i>Batal
                                </a>
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-save mr-2"></i>
                                    {{ isset($materi) ? 'Perbarui Materi' : 'Simpan Materi' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tips -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips Membuat Materi:</h4>
                <ul class="text-sm text-blue-700 list-disc list-inside">
                    <li>Judul materi harus jelas dan deskriptif</li>
                    <li>Sertakan konten yang lengkap dan terstruktur</li>
                    <li>Lampirkan file pendukung jika diperlukan</li>
                    <li>Tambahkan link video untuk pembelajaran yang lebih menarik</li>
                    <li>Pastikan materi sesuai dengan level kelas yang dituju</li>
                </ul>
            </div>
        </div>
    </main>

    <!-- Delete Confirmation Modal -->
    @if (isset($materi))
        <div id="deleteModal"
            class="fixed inset-0 bg-gray-900 bg-opacity-75 z-50 hidden flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl">
                <div class="text-center">
                    <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Materi?</h3>
                    <p class="text-gray-600 mb-6">
                        Apakah Anda yakin ingin menghapus materi "{{ $materi->judul_materi }}"?
                        Tindakan ini tidak dapat dibatalkan.
                    </p>
                    <div class="flex space-x-3">
                        <button type="button" onclick="closeDeleteModal()"
                            class="flex-1 px-4 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50">
                            Batal
                        </button>
                        <form action="{{ route('guru.materi.destroy', $materi) }}" method="POST" class="flex-1">
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

        // File name display
        document.getElementById('file')?.addEventListener('change', function(e) {
            const fileName = e.target.files[0] ? e.target.files[0].name : 'Tidak ada file yang dipilih';
            console.log('File selected:', fileName);
        });

        // Delete file confirmation
        document.querySelector('input[name="hapus_file"]')?.addEventListener('change', function(e) {
            if (e.target.checked) {
                if (!confirm('Anda yakin ingin menghapus file ini?')) {
                    e.target.checked = false;
                }
            }
        });

        // Form validation
        document.querySelector('form').addEventListener('submit', function(e) {
            const judul = document.getElementById('judul').value.trim();
            const kelas = document.getElementById('kelas_id').value;
            const mapel = document.getElementById('mata_pelajaran_id').value;
            const file = document.getElementById('file')?.files[0];

            if (!judul) {
                e.preventDefault();
                alert('Judul materi harus diisi');
                return;
            }

            if (!kelas) {
                e.preventDefault();
                alert('Kelas harus dipilih');
                return;
            }

            if (!mapel) {
                e.preventDefault();
                alert('Mata pelajaran harus dipilih');
                return;
            }

            // File size validation (20MB)
            if (file && file.size > 20 * 1024 * 1024) {
                e.preventDefault();
                alert('Ukuran file melebihi batas maksimal 20MB');
                return;
            }
        });
    </script>
</body>

</html>
