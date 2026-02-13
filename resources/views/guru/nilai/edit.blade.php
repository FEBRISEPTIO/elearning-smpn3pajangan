<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Nilai Siswa - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen">
    @include('guru.partials.header')

    <main class="md:ml-64 min-h-screen p-4 md:p-6">
        <div class="bg-white rounded-xl shadow-sm p-6 text-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Edit Nilai Siswa</h1>
                    <p class="text-gray-600">Perbarui nilai untuk {{ $nilai->siswa->nama }}.</p>
                </div>
                <a href="{{ route('guru.nilai.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <form action="{{ route('guru.nilai.update', $nilai->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-semibold mb-1">Nama Siswa</label>
                        <input type="text" value="{{ $nilai->siswa->nama }}" disabled class="w-full bg-gray-100 border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">NISN</label>
                        <input type="text" value="{{ $nilai->nisn }}" disabled class="w-full bg-gray-100 border rounded-lg px-3 py-2">
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Mata Pelajaran</label>
                        <select name="mapel_id" required class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach ($mapels as $m)
                            <option value="{{ $m->id }}" {{ $nilai->mapel_id == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Nilai (0-100)</label>
                        <input type="number" name="nilai" value="{{ $nilai->nilai }}" step="0.01" min="0" max="100" required class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block font-semibold mb-1">Keterangan</label>
                    <textarea name="keterangan" rows="3" class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">{{ $nilai->keterangan }}</textarea>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                    <button type="button" onclick="window.history.back()" class="bg-gray-500 text-white px-6 py-2 rounded-lg font-bold hover:bg-gray-600 transition">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>