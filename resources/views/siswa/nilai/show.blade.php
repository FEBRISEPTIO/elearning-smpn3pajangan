<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Nilai - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body class="bg-gray-100">
    @include('siswa.partials.header')

    <main class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="px-4 py-6 sm:px-0">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('siswa.nilai.index') }}"
                    class="inline-flex items-center text-blue-600 hover:text-blue-800">
                    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Nilai
                </a>
            </div>

            <!-- Card Detail Nilai -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                <!-- Header -->
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-4">
                    <h1 class="text-xl font-bold text-white">Detail Nilai Tugas</h1>
                </div>

                <!-- Content -->
                <div class="p-6">
                    <!-- Info Tugas -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Tugas</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Judul Tugas</p>
                                <p class="font-medium text-gray-900">{{ $pengumpulan->tugas->judul_tugas }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Mata Pelajaran</p>
                                <p class="font-medium text-gray-900">
                                    <i class="fas fa-book mr-1 text-blue-500"></i>
                                    {{ $pengumpulan->tugas->mataPelajaran->nama_mapel ?? '-' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Guru Pengampu</p>
                                <p class="font-medium text-gray-900">
                                    <i class="fas fa-user-tie mr-1 text-green-500"></i>
                                    {{ $pengumpulan->tugas->guru->nama ?? 'Guru' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Kelas</p>
                                <p class="font-medium text-gray-900">
                                    <i class="fas fa-users mr-1 text-purple-500"></i>
                                    {{ $pengumpulan->tugas->kelas->kelas ?? '-' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Timeline</h2>
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
                                            {{ $pengumpulan->submitted_at->format('d M Y, H:i') }}</p>
                                    </div>
                                </div>

                                <!-- Dinilai -->
                                <div class="relative pl-12">
                                    <div class="absolute left-2 top-0 w-4 h-4 bg-green-500 rounded-full"></div>
                                    <div>
                                        <p class="font-medium text-gray-900">Dinilai oleh Guru</p>
                                        <p class="text-sm text-gray-600">
                                            {{ $pengumpulan->updated_at->format('d M Y, H:i') }}</p>
                                        <p class="text-sm text-green-600 mt-1">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $pengumpulan->submitted_at->diff($pengumpulan->updated_at)->format('%a hari, %h jam, %i menit') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nilai -->
                    <div class="mb-8">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Hasil Penilaian</h2>
                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-6">
                            <div class="text-center">
                                <div
                                    class="text-5xl font-bold {{ $pengumpulan->nilai >= 80 ? 'text-green-600' : ($pengumpulan->nilai >= 60 ? 'text-yellow-600' : 'text-red-600') }}">
                                    {{ number_format($pengumpulan->nilai, 2) }}
                                </div>
                                <div class="mt-2">
                                    @if ($pengumpulan->nilai >= 80)
                                        <span class="text-lg font-semibold text-green-700">Sangat Baik</span>
                                    @elseif($pengumpulan->nilai >= 60)
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
                    @if ($pengumpulan->feedback)
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Feedback dari Guru</h2>
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 bg-yellow-100 rounded-full flex items-center justify-center">
                                            <i class="fas fa-comment-alt text-yellow-600"></i>
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <p class="text-gray-700 whitespace-pre-line">{{ $pengumpulan->feedback }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Keterangan -->
                    @if ($pengumpulan->keterangan)
                        <div class="mb-8">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Keterangan Pengumpulan</h2>
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                                <p class="text-gray-700">{{ $pengumpulan->keterangan }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- File Tugas -->
                    @if ($pengumpulan->file)
                        <div>
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">File Tugas</h2>
                            <div
                                class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg p-4">
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
                                        <p class="font-medium text-gray-900">{{ $pengumpulan->getFileNameAttribute() }}
                                        </p>
                                        <p class="text-sm text-gray-500">{{ strtoupper($extension) }}</p>
                                    </div>
                                </div>
                                <a href="{{ Storage::url($pengumpulan->file) }}" target="_blank"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                    <i class="fas fa-download mr-2"></i> Download
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-500">
                            Terakhir diperbarui: {{ $pengumpulan->updated_at->format('d M Y, H:i') }}
                        </div>
                        <div>
                            <a href="{{ route('siswa.nilai.index') }}"
                                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>
