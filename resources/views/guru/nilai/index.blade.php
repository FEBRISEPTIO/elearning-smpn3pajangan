<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nilai Siswa - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100 min-h-screen">
    @include('guru.partials.header')

    <!-- Main Content -->
    <main class="md:ml-64 min-h-screen p-4 md:p-6 transition-all duration-300">
        <div class="bg-white rounded-xl shadow-sm p-6 text-sm">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">
                        <i class="fas fa-chart-line mr-2 text-blue-600"></i>
                        Data Nilai Siswa
                    </h1>
                    <p class="text-gray-600 mt-1">Kelola nilai siswa berdasarkan mata pelajaran dan tugas.</p>
                </div>
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('guru.nilai.create') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium inline-flex items-center transition shadow-sm">
                        <i class="fas fa-plus mr-2"></i> Input Nilai
                    </a>
                </div>
            </div>

            @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-r-lg">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
            @endif

            <!-- Filters -->
            <form action="{{ route('guru.nilai.index') }}" method="GET" class="bg-gray-50 p-4 rounded-xl border border-gray-200 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Pilih Kelas</label>
                        <select name="kelas_id" onchange="this.form.submit()" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelas as $k)
                            <option value="{{ $k->id }}" {{ request('kelas_id') == $k->id ? 'selected' : '' }}>
                                {{ $k->kelas }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-1">Mata Pelajaran</label>
                        <select name="mapel_id" onchange="this.form.submit()" class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500 transition">
                            <option value="">Semua Mapel</option>
                            @foreach ($mapels as $m)
                            <option value="{{ $m->id }}" {{ request('mapel_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->nama_mapel }}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg font-medium hover:bg-blue-700 transition">
                            <i class="fas fa-filter mr-1"></i> Filter
                        </button>
                        <a href="{{ route('guru.nilai.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg font-medium hover:bg-gray-600 transition">
                            <i class="fas fa-redo mr-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="overflow-x-auto bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Siswa</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Kelas</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Mata Pelajaran</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Tugas</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider text-center">Nilai</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse ($nilais as $n)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4">
                                <div class="font-bold text-gray-900">{{ $n->siswa->nama }}</div>
                                <div class="text-xs text-gray-500">{{ $n->nisn }}</div>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $n->siswa->kelas->kelas }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $n->mapel->nama_mapel }}
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                {{ $n->tugas->judul_tugas ?? '-' }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $n->nilai >= 75 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    {{ $n->nilai }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap space-x-2">
                                <a href="{{ route('guru.nilai.edit', $n->id) }}" class="text-blue-600 hover:text-blue-800 transition">
                                    <i class="fas fa-edit"></i> Edit
                                </a>
                                <form action="{{ route('guru.nilai.destroy', $n->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus nilai ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 transition">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-gray-500 italic">
                                Belum ada data nilai ditemukan.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>

</html>