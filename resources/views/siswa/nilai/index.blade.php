<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nilai Siswa - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
    @include('siswa.partials.header')

    <!-- Main Content -->
    <main class="md:ml-64 min-h-screen p-4 md:p-6 transition-all duration-300">
        <!-- Header -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Nilai & Rapor</h1>
                <p class="text-gray-600 mt-1">Lihat nilai tugas yang sudah dinilai oleh guru</p>
            </div>
            <a href="{{ route('siswa.dashboard') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Dashboard
            </a>
        </div>

        <!-- Info Siswa -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex items-center">
                <div
                    class="flex-shrink-0 h-12 w-12 bg-gradient-to-r from-blue-500 to-blue-600 rounded-full flex items-center justify-center mr-4">
                    <i class="fas fa-user-graduate text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $siswa->nama }}</h2>
                    <p class="text-sm text-gray-600">
                        NISN: {{ $siswa->nisn }} |
                        Kelas: {{ $siswa->kelas->kelas ?? 'Belum ada kelas' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Statistik Nilai -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center">
                    <div class="bg-blue-100 rounded-full p-3 mr-4">
                        <i class="fas fa-check-circle text-blue-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Total Nilai</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalNilai }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center">
                    <div class="bg-green-100 rounded-full p-3 mr-4">
                        <i class="fas fa-chart-line text-green-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Rata-rata</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($rataRata, 2) ?? '0' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center">
                    <div class="bg-yellow-100 rounded-full p-3 mr-4">
                        <i class="fas fa-trophy text-yellow-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nilai Tertinggi</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($nilaiTertinggi, 2) ?? '0' }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-4">
                <div class="flex items-center">
                    <div class="bg-red-100 rounded-full p-3 mr-4">
                        <i class="fas fa-chart-area text-red-600"></i>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Nilai Terendah</p>
                        <p class="text-2xl font-bold text-gray-900">
                            {{ number_format($nilaiTerendah, 2) ?? '0' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <form method="GET" action="{{ route('siswa.nilai.index') }}">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filter Mata Pelajaran</label>
                        <select name="mapel" onchange="this.form.submit()"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            <option value="">Semua Mata Pelajaran</option>
                            @foreach ($mapels as $mapel)
                                <option value="{{ $mapel->id }}"
                                    {{ request('mapel') == $mapel->id ? 'selected' : '' }}>
                                    {{ $mapel->nama_mapel }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mt-4 md:mt-0">
                        <button type="button" onclick="window.location.href='{{ route('siswa.nilai.index') }}'"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-3 rounded-lg text-sm font-medium inline-flex items-center transition">
                            Reset Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Daftar Nilai -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b bg-gray-50">
                <h2 class="text-lg font-semibold text-gray-900">Daftar Nilai Tugas</h2>
                <p class="text-sm text-gray-600 mt-1">Tugas yang sudah dinilai oleh guru</p>
            </div>

            @if ($pengumpulan->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tugas
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Mata Pelajaran
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
                                    Tanggal Penilaian
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($pengumpulan as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900">{{ $item->tugas->judul_tugas }}
                                        </div>
                                        <div class="text-sm text-gray-500 mt-1">
                                            <i class="fas fa-user-tie mr-1"></i>
                                            {{ $item->tugas->guru->nama ?? 'Guru' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <i class="fas fa-book mr-1"></i>
                                            {{ $item->tugas->mataPelajaran->nama_mapel ?? '-' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div
                                                class="text-2xl font-bold {{ $item->nilai >= 80 ? 'text-green-600' : ($item->nilai >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                                {{ number_format($item->nilai, 2) }}
                                            </div>
                                            <div class="ml-2">
                                                @if ($item->nilai >= 80)
                                                    <span class="text-xs text-green-500">(Sangat Baik)</span>
                                                @elseif($item->nilai >= 60)
                                                    <span class="text-xs text-yellow-500">(Cukup)</span>
                                                @else
                                                    <span class="text-xs text-red-500">(Perlu Perbaikan)</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if ($item->status == 'late')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-clock mr-1"></i> Terlambat
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i> Tepat Waktu
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">
                                            {{ $item->updated_at->format('d M Y, H:i') }}
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            {{ $item->updated_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <a href="{{ route('siswa.nilai.show', $item->id) }}"
                                                class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-50 rounded"
                                                title="Lihat Detail">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if ($item->file)
                                                <a href="{{ Storage::url($item->file) }}" target="_blank"
                                                    class="text-green-600 hover:text-green-900 p-2 hover:bg-green-50 rounded"
                                                    title="Download File Tugas">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if ($pengumpulan->hasPages())
                    <div class="px-6 py-4 border-t">
                        {{ $pengumpulan->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-chart-bar text-gray-400 text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada nilai</h3>
                    <p class="text-gray-500">Belum ada tugas yang dinilai oleh guru.</p>
                </div>
            @endif
        </div>

        <!-- Chart Section -->
        @if ($totalNilai > 0)
            <div class="mt-6 bg-white rounded-xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Grafik Nilai per Mata Pelajaran</h3>
                <div class="h-64">
                    <canvas id="nilaiChart"></canvas>
                </div>
            </div>
        @endif

        <!-- Tips -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-amber-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips Meningkatkan Nilai:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Perhatikan feedback dari guru pada setiap tugas</li>
                        <li>• Submit tugas sebelum deadline untuk menghindari pengurangan nilai</li>
                        <li>• Perbaiki tugas yang nilainya rendah berdasarkan feedback</li>
                        <li>• Buat jadwal belajar yang konsisten untuk setiap mata pelajaran</li>
                        <li>• Diskusikan kesulitan belajar dengan guru atau teman sekelas</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>

    <script>
        // Chart
        @if ($totalNilai > 0)
            fetch("{{ route('siswa.nilai.chart') }}")
                .then(response => response.json())
                .then(data => {
                    const ctx = document.getElementById('nilaiChart').getContext('2d');
                    const labels = data.map(item => item.mapel);
                    const averages = data.map(item => item.rata_rata);

                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Rata-rata Nilai',
                                data: averages,
                                backgroundColor: [
                                    'rgba(59, 130, 246, 0.5)',
                                    'rgba(16, 185, 129, 0.5)',
                                    'rgba(245, 158, 11, 0.5)',
                                    'rgba(139, 92, 246, 0.5)',
                                    'rgba(239, 68, 68, 0.5)'
                                ],
                                borderColor: [
                                    'rgb(59, 130, 246)',
                                    'rgb(16, 185, 129)',
                                    'rgb(245, 158, 11)',
                                    'rgb(139, 92, 246)',
                                    'rgb(239, 68, 68)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    title: {
                                        display: true,
                                        text: 'Nilai (0-100)'
                                    }
                                }
                            }
                        }
                    });
                });
        @endif

        // Auto-hide flash messages
        setTimeout(() => {
            const flashMessages = document.querySelectorAll('.bg-green-50, .bg-red-50');
            flashMessages.forEach(msg => {
                msg.style.transition = 'opacity 0.5s';
                msg.style.opacity = '0';
                setTimeout(() => msg.remove(), 500);
            });
        }, 3000);
    </script>
</body>

</html>
