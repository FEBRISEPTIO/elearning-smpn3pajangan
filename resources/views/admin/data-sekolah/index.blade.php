<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Sekolah - E-Learning</title>
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
    @include('admin.partials.header')

    <!-- Main Content -->
    <main class="md:ml-64 min-h-screen p-4 md:p-6 transition-all duration-300">
        <div class="bg-white rounded-xl shadow-sm p-6">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Data Sekolah</h1>
                    <p class="text-gray-600 mt-1">Informasi sekolah yang ditampilkan di halaman login</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.data-sekolah.edit') }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                        <i class="fas fa-edit mr-2"></i> Edit Data
                    </a>
                    <a href="{{ route('admin.dashboard') }}"
                        class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm font-medium inline-flex items-center transition">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-500"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Info Box -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
                <div class="flex items-start">
                    <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                    <div>
                        <h4 class="text-sm font-semibold text-blue-800 mb-1">Informasi:</h4>
                        <p class="text-sm text-blue-700">
                            Data ini akan ditampilkan di halaman login sistem E-Learning. Pastikan data selalu
                            diperbarui.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Logo dan Info Utama -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Logo Sekolah -->
                <div class="bg-gray-50 rounded-xl p-6 text-center">
                    <div class="mb-4">
                        @if ($data && $data->logo)
                            <img src="{{ Storage::url($data->logo) }}" alt="Logo Sekolah"
                                class="w-48 h-48 object-contain mx-auto rounded-lg shadow-sm">
                        @else
                            <div
                                class="w-48 h-48 bg-white border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center mx-auto">
                                <i class="fas fa-school text-gray-400 text-6xl"></i>
                            </div>
                        @endif
                    </div>
                    <h3 class="font-bold text-gray-800">{{ $data->nama_sekolah ?? 'Nama Sekolah' }}</h3>
                    <p class="text-sm text-gray-600 mt-1">Logo Sekolah</p>
                </div>

                <!-- Informasi Kontak -->
                <div class="md:col-span-2 bg-gray-50 rounded-xl p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kontak</h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="bg-blue-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-map-marker-alt text-blue-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Alamat</p>
                                <p class="text-gray-600">{{ $data->alamat ?? 'Belum diatur' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="bg-green-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-phone text-green-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Telepon</p>
                                <p class="text-gray-600">{{ $data->telepon ?? 'Belum diatur' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="bg-yellow-100 p-2 rounded-lg mr-3">
                                <i class="fas fa-envelope text-yellow-600"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-700">Email</p>
                                <p class="text-gray-600">{{ $data->email ?? 'Belum diatur' }}</p>
                            </div>
                        </div>

                        @if ($data && $data->website)
                            <div class="flex items-center">
                                <div class="bg-purple-100 p-2 rounded-lg mr-3">
                                    <i class="fas fa-globe text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Website</p>
                                    <a href="{{ $data->website }}" target="_blank"
                                        class="text-blue-600 hover:text-blue-800 hover:underline">
                                        {{ $data->website }}
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Visi Sekolah -->
            @if ($data && $data->visi)
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-blue-600 p-2 rounded-lg mr-3">
                                <i class="fas fa-eye text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Visi Sekolah</h3>
                                <p class="text-sm text-gray-600">Pandangan masa depan sekolah</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-100">
                            <p class="text-gray-700 leading-relaxed">{{ $data->visi }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Misi Sekolah -->
            @if ($data && $data->misi)
                <div class="mb-6">
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                        <div class="flex items-center mb-4">
                            <div class="bg-green-600 p-2 rounded-lg mr-3">
                                <i class="fas fa-bullseye text-white"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900">Misi Sekolah</h3>
                                <p class="text-sm text-gray-600">Tujuan dan strategi sekolah</p>
                            </div>
                        </div>
                        <div class="bg-white rounded-lg p-4 border border-gray-100">
                            <div class="text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ $data->misi }}
                            </div>
                        </div>
                    </div>
                </div>
            @endif



            <!-- Last Updated -->
            @if ($data && $data->updated_at)
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex items-center justify-between text-sm text-gray-500">
                        <div>
                            <i class="fas fa-history mr-2"></i>
                            Terakhir diperbarui: {{ $data->updated_at->translatedFormat('l, d F Y H:i') }}
                        </div>
                        <div>
                            <i class="fas fa-calendar-alt mr-2"></i>
                            Dibuat: {{ $data->created_at->translatedFormat('d F Y') }}
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </main>

    <script>
        // Animation for cards
        document.querySelectorAll('.hover\\:shadow').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Copy email to clipboard
        function copyEmail() {
            const email = "{{ $data->email ?? '' }}";
            if (email) {
                navigator.clipboard.writeText(email).then(() => {
                    alert('Email berhasil disalin: ' + email);
                });
            }
        }

        // Copy phone to clipboard
        function copyPhone() {
            const phone = "{{ $data->telepon ?? '' }}";
            if (phone) {
                navigator.clipboard.writeText(phone).then(() => {
                    alert('Nomor telepon berhasil disalin: ' + phone);
                });
            }
        }

        // Make email and phone copyable
        document.querySelectorAll('.text-gray-600').forEach(element => {
            if (element.textContent.includes('@')) {
                element.style.cursor = 'pointer';
                element.title = 'Klik untuk menyalin';
                element.addEventListener('click', copyEmail);
            } else if (/^[\d\s()-]+$/.test(element.textContent.trim())) {
                element.style.cursor = 'pointer';
                element.title = 'Klik untuk menyalin';
                element.addEventListener('click', copyPhone);
            }
        });
    </script>
</body>

</html>
