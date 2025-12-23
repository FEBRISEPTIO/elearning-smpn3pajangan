<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($tugas) ? 'Edit' : 'Buat' }} Tugas - Guru</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    @include('guru.partials.header')

    <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('guru.tugas.index') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Tugas
                </a>
            </div>

            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-900">
                    <i class="fas fa-tasks mr-2"></i>
                    {{ isset($tugas) ? 'Edit Tugas' : 'Buat Tugas Baru' }}
                </h1>
                <p class="text-gray-600 mt-1">
                    {{ isset($tugas) ? 'Perbarui informasi tugas' : 'Buat tugas baru untuk siswa' }}
                </p>
            </div>

            <!-- Flash Messages -->
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <form action="{{ isset($tugas) ? route('guru.tugas.update', $tugas) : route('guru.tugas.store') }}"
                    method="POST">
                    @csrf
                    @if (isset($tugas))
                        @method('PUT')
                    @endif

                    <div class="p-6">
                        <!-- Judul Tugas -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Judul Tugas <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="judul" value="{{ old('judul', $tugas->judul_tugas ?? '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Masukkan judul tugas" required>
                            @error('judul')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deskripsi Tugas
                            </label>
                            <textarea name="deskripsi" rows="3"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Berikan deskripsi singkat tentang tugas">{{ old('deskripsi', $tugas->deskripsi ?? '') }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kelas dan Mapel -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Kelas -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Kelas <span class="text-red-500">*</span>
                                </label>
                                <select name="kelas_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas as $kelasItem)
                                        <option value="{{ $kelasItem->id }}"
                                            {{ old('kelas_id', $tugas->kelas_id ?? '') == $kelasItem->id ? 'selected' : '' }}>
                                            {{ $kelasItem->kelas }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('kelas_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Mata Pelajaran -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Mata Pelajaran <span class="text-red-500">*</span>
                                </label>
                                <select name="mata_pelajaran_id"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    @foreach ($mapels as $mapel)
                                        <option value="{{ $mapel->id }}"
                                            {{ old('mata_pelajaran_id', $tugas->mapel_id ?? '') == $mapel->id ? 'selected' : '' }}>
                                            {{ $mapel->nama_mapel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('mata_pelajaran_id')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Deadline -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Deadline <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="deadline"
                                value="{{ old('deadline', isset($tugas) ? $tugas->deadline->format('Y-m-d\TH:i') : '') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                min="{{ date('Y-m-d\TH:i') }}" required>
                            <p class="mt-1 text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Tentukan batas waktu pengumpulan tugas
                            </p>
                            @error('deadline')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Konten Tugas -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Instruksi dan Konten Tugas
                            </label>
                            <textarea name="konten_tugas" rows="6"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                placeholder="Tulis instruksi lengkap, materi pendukung, atau persyaratan tugas">{{ old('konten_tugas', $tugas->tugas ?? '') }}</textarea>
                            <p class="mt-1 text-sm text-gray-500">
                                <i class="fas fa-info-circle mr-1"></i>
                                Gunakan untuk memberikan petunjuk lengkap tentang tugas
                            </p>
                            @error('konten_tugas')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Form Footer -->
                    <div class="bg-gray-50 px-6 py-4 border-t">
                        <div class="flex justify-between items-center">
                            <a href="{{ route('guru.tugas.index') }}"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 font-medium">
                                <i class="fas fa-save mr-2"></i>
                                {{ isset($tugas) ? 'Update Tugas' : 'Simpan Tugas' }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Tips -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                    <i class="fas fa-lightbulb mr-2"></i> Tips Membuat Tugas:
                </h4>
                <ul class="text-sm text-blue-700 list-disc list-inside space-y-1">
                    <li>Berikan judul yang jelas dan deskriptif</li>
                    <li>Tentukan deadline yang realistis</li>
                    <li>Sertakan instruksi yang jelas dan lengkap</li>
                    <li>Berikan contoh jika diperlukan</li>
                    <li>Sebutkan kriteria penilaian jika ada</li>
                </ul>
            </div>
        </div>
    </main>

    <script>
        // Set min date untuk deadline
        document.addEventListener('DOMContentLoaded', function() {
            const deadlineInput = document.querySelector('input[name="deadline"]');
            if (deadlineInput && !deadlineInput.value) {
                // Set default deadline: 7 hari dari sekarang
                const now = new Date();
                now.setDate(now.getDate() + 7);
                const formattedDate = now.toISOString().slice(0, 16);
                deadlineInput.value = formattedDate;
            }
        });
    </script>
</body>

</html>
