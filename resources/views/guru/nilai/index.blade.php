<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nilai Siswa - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    @include('guru.partials.header')

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Data Nilai Siswa</h1>
                <p class="text-gray-600">Kelola dan pantau nilai siswa</p>
            </div>

            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg"
                    role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Filter Controls -->
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <select id="kelasFilter"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
                            <option value="">Semua Kelas</option>
                            @foreach ($kelas as $kelasItem)
                                <option value="{{ $kelasItem->id }}">{{ $kelasItem->nama_kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Nilai</label>
                        <select id="jenisFilter"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
                            <option value="">Semua Jenis</option>
                            <option value="materi">Materi</option>
                            <option value="tugas">Tugas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Nilai</label>
                        <select id="rangeFilter"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
                            <option value="">Semua</option>
                            <option value="0-50">0 - 50</option>
                            <option value="51-70">51 - 70</option>
                            <option value="71-85">71 - 85</option>
                            <option value="86-100">86 - 100</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="filterNilai()"
                            class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700">
                            <i class="fas fa-filter mr-2"></i> Filter
                        </button>
                    </div>
                </div>
            </div>

            <!-- Summary Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-purple-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Total Siswa</p>
                            <p class="text-2xl font-bold">{{ $siswa->count() ?? 0 }}</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-blue-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-chart-bar text-blue-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Rata-rata Nilai</p>
                            <p class="text-2xl font-bold">
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
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex items-center">
                        <div class="bg-green-100 p-3 rounded-lg mr-4">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Nilai Tuntas</p>
                            <p class="text-2xl font-bold">
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
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                @if ($siswa && $siswa->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Siswa
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Kelas
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Rata-rata Nilai
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai Tertinggi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai Terendah
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($siswa as $item)
                                    @php
                                        $nilaiSiswa = $item->nilai;
                                        $rataRata = $nilaiSiswa->avg('nilai') ?? 0;
                                        $nilaiTertinggi = $nilaiSiswa->max('nilai') ?? 0;
                                        $nilaiTerendah = $nilaiSiswa->min('nilai') ?? 0;
                                        $isTuntas = $rataRata >= 75;
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-user-graduate text-purple-600"></i>
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
                                                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $item->kelas->nama_kelas ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                                    <div class="bg-purple-600 h-2 rounded-full"
                                                        style="width: {{ $rataRata }}%"></div>
                                                </div>
                                                <span
                                                    class="font-bold {{ $rataRata >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ round($rataRata, 2) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $nilaiTertinggi }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span
                                                class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $nilaiTerendah }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($isTuntas)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> Tuntas
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-exclamation mr-1"></i> Remedial
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('guru.nilai.show', $item->id) }}"
                                                    class="text-blue-600 hover:text-blue-900 px-3 py-1 rounded-lg border border-blue-200 hover:bg-blue-50"
                                                    title="Detail Nilai">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </a>
                                                <button
                                                    onclick="showAddNilaiModal('{{ $item->id }}', '{{ $item->nama }}')"
                                                    class="text-green-600 hover:text-green-900 px-3 py-1 rounded-lg border border-green-200 hover:bg-green-50"
                                                    title="Tambah Nilai">
                                                    <i class="fas fa-plus mr-1"></i> Nilai
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-user-graduate text-6xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada data siswa</h3>
                        <p class="text-gray-600 mb-6">Siswa akan muncul setelah terdaftar di kelas yang Anda ajar.</p>
                    </div>
                @endif
            </div>

            <!-- Nilai Charts (Optional) -->
            <div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Nilai</h3>
                    <div class="h-64 flex items-center justify-center">
                        <p class="text-gray-500">Chart distribusi nilai akan muncul di sini</p>
                    </div>
                </div>
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Top 5 Siswa</h3>
                    <div class="space-y-3">
                        @php
                            $topSiswa = $siswa
                                ->sortByDesc(function ($s) {
                                    return $s->nilai->avg('nilai') ?? 0;
                                })
                                ->take(5);
                        @endphp
                        @foreach ($topSiswa as $index => $siswaItem)
                            @php
                                $rata = $siswaItem->nilai->avg('nilai') ?? 0;
                            @endphp
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div
                                        class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center mr-3">
                                        <span class="font-bold text-purple-600">{{ $index + 1 }}</span>
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $siswaItem->nama }}</p>
                                        <p class="text-xs text-gray-600">{{ $siswaItem->kelas->nama_kelas ?? 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="font-bold text-purple-600">{{ round($rata, 2) }}</p>
                                    <p class="text-xs text-gray-500">Rata-rata</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Nilai Modal -->
    <div id="addNilaiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4" id="modalTitle">Tambah Nilai</h3>
                <form id="addNilaiForm" method="POST">
                    @csrf
                    <input type="hidden" id="siswa_id" name="siswa_id">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Nilai</label>
                        <select name="jenis" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
                            <option value="">Pilih Jenis</option>
                            <option value="materi">Materi</option>
                            <option value="tugas">Tugas</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai</label>
                        <input type="number" name="nilai" min="0" max="100" step="0.1" required
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                            placeholder="0-100">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                            placeholder="Opsional"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="hideAddNilaiModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg">
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showAddNilaiModal(siswaId, siswaNama) {
            document.getElementById('siswa_id').value = siswaId;
            document.getElementById('modalTitle').textContent = 'Tambah Nilai - ' + siswaNama;
            document.getElementById('addNilaiForm').action = `/guru/nilai/${siswaId}`;
            document.getElementById('addNilaiModal').classList.remove('hidden');
        }

        function hideAddNilaiModal() {
            document.getElementById('addNilaiModal').classList.add('hidden');
        }

        function filterNilai() {
            const kelas = document.getElementById('kelasFilter').value;
            const jenis = document.getElementById('jenisFilter').value;
            const range = document.getElementById('rangeFilter').value;

            // Implement filter logic here
            console.log('Filtering:', {
                kelas,
                jenis,
                range
            });
        }
    </script>
</body>

</html>
