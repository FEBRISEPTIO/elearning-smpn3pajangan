<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Nilai - E-Learning</title>
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
    @include('siswa.partials.header')

    <!-- Main Content -->
    <main class="md:ml-64 min-h-screen p-4 md:p-6 transition-all duration-300">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('siswa.nilai.index') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i>Kembali ke Daftar Nilai
            </a>
        </div>

        <!-- Card Detail Nilai -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                <h1 class="text-xl font-bold text-white">Detail Penilaian</h1>
            </div>

            <!-- Content -->
            <div class="p-6">
                <!-- Info Tugas -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Penilaian</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Judul Penilaian</p>
                            <p class="font-medium text-gray-900 mt-1">
                                {{ $nilai->tugas->judul_tugas ?? 'Penilaian Langsung' }}
                            </p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Mata Pelajaran</p>
                            <p class="font-medium text-gray-900 mt-1">
                                <i class="fas fa-book mr-1 text-blue-500"></i>
                                {{ $nilai->mapel->nama_mapel ?? ($nilai->tugas->mataPelajaran->nama_mapel ?? '-') }}
                            </p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Guru Pengampu</p>
                            <p class="font-medium text-gray-900 mt-1">
                                <i class="fas fa-user-tie mr-1 text-green-500"></i>
                                {{ $nilai->guru->nama ?? ($nilai->tugas->guru->nama ?? 'Guru') }}
                            </p>
                        </div>
                        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                            <p class="text-sm text-gray-500">Tanggal Penilaian</p>
                            <p class="font-medium text-gray-900 mt-1">
                                <i class="fas fa-calendar-alt mr-1 text-purple-500"></i>
                                {{ $nilai->updated_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>

                @if($pengumpulan)
                <!-- Timeline (Hanya untuk tugas) -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline Tugas</h2>
                    <div class="relative">
                        <!-- Timeline line -->
                        <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-blue-200"></div>

                        <!-- Items -->
                        <div class="space-y-8">
                            <!-- Submit -->
                            <div class="relative pl-12">
                                <div class="absolute left-2 top-0 w-4 h-4 bg-blue-500 rounded-full"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Submit Tugas</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $pengumpulan->submitted_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Dinilai -->
                            <div class="relative pl-12">
                                <div class="absolute left-2 top-0 w-4 h-4 bg-green-500 rounded-full"></div>
                                <div>
                                    <p class="font-medium text-gray-900">Dinilai oleh Guru</p>
                                    <p class="text-sm text-gray-600">
                                        {{ $nilai->updated_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Nilai -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Hasil Penilaian</h2>
                    <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6">
                        <div class="text-center">
                            <div
                                class="text-5xl font-bold {{ $nilai->nilai >= 80 ? 'text-green-600' : ($nilai->nilai >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ number_format($nilai->nilai, 0) }}
                            </div>
                            <div class="mt-2">
                                @if ($nilai->nilai >= 80)
                                <span class="text-lg font-semibold text-green-700">Sangat Baik</span>
                                @elseif($nilai->nilai >= 60)
                                <span class="text-lg font-semibold text-yellow-700">Cukup</span>
                                @else
                                <span class="text-lg font-semibold text-red-700">Perlu Perbaikan</span>
                                @endif
                            </div>
                            <div class="mt-4 text-sm text-gray-600">
                                Nilai pada skala 0-100
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feedback -->
                @if ($nilai->keterangan || ($pengumpulan && $pengumpulan->feedback))
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Feedback dari Guru</h2>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-xl p-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-comment-alt text-yellow-600"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-gray-700 whitespace-pre-line">
                                    {{ $nilai->keterangan ?: ($pengumpulan ? $pengumpulan->feedback : '-') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if($pengumpulan && $pengumpulan->keterangan)
                <!-- Keterangan dari Siswa -->
                <div class="mb-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Keterangan Pengumpulan Saya</h2>
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <p class="text-gray-700">{{ $pengumpulan->keterangan }}</p>
                    </div>
                </div>
                @endif

                <!-- File Tugas -->
                @if ($pengumpulan && $pengumpulan->file)
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">File Tugas</h2>
                    <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-xl p-4">
                        <div class="flex items-center">
                            @php
                            $extension = pathinfo($pengumpulan->file, PATHINFO_EXTENSION);
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
                            ][strtolower($extension)] ?? 'fas fa-file text-gray-500';
                            @endphp
                            <div class="{{ $iconClass }} text-2xl mr-3"></div>
                            <div>
                                <p class="font-medium text-gray-900">{{ basename($pengumpulan->file) }}
                                </p>
                                <p class="text-sm text-gray-500">{{ strtoupper($extension) }}</p>
                            </div>
                        </div>
                        <a href="{{ Storage::url($pengumpulan->file) }}" target="_blank"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                            <i class="fas fa-download mr-2"></i> Download
                        </a>
                    </div>
                </div>
                @endif
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 bg-gray-50 border-t">
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
                    <div class="text-sm text-gray-500">
                        Terakhir diperbarui: {{ $nilai->updated_at->format('d M Y, H:i') }}
                    </div>
                    <div>
                        <a href="{{ route('siswa.nilai.index') }}"
                            class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tips -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
            <div class="flex items-start">
                <i class="fas fa-lightbulb text-amber-500 mt-1 mr-3"></i>
                <div>
                    <h4 class="text-sm font-semibold text-blue-800 mb-2">Tips Meningkatkan Nilai:</h4>
                    <ul class="text-sm text-blue-700 space-y-1">
                        <li>• Perhatikan feedback dari guru dengan seksama</li>
                        <li>• Analisis kesalahan dan pelajari cara perbaikan</li>
                        <li>• Mintalah penjelasan tambahan jika kurang jelas</li>
                        <li>• Gunakan nilai sebagai motivasi untuk belajar lebih baik</li>
                        <li>• Diskusikan dengan guru tentang cara meningkatkan kemampuan</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</body>

</html>