<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nilai Siswa - E-Learning</title>
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
                        <i class="fas fa-chart-bar mr-2"></i>
                        Data Nilai Siswa
                    </h1>
                    <p class="text-gray-600 mt-1">
                        Kelola dan pantau nilai siswa berdasarkan tugas yang diberikan
                    </p>
                </div>
                <a href="{{ route('guru.tugas.index') }}"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                    <i class="fas fa-tasks mr-2"></i>Lihat Tugas
                </a>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <div class="flex">
                        <div class="shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

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

            <!-- Filter Controls -->
            <div class="bg-gray-50 border border-gray-200 rounded-xl p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Data Nilai</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <select id="filter_kelas" onchange="filterNilai()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelas as $kelasItem)
                                <option value="{{ $kelasItem->id }}"
                                    {{ request('kelas_id') == $kelasItem->id ? 'selected' : '' }}>
                                    {{ $kelasItem->kelas ?? $kelasItem->nama_kelas }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Nilai</label>
                        <select id="filter_status" onchange="filterNilai()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Semua Status</option>
                            <option value="tuntas" {{ request('status') == 'tuntas' ? 'selected' : '' }}>Tuntas</option>
                            <option value="remedial" {{ request('status') == 'remedial' ? 'selected' : '' }}>Remedial
                            </option>
                            <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum Dinilai
                            </option>
                        </select>
                    </div>

                    <div class="flex items-end space-x-2">
                        <a href="{{ route('guru.nilai.index') }}"
                            class="w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 rounded-lg text-sm font-medium inline-flex items-center justify-center transition">
                            <i class="fas fa-redo mr-2"></i> Reset Filter
                        </a>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Siswa</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $siswa->count() ?? 0 }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-tasks text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Tugas</p>
                            <p class="text-2xl font-bold text-gray-900">
                                @php
                                    $totalTugas = 0;
                                    foreach ($siswa as $s) {
                                        $totalTugas += $s->nilai->unique('tugas_id')->count();
                                    }
                                @endphp
                                {{ $totalTugas }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="bg-amber-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-chart-line text-amber-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Rata-rata Nilai</p>
                            <p class="text-2xl font-bold text-gray-900">
                                @php
                                    $totalNilai = 0;
                                    $countNilai = 0;
                                    foreach ($siswa as $s) {
                                        foreach ($s->nilai as $n) {
                                            $totalNilai += $n->nilai;
                                            $countNilai++;
                                        }
                                    }
                                    $rataRata = $countNilai > 0 ? round($totalNilai / $countNilai, 2) : 0;
                                @endphp
                                {{ $rataRata }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-check-circle text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Siswa Tuntas</p>
                            <p class="text-2xl font-bold text-gray-900">
                                @php
                                    $tuntas = 0;
                                    foreach ($siswa as $s) {
                                        $rataSiswa = $s->nilai->avg('nilai');
                                        if ($rataSiswa >= 75) {
                                            $tuntas++;
                                        }
                                    }
                                @endphp
                                {{ $tuntas }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden mb-8">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-list-ol mr-2"></i>Daftar Nilai Siswa
                        </h3>
                        <div class="mt-2 md:mt-0 text-sm text-gray-600">
                            Menampilkan {{ $siswa->count() }} siswa
                        </div>
                    </div>
                </div>

                @if ($siswa && $siswa->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-user-graduate mr-1"></i> Siswa
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-school mr-1"></i> Kelas
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-tasks mr-1"></i> Jumlah Tugas
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-chart-line mr-1"></i> Rata-rata
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-info-circle mr-1"></i> Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <i class="fas fa-cog mr-1"></i> Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($siswa as $item)
                                    @php
                                        $nilaiSiswa = $item->nilai;
                                        $jumlahTugas = $nilaiSiswa->unique('tugas_id')->count();
                                        $rataRata = $nilaiSiswa->avg('nilai') ?? 0;
                                        $isTuntas = $rataRata >= 75;
                                        $tugasIds = $nilaiSiswa->pluck('tugas_id')->unique();
                                    @endphp
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-user text-blue-600"></i>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->nama }}
                                                    </div>
                                                    <div class="text-xs text-gray-500">
                                                        NISN: {{ $item->nisn }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                {{ $item->kelas->nama_kelas ?? $item->kelas->kelas }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                                {{ $jumlahTugas }} Tugas
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                                    <div class="bg-blue-600 h-2 rounded-full transition-all duration-300"
                                                        style="width: {{ min($rataRata, 100) }}%"></div>
                                                </div>
                                                <span
                                                    class="font-bold {{ $rataRata >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ round($rataRata, 2) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($jumlahTugas > 0)
                                                @if ($isTuntas)
                                                    <span
                                                        class="px-3 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 inline-flex items-center">
                                                        <i class="fas fa-check mr-1"></i> Tuntas
                                                    </span>
                                                @else
                                                    <span
                                                        class="px-3 py-1 text-xs font-medium rounded-full bg-amber-100 text-amber-800 inline-flex items-center">
                                                        <i class="fas fa-exclamation mr-1"></i> Remedial
                                                    </span>
                                                @endif
                                            @else
                                                <span
                                                    class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 inline-flex items-center">
                                                    <i class="fas fa-clock mr-1"></i> Belum Dinilai
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('guru.nilai.show', $item->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 px-3 py-2 rounded-lg border border-blue-200 hover:bg-blue-50 transition inline-flex items-center"
                                                    title="Detail Nilai">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </a>
                                                <a href="{{ route('guru.nilai.byKelas', $item->kelas_id) }}?siswa_id={{ $item->id }}"
                                                    class="text-green-600 hover:text-green-900 px-3 py-2 rounded-lg border border-green-200 hover:bg-green-50 transition inline-flex items-center"
                                                    title="Tambah Nilai">
                                                    <i class="fas fa-plus mr-1"></i> Nilai
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-300 mb-4">
                            <i class="fas fa-chart-bar text-6xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data nilai</h3>
                        <p class="text-gray-600 mb-6">Mulai dengan membuat tugas terlebih dahulu.</p>
                        <div class="space-x-3">
                            <a href="{{ route('guru.tugas.create') }}"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                                <i class="fas fa-plus mr-2"></i> Buat Tugas
                            </a>
                        </div>
                    </div>
                @endif
            </div>


            <!-- Tips Section -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
                <div class="flex items-start">
                    <i class="fas fa-lightbulb text-amber-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800 mb-2">Panduan Pengelolaan Nilai:</h4>
                        <ul class="text-sm text-blue-700 space-y-1">
                            <li>• Nilai diberikan berdasarkan tugas yang telah dibuat</li>
                            <li>• Klik "Detail" untuk melihat nilai per tugas siswa</li>
                            <li>• Klik "Nilai" untuk memberikan nilai pada tugas tertentu</li>
                            <li>• Pilih kelas untuk melihat nilai siswa dalam kelas tersebut</li>
                            <li>• Gunakan filter untuk mencari siswa dengan status tertentu</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        function filterNilai() {
            const kelas = document.getElementById('filter_kelas').value;
            const status = document.getElementById('filter_status').value;

            let params = new URLSearchParams();

            if (kelas) params.append('kelas_id', kelas);
            if (status) params.append('status', status);

            const queryString = params.toString();
            window.location.href = window.location.pathname + (queryString ? '?' + queryString : '');
        }
    </script>
</body>

</html>
