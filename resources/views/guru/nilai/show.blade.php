<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Nilai {{ $siswa->nama }} - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-gray-100 min-h-screen">
    @include('guru.partials.header')

    <main class="md:ml-64 min-h-screen p-4 md:p-6 text-sm">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
                <div class="flex items-center gap-4">
                    <div class="bg-blue-100 p-4 rounded-full text-blue-600">
                        <i class="fas fa-user-graduate text-2xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $siswa->nama }}</h1>
                        <p class="text-gray-600">NISN: {{ $siswa->nisn }} | Kelas: {{ $siswa->kelas->nama_kelas ?? $siswa->kelas->kelas }}</p>
                    </div>
                </div>
                <div class="flex gap-2">
                    <button onclick="window.print()" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200 transition">
                        <i class="fas fa-print mr-1"></i> Cetak
                    </button>
                    <a href="{{ route('guru.nilai.index') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 text-center">
                    <div class="text-blue-600 font-bold text-lg mb-1">Rata-rata Nilai</div>
                    <div class="text-3xl font-black text-blue-900">{{ number_format($rataRata, 1) }}</div>
                </div>
                <div class="bg-green-50 p-4 rounded-xl border border-green-100 text-center">
                    <div class="text-green-600 font-bold text-lg mb-1">Nilai Tertinggi</div>
                    <div class="text-3xl font-black text-green-900">{{ $nilais->max('nilai') ?? 0 }}</div>
                </div>
                <div class="bg-purple-50 p-4 rounded-xl border border-purple-100 text-center">
                    <div class="text-purple-600 font-bold text-lg mb-1">Total Penilaian</div>
                    <div class="text-3xl font-black text-purple-900">{{ $nilais->count() }}</div>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Grafik Perkembangan Nilai</h3>
                <div class="h-64">
                    <canvas id="nilaiChart"></canvas>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-bold text-gray-900 mb-4 border-b pb-2">Riwayat Nilai</h3>
                <div class="overflow-x-auto border rounded-xl overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500">
                            <tr>
                                <th class="px-6 py-3 text-left">Mapel</th>
                                <th class="px-6 py-3 text-left">Tugas</th>
                                <th class="px-6 py-3 text-center">Nilai</th>
                                <th class="px-6 py-3 text-left">Keterangan</th>
                                <th class="px-6 py-3 text-left">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($nilais as $n)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-bold text-gray-900">{{ $n->mapel->nama_mapel }}</td>
                                <td class="px-6 py-4 text-gray-600">{{ $n->tugas->judul_tugas ?? '-' }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-2 py-1 rounded font-bold {{ $n->nilai >= 75 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $n->nilai }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-600 text-xs italic">{{ $n->keterangan ?? '-' }}</td>
                                <td class="px-6 py-4 text-gray-500 text-xs">{{ $n->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('nilaiChart').getContext('2d');
        const data = @json($nilais - > reverse() - > values());

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(n => n.tugas ? n.tugas.judul : n.mapel.nama_mapel),
                datasets: [{
                    label: 'Nilai',
                    data: data.map(n => n.nilai),
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    tension: 0.3,
                    fill: true,
                    pointBackgroundColor: '#2563eb',
                    pointRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: {
                            color: '#f3f4f6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
</body>

</html>