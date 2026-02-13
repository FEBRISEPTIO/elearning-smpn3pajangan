<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($tugas) ? 'Edit' : 'Buat' }} Tugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen">
    @include('guru.partials.header')

    <main class="md:ml-64 p-4 md:p-6">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <h1 class="text-2xl font-bold mb-6">
                <i class="fas fa-tasks mr-2"></i>
                {{ isset($tugas) ? 'Edit Tugas' : 'Buat Tugas Baru' }}
            </h1>

            @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                <div class="flex">
                    <i class="fas fa-exclamation-circle text-red-500 mt-1 mr-3"></i>
                    <div>
                        <h3 class="text-sm font-semibold text-red-800">Ada kesalahan:</h3>
                        <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST"
                action="{{ isset($tugas) ? route('guru.tugas.update', $tugas->id) : route('guru.tugas.store') }}"
                enctype="multipart/form-data">
                @csrf
                @if (isset($tugas))
                @method('PUT')
                @endif

                <!-- Judul -->
                <div class="mb-6">
                    <label class="block mb-2 font-medium text-gray-700">
                        Judul Tugas <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="judul" value="{{ old('judul', $tugas->judul_tugas ?? '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Masukkan judul tugas" required>
                </div>

                <!-- Kelas & Mapel -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block mb-2 font-medium text-gray-700">
                            Kelas <span class="text-red-500">*</span>
                        </label>
                        <select name="kelas_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">Pilih Kelas</option>
                            @foreach ($kelas as $k)
                            <option value="{{ $k->id }}"
                                {{ old('kelas_id', $tugas->kelas_id ?? '') == $k->id ? 'selected' : '' }}>
                                {{ $k->kelas }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block mb-2 font-medium text-gray-700">
                            Mata Pelajaran <span class="text-red-500">*</span>
                        </label>
                        <select name="mata_pelajaran_id"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                            required>
                            <option value="">Pilih Mapel</option>
                            @foreach ($mapels as $m)
                            <option value="{{ $m->id }}"
                                {{ old('mata_pelajaran_id', $tugas->mapel_id ?? '') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Deskripsi -->
                <div class="mb-6">
                    <label class="block mb-2 font-medium text-gray-700">Deskripsi</label>
                    <textarea name="deskripsi" rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Deskripsi singkat tugas">{{ old('deskripsi', $tugas->deskripsi ?? '') }}</textarea>
                </div>

                <!-- Konten Tugas (field: tugas) -->
                <div class="mb-6">
                    <label class="block mb-2 font-medium text-gray-700">Instruksi Tugas</label>
                    <textarea name="konten_tugas" rows="6"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        placeholder="Tulis instruksi dan konten tugas di sini...">{{ old('konten_tugas', $tugas->tugas ?? '') }}</textarea>
                </div>

                <!-- File Tugas (Lampiran dari Guru) -->
                <div class="mb-6">
                    <label class="block mb-2 font-medium text-gray-700">Lampiran File (Opsional)</label>
                    @if(isset($tugas) && $tugas->file)
                    <div class="mb-3 p-3 bg-blue-50 border border-blue-200 rounded-lg flex items-center justify-between">
                        <div class="flex items-center">
                            <i class="fas fa-file-alt text-blue-500 mr-3"></i>
                            <span class="text-sm font-medium text-blue-700">File sudah ada: {{ basename($tugas->file) }}</span>
                        </div>
                        <a href="{{ Storage::url($tugas->file) }}" target="_blank" class="text-xs text-blue-600 hover:underline">
                            Lihat File
                        </a>
                    </div>
                    @endif
                    <input type="file" name="file_tugas"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Format: pdf, doc, docx, ppt, pptx, jpg, png. Max 5MB.
                    </p>
                </div>

                <!-- Deadline -->
                <div class="mb-6">
                    <label class="block mb-2 font-medium text-gray-700">
                        Deadline <span class="text-red-500">*</span>
                    </label>
                    <input type="datetime-local" name="deadline"
                        value="{{ old('deadline', isset($tugas) ? $tugas->deadline->format('Y-m-d\TH:i') : '') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                        required>
                    <p class="mt-2 text-sm text-gray-500">
                        <i class="fas fa-info-circle mr-1"></i>
                        Tentukan batas waktu pengumpulan tugas
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-between pt-6 border-t">
                    <a href="{{ route('guru.tugas.index') }}"
                        class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i>
                        {{ isset($tugas) ? 'Perbarui Tugas' : 'Simpan Tugas' }}
                    </button>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Set default deadline: 7 hari dari sekarang
        document.addEventListener('DOMContentLoaded', function() {
            const deadlineInput = document.querySelector('input[name="deadline"]');
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