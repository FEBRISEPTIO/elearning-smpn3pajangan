<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengumpulan Tugas - {{ $tugas->judul_tugas }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-50">
    @include('guru.partials.header')

    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('guru.tugas.show', $tugas) }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Tugas
                </a>
            </div>

            <!-- Header -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">Pengumpulan Tugas</h1>
                        <p class="text-gray-600 mt-1">{{ $tugas->judul_tugas }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            <i class="fas fa-users mr-1"></i> Kelas: {{ $tugas->kelas->kelas ?? '-' }} |
                            <i class="fas fa-book ml-3 mr-1"></i> Mapel: {{ $tugas->mataPelajaran->nama_mapel ?? '-' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Deadline</p>
                        <p
                            class="font-medium {{ now()->greaterThan($tugas->deadline) ? 'text-red-600' : 'text-green-600' }}">
                            {{ $tugas->deadline->format('d M Y, H:i') }}
                        </p>
                        @if (now()->greaterThan($tugas->deadline))
                            <span class="text-xs text-red-500">(Sudah lewat)</span>
                        @endif
                    </div>
                </div>

                <!-- Statistik -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="bg-blue-100 rounded-full p-3 mr-4">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Total Siswa</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="bg-green-100 rounded-full p-3 mr-4">
                                <i class="fas fa-check-circle text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Sudah Kumpul</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $sudahKumpul }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ number_format(($sudahKumpul / $totalSiswa) * 100, 0) }}%
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="bg-yellow-100 rounded-full p-3 mr-4">
                                <i class="fas fa-clock text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Belum Kumpul</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $belumKumpul }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <div class="bg-red-100 rounded-full p-3 mr-4">
                                <i class="fas fa-exclamation-triangle text-red-600"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Terlambat</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $terlambat }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Pengumpulan -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 border-b bg-gray-50">
                    <h2 class="text-lg font-semibold text-gray-900">Daftar Pengumpulan Siswa</h2>
                    <p class="text-sm text-gray-600 mt-1">Daftar tugas yang sudah dikumpulkan siswa</p>
                </div>

                @if ($pengumpulan->count() > 0)
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
                                        Waktu Pengumpulan
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        File
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
                                @foreach ($pengumpulan as $item)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                                    <i class="fas fa-user-graduate text-gray-600"></i>
                                                </div>
                                                <div>
                                                    <div class="font-medium text-gray-900">
                                                        {{ $item->siswa->nama ?? 'Siswa' }}</div>
                                                    <div class="text-sm text-gray-500">
                                                        NISN: {{ $item->siswa->nisn ?? '-' }} |
                                                        NIS: {{ $item->siswa->nis ?? '-' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                {{ $item->submitted_at->format('d M Y, H:i') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $item->submitted_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->status == 'late')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-clock mr-1"></i> Terlambat
                                                </span>
                                            @elseif($item->status == 'graded')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i> Sudah Dinilai
                                                </span>
                                                @if ($item->nilai)
                                                    <div class="text-sm font-medium text-gray-900 mt-1">
                                                        Nilai: {{ $item->nilai }}
                                                    </div>
                                                @endif
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-paper-plane mr-1"></i> Terkirim
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if ($item->file)
                                                @php
                                                    $extension = pathinfo($item->file, PATHINFO_EXTENSION);
                                                    $iconClass =
                                                        [
                                                            'pdf' => 'fas fa-file-pdf text-red-500',
                                                            'doc' => 'fas fa-file-word text-blue-500',
                                                            'docx' => 'fas fa-file-word text-blue-500',
                                                            'ppt' => 'fas fa-file-powerpoint text-orange-500',
                                                            'pptx' => 'fas fa-file-powerpoint text-orange-500',
                                                            'jpg' => 'fas fa-file-image text-green-500',
                                                            'jpeg' => 'fas fa-file-image text-green-500',
                                                            'png' => 'fas fa-file-image text-green-500',
                                                            'zip' => 'fas fa-file-archive text-purple-500',
                                                            'rar' => 'fas fa-file-archive text-purple-500',
                                                        ][strtolower($extension)] ?? 'fas fa-file text-gray-500';
                                                @endphp
                                                <div class="flex items-center">
                                                    <div class="{{ $iconClass }} text-lg mr-2"></div>
                                                    <div>
                                                        <div class="text-sm text-gray-900">
                                                            {{ $item->getFileNameAttribute() }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ strtoupper($extension) }} •
                                                            {{ round(filesize(storage_path('app/public/' . $item->file)) / 1024) }}
                                                            KB
                                                        </div>
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-gray-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-900 max-w-xs">
                                                {{ $item->keterangan ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex items-center space-x-2">
                                                @if ($item->file)
                                                    <a href="{{ Storage::url($item->file) }}" target="_blank"
                                                        class="text-blue-600 hover:text-blue-900"
                                                        title="Download File">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                @endif

                                                <button onclick="openNilaiModal({{ $item->id }})"
                                                    class="text-green-600 hover:text-green-900" title="Beri Nilai">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('guru.tugas.hapus-pengumpulan', $item->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Hapus pengumpulan ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900"
                                                        title="Hapus Pengumpulan">
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

                    <!-- Pagination -->
                    @if ($pengumpulan->hasPages())
                        <div class="px-6 py-4 border-t">
                            {{ $pengumpulan->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-inbox text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Belum ada pengumpulan</h3>
                        <p class="text-gray-500">Belum ada siswa yang mengumpulkan tugas ini.</p>
                    </div>
                @endif
            </div>

            <!-- Daftar Siswa Belum Kumpul -->
            @if ($belumKumpul > 0)
                <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b bg-yellow-50">
                        <h2 class="text-lg font-semibold text-gray-900">Siswa Belum Mengumpulkan ({{ $belumKumpul }})
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Siswa yang belum mengumpulkan tugas</p>
                    </div>
                    <div class="p-6">
                        @php
                            // Ambil siswa yang belum mengumpulkan
                            $siswaBelumKumpul = Siswa::where('kelas_id', $tugas->kelas_id)
                                ->whereNotIn('nisn', function ($query) use ($tugas) {
                                    $query->select('nisn')->from('pengumpulan_tugas')->where('tugas_id', $tugas->id);
                                })
                                ->get();
                        @endphp

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach ($siswaBelumKumpul as $siswa)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 bg-gray-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-gray-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-medium text-gray-900">{{ $siswa->nama }}</div>
                                            <div class="text-sm text-gray-500">
                                                NISN: {{ $siswa->nisn }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <!-- Modal untuk Beri Nilai -->
    <div id="nilaiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Beri Nilai</h3>
                <form id="nilaiForm" method="POST">
                    @csrf
                    <input type="hidden" name="pengumpulan_id" id="pengumpulan_id">

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nilai (0-100)</label>
                        <input type="number" name="nilai" id="nilai"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md" min="0" max="100"
                            step="0.1" required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Feedback</label>
                        <textarea name="feedback" id="feedback" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md"
                            placeholder="Berikan masukan untuk siswa..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeNilaiModal()"
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            Simpan Nilai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Modal functions
        function openNilaiModal(pengumpulanId) {
            document.getElementById('pengumpulan_id').value = pengumpulanId;
            document.getElementById('nilaiForm').action = "{{ route('guru.tugas.nilai', ['tugas' => $tugas->id]) }}";
            document.getElementById('nilaiModal').classList.remove('hidden');
        }

        function closeNilaiModal() {
            document.getElementById('nilaiModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('nilaiModal');
            if (event.target == modal) {
                closeNilaiModal();
            }
        }
    </script>
</body>

</html>
