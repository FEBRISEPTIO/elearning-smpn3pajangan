<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai {{ $siswa->nama }} - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        @media print {
            @page {
                margin: 0.5in;
            }

            body {
                font-size: 12pt;
            }

            .no-print {
                display: none !important;
            }

            .bg-white {
                background-color: white !important;
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }

            th,
            td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }

            th {
                background-color: #f5f5f5;
            }

            .chart-container {
                page-break-inside: avoid;
            }

            h1,
            h2,
            h3 {
                page-break-after: avoid;
            }

            a {
                text-decoration: none;
                color: inherit;
            }
        }
    </style>
    
</head>

<body class="bg-gray-100">
    @include('guru.partials.header')

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Navigation -->
            <div class="mb-6">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('guru.nilai.index') }}"
                                class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600">
                                <i class="fas fa-chart-bar mr-2"></i>
                                Data Nilai
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2"></i>
                                <span class="text-sm font-medium text-gray-500">{{ $siswa->nama }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>

            <!-- Student Info Card -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-6 py-5 border-b border-gray-200">
                    <div class="flex justify-between items-start">
                        <div class="flex items-center">
                            <div
                                class="flex-shrink-0 h-16 w-16 bg-purple-100 rounded-full flex items-center justify-center mr-4">
                                <i class="fas fa-user-graduate text-purple-600 text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-2xl font-bold text-gray-900">{{ $siswa->nama }}</h1>
                                <div class="mt-1 flex flex-wrap items-center text-sm text-gray-500">
                                    <span class="mr-4">
                                        <i class="fas fa-id-card mr-1"></i>
                                        NISN: {{ $siswa->nisn }}
                                    </span>
                                    <span class="mr-4">
                                        <i class="fas fa-users-class mr-1"></i>
                                        Kelas: {{ $siswa->kelas->nama_kelas ?? 'N/A' }}
                                    </span>
                                    <span>
                                        <i class="fas fa-venus-mars mr-1"></i>
                                        {{ $siswa->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="showAddNilaiModal()"
                                class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700">
                                <i class="fas fa-plus mr-2"></i> Tambah Nilai
                            </button>
                            <a href="{{ route('guru.nilai.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                                <i class="fas fa-arrow-left mr-2"></i> Kembali
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="px-6 py-5 bg-gray-50">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-white rounded-lg shadow p-4">
                            <div class="flex items-center">
                                <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-calculator text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Rata-rata</p>
                                    <p
                                        class="text-xl font-bold {{ $rata_rata >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($rata_rata, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-4">
                            <div class="flex items-center">
                                <div class="bg-green-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-chart-line text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Tertinggi</p>
                                    <p class="text-xl font-bold text-green-600">
                                        {{ $nilai->max('nilai') ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-4">
                            <div class="flex items-center">
                                <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-chart-area text-yellow-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Terendah</p>
                                    <p class="text-xl font-bold text-yellow-600">
                                        {{ $nilai->min('nilai') ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow p-4">
                            <div class="flex items-center">
                                <div
                                    class="{{ $rata_rata >= 75 ? 'bg-green-100' : 'bg-red-100' }} p-2 rounded-lg mr-3">
                                    <i
                                        class="fas {{ $rata_rata >= 75 ? 'fa-check-circle text-green-600' : 'fa-exclamation-circle text-red-600' }}"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p
                                        class="text-xl font-bold {{ $rata_rata >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $rata_rata >= 75 ? 'Tuntas' : 'Remedial' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chart Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Progress Chart -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Perkembangan Nilai</h3>
                    <div class="h-64">
                        <canvas id="nilaiChart"></canvas>
                    </div>
                </div>

                <!-- Score Distribution -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Nilai</h3>
                    <div class="h-64">
                        <canvas id="distributionChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Nilai Table -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg font-semibold text-gray-900">
                            <i class="fas fa-list-alt mr-2"></i>
                            Daftar Nilai
                        </h3>
                        <div class="flex items-center space-x-2">
                            <div class="relative">
                                <input type="text" placeholder="Cari nilai..." id="searchNilai"
                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                            </div>
                            <select id="filterJenis"
                                class="border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
                                <option value="">Semua Jenis</option>
                                <option value="materi">Materi</option>
                                <option value="tugas">Tugas</option>
                            </select>
                        </div>
                    </div>
                </div>

                @if ($nilai->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="nilaiTable">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Jenis
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Deskripsi
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Tanggal
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Nilai
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Keterangan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($nilai as $item)
                                    <tr class="hover:bg-gray-50 nilai-row" data-jenis="{{ $item->jenis }}">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                {{ $item->jenis == 'materi' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                                <i
                                                    class="fas {{ $item->jenis == 'materi' ? 'fa-book mr-1' : 'fa-tasks mr-1' }}"></i>
                                                {{ ucfirst($item->jenis) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                @if ($item->jenis == 'materi' && $item->materi)
                                                    {{ $item->materi->judul }}
                                                @elseif($item->jenis == 'tugas' && $item->tugas)
                                                    {{ $item->tugas->judul }}
                                                @else
                                                    {{ $item->keterangan ?? 'Tidak ada deskripsi' }}
                                                @endif
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                @if ($item->jenis == 'materi' && $item->materi)
                                                    Materi Pembelajaran
                                                @elseif($item->jenis == 'tugas' && $item->tugas)
                                                    Tugas
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $item->created_at->format('d M Y') }}
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                {{ $item->created_at->format('H:i') }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                                    <div class="{{ $item->nilai >= 75 ? 'bg-green-600' : ($item->nilai >= 50 ? 'bg-yellow-600' : 'bg-red-600') }} h-2 rounded-full"
                                                        style="width: {{ $item->nilai }}%"></div>
                                                </div>
                                                <span
                                                    class="font-bold {{ $item->nilai >= 75 ? 'text-green-600' : ($item->nilai >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                                    {{ $item->nilai }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->nilai >= 75)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <i class="fas fa-check mr-1"></i> Tuntas
                                                </span>
                                            @elseif($item->nilai >= 50)
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    <i class="fas fa-exclamation mr-1"></i> Cukup
                                                </span>
                                            @else
                                                <span
                                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    <i class="fas fa-times mr-1"></i> Remedial
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900">
                                                {{ Str::limit($item->keterangan, 50) ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick="showEditNilaiModal({{ $item->id }})"
                                                    class="text-blue-600 hover:text-blue-900" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('guru.nilai.update', $item->id) }}"
                                                    method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        onclick="return confirm('Hapus nilai ini?')"
                                                        class="text-red-600 hover:text-red-900" title="Hapus">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
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
                            <i class="fas fa-chart-bar text-6xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada nilai</h3>
                        <p class="text-gray-600 mb-6">Tambahkan nilai pertama untuk siswa ini.</p>
                        <button onclick="showAddNilaiModal()"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-white hover:bg-purple-700">
                            <i class="fas fa-plus mr-2"></i> Tambah Nilai Pertama
                        </button>
                    </div>
                @endif

                <!-- Summary -->
                @if ($nilai->count() > 0)
                    <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                        <div class="flex justify-between items-center">
                            <div class="text-sm text-gray-700">
                                Menampilkan {{ $nilai->count() }} nilai
                            </div>
                            <div class="text-sm text-gray-700">
                                Rata-rata: <span class="font-bold">{{ number_format($rata_rata, 2) }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Export Options -->
            <div class="mt-6 flex justify-end">
                <div class="flex space-x-3">
                    <button onclick="exportToPDF()"
                        class="inline-flex items-center px-4 py-2 border border-red-300 rounded-md text-sm font-medium text-red-700 hover:bg-red-50">
                        <i class="fas fa-file-pdf mr-2"></i> Export PDF
                    </button>
                    <button onclick="exportToExcel()"
                        class="inline-flex items-center px-4 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 hover:bg-green-50">
                        <i class="fas fa-file-excel mr-2"></i> Export Excel
                    </button>
                    <button onclick="printNilai()"
                        class="inline-flex items-center px-4 py-2 border border-blue-300 rounded-md text-sm font-medium text-blue-700 hover:bg-blue-50">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                </div>
            </div>
        </div>
    </main>

    <!-- Add Nilai Modal -->
    <div id="addNilaiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Tambah Nilai</h3>
                    <button onclick="hideAddNilaiModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="addNilaiForm" method="POST" action="{{ route('guru.nilai.store', $siswa->id) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Nilai</label>
                        <select name="jenis" required id="jenisSelect"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500">
                            <option value="">Pilih Jenis</option>
                            <option value="materi">Materi</option>
                            <option value="tugas">Tugas</option>
                        </select>
                    </div>

                    <div class="mb-4" id="materiSelectContainer" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Materi</label>
                        <select name="referensi_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">Pilih Materi</option>
                            <!-- Materi options akan diisi via AJAX -->
                        </select>
                    </div>

                    <div class="mb-4" id="tugasSelectContainer" style="display: none;">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tugas</label>
                        <select name="referensi_id" class="w-full border border-gray-300 rounded-lg px-4 py-2">
                            <option value="">Pilih Tugas</option>
                            <!-- Tugas options akan diisi via AJAX -->
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

    <!-- Edit Nilai Modal -->
    <div id="editNilaiModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Edit Nilai</h3>
                    <button onclick="hideEditNilaiModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="editNilaiForm" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai</label>
                        <input type="number" name="nilai" min="0" max="100" step="0.1" required
                            id="editNilai"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                            placeholder="0-100">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" id="editKeterangan"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-purple-500"
                            placeholder="Opsional"></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" onclick="hideEditNilaiModal()"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg">
                            Perbarui
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Chart Data
        const nilaiData = @json($nilai->sortBy('created_at')->values());
        const labels = nilaiData.map(n => n.created_at.format('d M'));
        const nilaiValues = nilaiData.map(n => n.nilai);

        // Progress Chart
        const ctx1 = document.getElementById('nilaiChart').getContext('2d');
        const nilaiChart = new Chart(ctx1, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nilai',
                    data: nilaiValues,
                    borderColor: 'rgb(147, 51, 234)',
                    backgroundColor: 'rgba(147, 51, 234, 0.1)',
                    tension: 0.1,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });

        // Distribution Chart
        const ctx2 = document.getElementById('distributionChart').getContext('2d');

        // Count nilai by category
        const remedial = nilaiData.filter(n => n.nilai < 50).length;
        const cukup = nilaiData.filter(n => n.nilai >= 50 && n.nilai < 75).length;
        const tuntas = nilaiData.filter(n => n.nilai >= 75).length;

        const distributionChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Remedial (<50)', 'Cukup (50-74)', 'Tuntas (≥75)'],
                datasets: [{
                    data: [remedial, cukup, tuntas],
                    backgroundColor: [
                        'rgb(239, 68, 68)',
                        'rgb(234, 179, 8)',
                        'rgb(34, 197, 94)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Modal Functions
        function showAddNilaiModal() {
            document.getElementById('addNilaiModal').classList.remove('hidden');
        }

        function hideAddNilaiModal() {
            document.getElementById('addNilaiModal').classList.add('hidden');
        }

        function showEditNilaiModal(nilaiId) {
            // Find nilai data
            const nilai = nilaiData.find(n => n.id == nilaiId);
            if (nilai) {
                document.getElementById('editNilai').value = nilai.nilai;
                document.getElementById('editKeterangan').value = nilai.keterangan || '';
                document.getElementById('editNilaiForm').action = `/guru/nilai/${nilaiId}`;
                document.getElementById('editNilaiModal').classList.remove('hidden');
            }
        }

        function hideEditNilaiModal() {
            document.getElementById('editNilaiModal').classList.add('hidden');
        }

        // Filter jenis nilai change
        document.getElementById('jenisSelect').addEventListener('change', function(e) {
            const jenis = e.target.value;
            document.getElementById('materiSelectContainer').style.display = jenis === 'materi' ? 'block' : 'none';
            document.getElementById('tugasSelectContainer').style.display = jenis === 'tugas' ? 'block' : 'none';

            // Load data via AJAX
            if (jenis) {
                loadReferensiData(jenis);
            }
        });

        // Table filtering
        document.getElementById('filterJenis').addEventListener('change', function(e) {
            const filterValue = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.nilai-row');

            rows.forEach(row => {
                const jenis = row.getAttribute('data-jenis');
                if (filterValue === '' || jenis === filterValue) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Search functionality
        document.getElementById('searchNilai').addEventListener('keyup', function(e) {
            const searchValue = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.nilai-row');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Load referensi data via AJAX
        function loadReferensiData(jenis) {
            // Implement AJAX call to load materi/tugas data
            fetch(`/guru/api/${jenis}`)
                .then(response => response.json())
                .then(data => {
                    const select = document.getElementById(`${jenis}SelectContainer`).querySelector('select');
                    select.innerHTML = '<option value="">Pilih ' + jenis.charAt(0).toUpperCase() + jenis.slice(1) +
                        '</option>';

                    data.forEach(item => {
                        const option = document.createElement('option');
                        option.value = item.id;
                        option.textContent = item.judul || item.nama;
                        select.appendChild(option);
                    });
                });
        }

        // Export functions
        function exportToPDF() {
            alert('Fitur export PDF akan diimplementasi');
        }

        function exportToExcel() {
            alert('Fitur export Excel akan diimplementasi');
        }

        function printNilai() {
            window.print();
        }

        // Close modals on outside click
        window.onclick = function(event) {
            if (event.target.id === 'addNilaiModal') {
                hideAddNilaiModal();
            }
            if (event.target.id === 'editNilaiModal') {
                hideEditNilaiModal();
            }
        }
    </script>
</body>

</html>
