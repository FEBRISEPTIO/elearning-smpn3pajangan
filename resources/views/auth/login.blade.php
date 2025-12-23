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

        .illustration {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="flex min-h-screen">
        <!-- Kolom Kiri: Ilustrasi & Welcome -->
        <div class="hidden lg:flex flex-col justify-between login-bg w-1/2 p-12 text-white">
            <div>
                <div class="flex items-center mb-8">
                    <i class="fas fa-graduation-cap text-3xl mr-3"></i>
                    <h1 class="text-3xl font-bold">E-Learning STIPA</h1>
                </div>

                <div class="mt-8">
                    <h2 class="text-4xl font-bold mb-4">Selamat Datang Kembali!</h2>
                    <p class="text-xl opacity-90 mb-8">
                        Sistem pembelajaran online terintegrasi untuk sekolah Anda
                    </p>

                    <div class="space-y-6">
                        <div class="flex items-center">
                            <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                <i class="fas fa-book-open text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Materi Pembelajaran</h3>
                                <p class="opacity-80">Akses materi kapan saja, di mana saja</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                <i class="fas fa-tasks text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Tugas & Penilaian</h3>
                                <p class="opacity-80">Kerjakan tugas dan pantau nilai secara real-time</p>
                            </div>
                        </div>

                        <div class="flex items-center">
                            <div class="bg-white bg-opacity-20 p-3 rounded-full mr-4">
                                <i class="fas fa-comments text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Diskusi Interaktif</h3>
                                <p class="opacity-80">Berinteraksi dengan guru dan siswa lainnya</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Ilustrasi -->
            <div class="text-center illustration">
                <div class="inline-block p-6 bg-white bg-opacity-10 rounded-3xl">
                    <i class="fas fa-laptop-house text-6xl"></i>
                </div>
                <p class="mt-4 opacity-80">Belajar lebih efektif dengan sistem digital</p>
            </div>

            <div class="text-sm opacity-75">
                <p>© 2024 E-Learning System. All rights reserved.</p>
            </div>
        </div>

        <!-- Kolom Kanan: Form Login -->
        <div class="flex-1 flex items-center justify-center p-8">
            <div class="w-full max-w-md">

                <div class="lg:hidden flex justify-center mb-8">
                    <div class="flex items-center">
                        <i class="fas fa-graduation-cap text-blue-600 text-3xl mr-3"></i>
                        <h1 class="text-2xl font-bold text-gray-900">E-Learning</h1>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-gray-900">Masuk ke Akun Anda</h2>
                    <p class="text-gray-600 mt-2">
                        Gunakan kredensial yang diberikan untuk masuk
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

                <!-- Footer -->
                <div class="lg:hidden mt-8 text-center">
                    <p class="text-sm text-gray-500">
                        © 2024 E-Learning System. All rights reserved.
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
                // Jika input adalah angka (NISN/NIK), format hanya angka
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
