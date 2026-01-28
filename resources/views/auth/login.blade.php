<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Learning</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .login-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .info-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .scrollable-content {
            max-height: 70vh;
            overflow-y: auto;
            padding-right: 10px;
        }

        .scrollable-content::-webkit-scrollbar {
            width: 5px;
        }

        .scrollable-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 10px;
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Kolom Kiri: Data Sekolah (DINAMIS) -->
        <div class="hidden lg:flex flex-col justify-between login-bg w-1/2 p-8 text-white">
            <div class="scrollable-content">
                <!-- Logo dan Nama Sekolah -->
                <div class="flex items-center mb-8">
                    @if ($dataSekolah && $dataSekolah->logo)
                        <img src="{{ Storage::url($dataSekolah->logo) }}" alt="Logo Sekolah"
                            class="h-14 w-14 mr-4 object-contain bg-white rounded-lg p-1">
                    @else
                        <div class="h-14 w-14 mr-4 flex items-center justify-center bg-white rounded-lg">
                            <i class="fas fa-school text-2xl text-blue-600"></i>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold">{{ $dataSekolah->nama_sekolah ?? 'E-Learning Sekolah' }}</h1>
                        <p class="text-sm opacity-90 mt-1">Sistem Pembelajaran Online Terintegrasi</p>
                    </div>
                </div>

                <!-- Visi Sekolah -->
                @if ($dataSekolah && $dataSekolah->visi)
                    <div class="info-card p-5 rounded-xl mb-6">
                        <div class="flex items-center mb-3">
                            <div class="bg-blue-500 p-2 rounded-lg mr-3">
                                <i class="fas fa-eye text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg font-bold">Visi Sekolah</h2>
                        </div>
                        <p class="leading-relaxed">{{ $dataSekolah->visi }}</p>
                    </div>
                @endif

                <!-- Misi Sekolah -->
                @if ($dataSekolah && $dataSekolah->misi)
                    <div class="info-card p-5 rounded-xl mb-6">
                        <div class="flex items-center mb-3">
                            <div class="bg-green-500 p-2 rounded-lg mr-3">
                                <i class="fas fa-bullseye text-white text-sm"></i>
                            </div>
                            <h2 class="text-lg font-bold">Misi Sekolah</h2>
                        </div>
                        <p class="leading-relaxed">{{ $dataSekolah->misi }}</p>
                    </div>
                @endif

                <!-- Kontak dan Alamat -->
                <div class="info-card p-5 rounded-xl mb-6">
                    <div class="flex items-center mb-3">
                        <div class="bg-purple-500 p-2 rounded-lg mr-3">
                            <i class="fas fa-address-card text-white text-sm"></i>
                        </div>
                        <h2 class="text-lg font-bold">Kontak & Alamat</h2>
                    </div>
                    <div class="space-y-3">
                        @if ($dataSekolah && $dataSekolah->alamat)
                            <div class="flex items-start">
                                <i class="fas fa-map-marker-alt mt-1 mr-3 w-5 text-yellow-300"></i>
                                <span>{{ $dataSekolah->alamat }}</span>
                            </div>
                        @endif

                        @if ($dataSekolah && $dataSekolah->telepon)
                            <div class="flex items-center">
                                <i class="fas fa-phone mr-3 w-5 text-green-300"></i>
                                <span>{{ $dataSekolah->telepon }}</span>
                            </div>
                        @endif

                        @if ($dataSekolah && $dataSekolah->email)
                            <div class="flex items-center">
                                <i class="fas fa-envelope mr-3 w-5 text-red-300"></i>
                                <span>{{ $dataSekolah->email }}</span>
                            </div>
                        @endif

                        @if ($dataSekolah && $dataSekolah->website)
                            <div class="flex items-center">
                                <i class="fas fa-globe mr-3 w-5 text-blue-300"></i>
                                <a href="{{ $dataSekolah->website }}" target="_blank" class="hover:underline">
                                    Kunjungi Website
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="mt-6 pt-4 border-t border-white border-opacity-20">
                <p class="text-sm opacity-80">
                    {{ date('Y') }} - {{ $dataSekolah->nama_sekolah ?? 'Sistem E-Learning Sekolah' }}
                </p>
            </div>
        </div>

        <!-- Kolom Kanan: Form Login (TETAP SAMA) -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">
                <!-- Logo Mobile -->
                <div class="lg:hidden flex justify-center mb-8">
                    <div class="flex items-center">
                        @if ($dataSekolah && $dataSekolah->logo)
                            <img src="{{ Storage::url($dataSekolah->logo) }}" alt="Logo Sekolah"
                                class="h-12 w-12 mr-3 object-contain bg-gray-100 rounded-lg p-1">
                        @else
                            <i class="fas fa-school text-blue-600 text-3xl mr-3"></i>
                        @endif
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ $dataSekolah->nama_sekolah ?? 'E-Learning' }}</h1>
                            <p class="text-xs text-gray-600">Sistem Pembelajaran Online</p>
                        </div>
                    </div>
                </div>

                <!-- Welcome Message Mobile -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Masuk ke Sistem</h2>
                    <p class="text-gray-600 mt-2">
                        Silakan masukkan username dan password Anda
                    </p>
                </div>

                @if (session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6"
                        role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ session('error') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Form Login -->
                <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                    <form method="POST" action="{{ route('login.submit') }}">
                        @csrf

                        <!-- Username -->
                        <div class="mb-6">
                            <label for="username" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-user mr-2 text-blue-500"></i>Username
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-id-card text-gray-400"></i>
                                </div>
                                <input id="username" name="username" type="text" required
                                    value="{{ old('username') }}"
                                    class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Masukkan username Anda" autofocus>
                                @error('username')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Password -->
                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-lock mr-2 text-blue-500"></i>Password
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-key text-gray-400"></i>
                                </div>
                                <input id="password" name="password" type="password" required
                                    class="pl-10 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                    placeholder="Masukkan password Anda">
                                @error('password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Submit -->
                        <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-lg flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-3"></i>
                            Masuk ke Sistem
                        </button>
                    </form>

                    <!-- Login Information -->
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                            Informasi Login
                        </h4>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-start">
                                <i class="fas fa-user-graduate text-green-500 mt-1 mr-2"></i>
                                <div>
                                    <span class="font-medium">Siswa:</span> Username = <span
                                        class="font-mono">NISN</span>,
                                    Password = <span class="font-mono">NIS</span>
                                </div>
                            </div>
                            <div class="flex items-start">
                                <i class="fas fa-chalkboard-teacher text-purple-500 mt-1 mr-2"></i>
                                <div>
                                    <span class="font-medium">Guru:</span> Username = <span
                                        class="font-mono">NIK</span>,
                                    Password = <span class="font-mono">NIK</span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Footer Mobile -->
                <div class="lg:hidden mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        © {{ date('Y') }} {{ $dataSekolah->nama_sekolah ?? 'E-Learning System' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form validation and UX enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const usernameInput = document.getElementById('username');
            const passwordInput = document.getElementById('password');
            const form = document.querySelector('form');

            // Auto format for numeric inputs
            usernameInput.addEventListener('input', function(e) {
                if (/^\d+$/.test(e.target.value)) {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                }
            });

            // Form submission enhancement
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
                submitBtn.disabled = true;
            });

            // Show/hide password
            const togglePassword = document.createElement('span');
            togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
            togglePassword.className =
                'absolute inset-y-0 right-0 pr-3 flex items-center cursor-pointer text-gray-400 hover:text-gray-600';
            togglePassword.style.top = '0';
            togglePassword.style.bottom = '0';
            togglePassword.style.right = '0';

            const passwordContainer = passwordInput.parentElement;
            passwordContainer.appendChild(togglePassword);

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' :
                    '<i class="fas fa-eye-slash"></i>';
            });
        });
    </script>
</body>

</html>
