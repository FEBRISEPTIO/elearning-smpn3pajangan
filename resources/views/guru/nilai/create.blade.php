<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Nilai Siswa - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen">
    @include('guru.partials.header')

    <main class="md:ml-64 min-h-screen p-4 md:p-6">
        <div class="bg-white rounded-xl shadow-sm p-6 text-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Input Nilai Siswa</h1>
                    <p class="text-gray-600">Pilih kelas dan masukkan nilai untuk seluruh siswa.</p>
                </div>
                <a href="{{ route('guru.nilai.index') }}" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-arrow-left mr-1"></i> Kembali
                </a>
            </div>

            <form action="{{ route('guru.nilai.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block font-semibold mb-1">Pilih Kelas</label>
                        <select id="kelas_selector" class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach ($kelas as $k)
                            <option value="{{ $k->id }}">{{ $k->kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Mata Pelajaran</label>
                        <select name="mapel_id" required class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Mapel --</option>
                            @foreach ($mapels as $m)
                            <option value="{{ $m->id }}">{{ $m->nama_mapel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block font-semibold mb-1">Tugas (Opsional)</label>
                        <select name="tugas_id" class="w-full border rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">-- Pilih Tugas --</option>
                            @foreach ($tugas as $t)
                            <option value="{{ $t->id }}">{{ $t->judul_tugas }} ({{ $t->kelas->kelas }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="siswa_container" class="hidden">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2">Daftar Siswa</h3>
                    <div class="overflow-x-auto border rounded-xl mb-6">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500">
                                <tr>
                                    <th class="px-6 py-3 text-left">Nama Siswa</th>
                                    <th class="px-6 py-3 text-left">NISN</th>
                                    <th class="px-6 py-3 text-center w-32">Nilai (0-100)</th>
                                    <th class="px-6 py-3 text-left">Keterangan</th>
                                </tr>
                            </thead>
                            <tbody id="siswa_list" class="divide-y divide-gray-200">
                                <!-- Ajax Content -->
                            </tbody>
                        </table>
                    </div>
                    <div class="flex justify-end">
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-lg font-bold hover:bg-blue-700 transition">
                            <i class="fas fa-save mr-2"></i> Simpan Semua Nilai
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <script>
        function loadSiswa() {
            const kelasId = document.getElementById('kelas_selector').value;
            const tugasId = document.querySelector('select[name="tugas_id"]').value;
            const container = document.getElementById('siswa_container');
            const list = document.getElementById('siswa_list');

            if (!kelasId) {
                container.classList.add('hidden');
                return;
            }

            let url = `{{ route('guru.nilai.index') }}/get-siswa/${kelasId}`;
            if (tugasId) {
                url += `?tugas_id=${tugasId}`;
            }

            fetch(url)
                .then(res => res.json())
                .then(data => {
                    list.innerHTML = '';
                    if (data.length === 0) {
                        const msg = tugasId ? 'Tidak ada siswa yang sudah mengumpulkan tugas ini.' : 'Pilih Tugas untuk menampilkan daftar siswa yang sudah mengumpulkan.';
                        list.innerHTML = `<tr><td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">${msg}</td></tr>`;
                    } else {
                        data.forEach(s => {
                            list.innerHTML += `
                                <tr>
                                    <td class="px-6 py-4 font-bold text-gray-900">${s.nama}</td>
                                    <td class="px-6 py-4 text-gray-600">
                                        ${s.nisn}
                                        <input type="hidden" name="nisn[]" value="${s.nisn}">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="number" name="nilai[]" step="0.01" min="0" max="100" class="w-full border rounded px-2 py-1 text-center" placeholder="0">
                                    </td>
                                    <td class="px-6 py-4">
                                        <input type="text" name="keterangan[]" class="w-full border rounded px-2 py-1" placeholder="...">
                                    </td>
                                </tr>
                            `;
                        });
                    }
                    container.classList.remove('hidden');
                });
        }

        document.getElementById('kelas_selector').addEventListener('change', loadSiswa);
        document.querySelector('select[name="tugas_id"]').addEventListener('change', loadSiswa);
    </script>
</body>

</html>