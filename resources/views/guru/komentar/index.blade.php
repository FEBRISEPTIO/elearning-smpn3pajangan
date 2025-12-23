<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komentar Tugas & Materi - Guru Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        .sidebar {
            transition: all 0.3s ease;
        }

        .comment-item {
            transition: all 0.2s ease;
        }

        .comment-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .avatar {
            width: 2.5rem;
            height: 2.5rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            color: white;
        }

        .tab-active {
            border-bottom: 3px solid #3b82f6;
            color: #3b82f6;
            font-weight: 600;
        }

        .reply-form {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .reply-form.show {
            max-height: 500px;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <i class="fas fa-chalkboard-teacher text-blue-600 text-2xl mr-3"></i>
                        <span class="text-xl font-bold text-gray-800">Dashboard Guru</span>
                    </div>
                </div>
                <div class="flex items-center">
                    <div class="ml-3 relative">
                        <div class="relative">
                            <button type="button"
                                class="flex items-center space-x-2 text-gray-700 hover:text-gray-900 focus:outline-none"
                                id="user-menu-button">
                                <div
                                    class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold">
                                    {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                                </div>
                                <span class="hidden md:block">{{ Auth::user()->name ?? 'Guru' }}</span>
                                <i class="fas fa-chevron-down text-sm"></i>
                            </button>

                            <!-- Dropdown menu -->
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 hidden"
                                id="user-menu">
                                <a href="{{ route('guru.profile') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i>Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Logout
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex">
        <!-- Sidebar -->
        <div class="sidebar w-64 bg-gradient-to-b from-blue-800 to-blue-900 min-h-screen text-white p-4">
            <div class="mt-4">
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('guru.dashboard') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-tachometer-alt w-6 mr-3"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru.materi.index') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-book w-6 mr-3"></i>
                            <span>Materi</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru.tugas.index') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-tasks w-6 mr-3"></i>
                            <span>Tugas</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru.komentar.index') }}"
                            class="flex items-center p-3 rounded-lg bg-blue-700 transition">
                            <i class="fas fa-comments w-6 mr-3"></i>
                            <span>Komentar</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru.siswa.index') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-users w-6 mr-3"></i>
                            <span>Siswa</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('guru.nilai.index') }}"
                            class="flex items-center p-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-star w-6 mr-3"></i>
                            <span>Nilai</span>
                        </a>
                    </li>
                </ul>

                <div class="mt-8 pt-4 border-t border-blue-700">
                    <p class="text-sm text-blue-200">
                        <i class="fas fa-info-circle mr-2"></i>
                        Kelola komentar siswa pada tugas dan materi
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-6">
            <!-- Header -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">
                        <i class="fas fa-comments text-blue-600 mr-3"></i>Komentar Tugas & Materi
                    </h1>
                    <p class="text-gray-600 mt-2">Kelola komentar siswa pada tugas dan materi yang Anda buat</p>
                </div>
                <div class="flex space-x-3">
                    <button onclick="toggleFilter()"
                        class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition flex items-center">
                        <i class="fas fa-filter mr-2"></i> Filter
                    </button>
                    <a href="{{ route('guru.dashboard') }}"
                        class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Kembali
                    </a>
                </div>
            </div>

            <!-- Filter Section (Hidden by default) -->
            <div id="filterSection" class="hidden bg-white p-6 rounded-xl shadow mb-6">
                <form class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
                        <select class="w-full border border-gray-300 rounded-lg p-2">
                            <option value="">Semua Jenis</option>
                            <option value="tugas">Tugas</option>
                            <option value="materi">Materi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select class="w-full border border-gray-300 rounded-lg p-2">
                            <option value="">Semua Status</option>
                            <option value="replied">Sudah Dibalas</option>
                            <option value="pending">Belum Dibalas</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal</label>
                        <input type="date" class="w-full border border-gray-300 rounded-lg p-2">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                        <select class="w-full border border-gray-300 rounded-lg p-2">
                            <option value="">Semua Kelas</option>
                            <option value="10">Kelas 10</option>
                            <option value="11">Kelas 11</option>
                            <option value="12">Kelas 12</option>
                        </select>
                    </div>
                    <div class="md:col-span-4 flex justify-end space-x-3">
                        <button type="button" onclick="toggleFilter()"
                            class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                            Terapkan Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Total Komentar</p>
                            <p class="text-2xl font-bold">47</p>
                        </div>
                        <i class="fas fa-comment text-blue-500 text-2xl"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Komentar Tugas</p>
                            <p class="text-2xl font-bold">32</p>
                        </div>
                        <i class="fas fa-tasks text-green-500 text-2xl"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow border-l-4 border-purple-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Komentar Materi</p>
                            <p class="text-2xl font-bold">15</p>
                        </div>
                        <i class="fas fa-book text-purple-500 text-2xl"></i>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow border-l-4 border-yellow-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Belum Dibalas</p>
                            <p class="text-2xl font-bold">8</p>
                        </div>
                        <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="mb-6 border-b border-gray-200">
                <div class="flex space-x-4">
                    <button id="tabAll" class="pb-3 px-1 tab-active" onclick="switchTab('all')">
                        Semua Komentar
                    </button>
                    <button id="tabTugas" class="pb-3 px-1 text-gray-600 hover:text-gray-800"
                        onclick="switchTab('tugas')">
                        <i class="fas fa-tasks mr-2"></i>Komentar Tugas
                    </button>
                    <button id="tabMateri" class="pb-3 px-1 text-gray-600 hover:text-gray-800"
                        onclick="switchTab('materi')">
                        <i class="fas fa-book mr-2"></i>Komentar Materi
                    </button>
                </div>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <div class="relative">
                    <input type="text" id="searchInput" placeholder="Cari komentar, siswa, atau judul..."
                        class="w-full p-4 pl-12 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                </div>
            </div>

            <!-- Comments List -->
            <div class="space-y-6">
                <!-- Komentar pada Tugas -->
                <div class="comment-item bg-white rounded-xl shadow p-6" data-type="tugas">
                    <div class="flex items-start space-x-4">
                        <!-- Avatar Siswa -->
                        <div class="avatar bg-blue-600">AS</div>

                        <div class="flex-1">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-lg">Ahmad Santoso</h3>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="badge bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-tasks mr-1"></i> Tugas
                                        </span>
                                        <span class="badge bg-red-100 text-red-800">
                                            <i class="fas fa-clock mr-1"></i> Belum Dibalas
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            <i class="far fa-clock mr-1"></i> 2 jam yang lalu
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Tugas Info -->
                            <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                                <a href="#" class="text-blue-600 font-medium hover:underline">
                                    <i class="fas fa-link mr-2"></i>Tugas: "Analisis Puisi 'Aku' karya Chairil Anwar"
                                </a>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-graduation-cap mr-1"></i>Kelas 11 IPA •
                                    <i class="fas fa-calendar mr-1 ml-2"></i>Deadline: 15 Des 2023
                                </p>
                            </div>

                            <!-- Komentar -->
                            <div class="mb-4">
                                <p class="text-gray-700">
                                    "Bu, untuk analisis puisi ini apakah perlu mencantumkan biografi penyair juga?
                                    Atau cukup analisis struktur puisinya saja? Saya bingung dengan instruksi di poin
                                    ke-3."
                                </p>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex space-x-3">
                                <button onclick="toggleReply('reply1')"
                                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center">
                                    <i class="fas fa-reply mr-2"></i> Balas Komentar
                                </button>
                                <button
                                    class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition flex items-center">
                                    <i class="fas fa-check mr-2"></i> Tandai Sudah Dibalas
                                </button>
                            </div>

                            <!-- Reply Form -->
                            <div id="reply1" class="reply-form mt-4">
                                <div class="p-4 bg-gray-50 rounded-lg">
                                    <textarea class="w-full p-3 border border-gray-300 rounded-lg mb-3" rows="3"
                                        placeholder="Ketik balasan Anda..."></textarea>
                                    <div class="flex justify-end space-x-3">
                                        <button onclick="toggleReply('reply1')"
                                            class="px-4 py-2 border border-gray-300 rounded-lg">
                                            Batal
                                        </button>
                                        <button onclick="submitReply('reply1')"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg">
                                            Kirim Balasan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Komentar pada Materi -->
                <div class="comment-item bg-white rounded-xl shadow p-6" data-type="materi">
                    <div class="flex items-start space-x-4">
                        <!-- Avatar Siswa -->
                        <div class="avatar bg-green-600">SR</div>

                        <div class="flex-1">
                            <!-- Header -->
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-lg">Sari Ratnasari</h3>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="badge bg-purple-100 text-purple-800">
                                            <i class="fas fa-book mr-1"></i> Materi
                                        </span>
                                        <span class="badge bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Sudah Dibalas
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            <i class="far fa-clock mr-1"></i> 1 hari yang lalu
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Materi Info -->
                            <div class="mb-4 p-3 bg-purple-50 rounded-lg">
                                <a href="#" class="text-purple-600 font-medium hover:underline">
                                    <i class="fas fa-link mr-2"></i>Materi: "Fotosintesis - Proses dan Faktor yang
                                    Mempengaruhi"
                                </a>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-graduation-cap mr-1"></i>Kelas 10 IPA •
                                    <i class="fas fa-tag mr-1 ml-2"></i>Biologi
                                </p>
                            </div>

                            <!-- Komentar -->
                            <div class="mb-4">
                                <p class="text-gray-700">
                                    "Bu, pada slide ke-7 tentang faktor pembatas fotosintesis, apakah intensitas cahaya
                                    dan suhu memiliki hubungan langsung? Mohon penjelasannya."
                                </p>
                            </div>

                            <!-- Balasan dari Guru -->
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                <div class="flex items-center mb-2">
                                    <div
                                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                        {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-blue-700">Anda (Guru)</span>
                                        <span class="text-sm text-gray-500 ml-3">Kemarin, 14:30</span>
                                    </div>
                                </div>
                                <p class="text-gray-700">
                                    "Benar Sari, intensitas cahaya dan suhu saling berkaitan. Pada kondisi cahaya
                                    rendah,
                                    suhu optimal fotosintesis juga lebih rendah. Saya akan tambahkan diagram hubungannya
                                    di materi."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Komentar Lainnya -->
                <div class="comment-item bg-white rounded-xl shadow p-6" data-type="tugas">
                    <div class="flex items-start space-x-4">
                        <div class="avatar bg-red-600">BD</div>
                        <div class="flex-1">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-bold text-lg">Budi Darma</h3>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="badge bg-yellow-100 text-yellow-800">
                                            <i class="fas fa-tasks mr-1"></i> Tugas
                                        </span>
                                        <span class="badge bg-green-100 text-green-800">
                                            <i class="fas fa-check mr-1"></i> Sudah Dibalas
                                        </span>
                                        <span class="text-sm text-gray-500">
                                            <i class="far fa-clock mr-1"></i> 3 hari yang lalu
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-4 p-3 bg-blue-50 rounded-lg">
                                <a href="#" class="text-blue-600 font-medium hover:underline">
                                    <i class="fas fa-link mr-2"></i>Tugas: "Proyek Percobaan Hukum Newton"
                                </a>
                                <p class="text-sm text-gray-600 mt-1">
                                    <i class="fas fa-graduation-cap mr-1"></i>Kelas 10 IPA •
                                    <i class="fas fa-tag mr-1 ml-2"></i>Fisika
                                </p>
                            </div>
                            <div class="mb-4">
                                <p class="text-gray-700">
                                    "Bu, untuk proyek hukum Newton, apakah boleh dibuat kelompok dengan 4 orang?
                                    Atau maksimal 3 orang seperti yang disebutkan?"
                                </p>
                            </div>
                            <div class="mt-4 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500">
                                <div class="flex items-center mb-2">
                                    <div
                                        class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white font-semibold mr-3">
                                        {{ substr(Auth::user()->name ?? 'G', 0, 1) }}
                                    </div>
                                    <div>
                                        <span class="font-bold text-blue-700">Anda (Guru)</span>
                                        <span class="text-sm text-gray-500 ml-3">3 hari yang lalu</span>
                                    </div>
                                </div>
                                <p class="text-gray-700">
                                    "Boleh Budi, maksimal 4 orang. Tapi pastikan semua anggota berkontribusi aktif ya.
                                    Sertakan pembagian tugas di laporan."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-8 flex justify-center">
                <nav class="inline-flex rounded-md shadow">
                    <a href="#"
                        class="px-3 py-2 rounded-l-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-chevron-left"></i>
                    </a>
                    <a href="#"
                        class="px-4 py-2 border-t border-b border-gray-300 bg-blue-50 text-blue-600 font-medium">1</a>
                    <a href="#"
                        class="px-4 py-2 border border-gray-300 bg-white text-gray-700 hover:bg-gray-50">2</a>
                    <a href="#"
                        class="px-4 py-2 border-t border-b border-gray-300 bg-white text-gray-700 hover:bg-gray-50">3</a>
                    <a href="#"
                        class="px-3 py-2 rounded-r-md border border-gray-300 bg-white text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-chevron-right"></i>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Toggle filter section
        function toggleFilter() {
            const filterSection = document.getElementById('filterSection');
            filterSection.classList.toggle('hidden');
        }

        // Switch tabs
        function switchTab(tab) {
            // Update active tab
            document.querySelectorAll('[id^="tab"]').forEach(btn => {
                btn.classList.remove('tab-active');
                btn.classList.add('text-gray-600');
            });
            document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.add('tab-active');
            document.getElementById('tab' + tab.charAt(0).toUpperCase() + tab.slice(1)).classList.remove('text-gray-600');

            // Filter comments
            const comments = document.querySelectorAll('.comment-item');
            comments.forEach(comment => {
                if (tab === 'all') {
                    comment.style.display = 'block';
                } else {
                    if (comment.dataset.type === tab) {
                        comment.style.display = 'block';
                    } else {
                        comment.style.display = 'none';
                    }
                }
            });
        }

        // Toggle reply form
        function toggleReply(replyId) {
            const replyForm = document.getElementById(replyId);
            replyForm.classList.toggle('show');
            replyForm.style.maxHeight = replyForm.classList.contains('show') ? replyForm.scrollHeight + 'px' : '0';
        }

        // Submit reply
        function submitReply(replyId) {
            const textarea = document.querySelector(`#${replyId} textarea`);
            if (textarea.value.trim() === '') {
                alert('Balasan tidak boleh kosong!');
                return;
            }

            // Here you would typically send to server via AJAX
            console.log('Mengirim balasan:', textarea.value);

            // Simulate success
            alert('Balasan berhasil dikirim!');
            textarea.value = '';
            toggleReply(replyId);

            // In real app, you would update the UI with the new reply
        }

        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const comments = document.querySelectorAll('.comment-item');

            comments.forEach(comment => {
                const text = comment.textContent.toLowerCase();
                comment.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });

        // User dropdown
        document.getElementById('user-menu-button').addEventListener('click', function() {
            document.getElementById('user-menu').classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userMenu = document.getElementById('user-menu');
            const userButton = document.getElementById('user-menu-button');

            if (!userMenu.contains(event.target) && !userButton.contains(event.target)) {
                userMenu.classList.add('hidden');
            }
        });
    </script>
</body>

</html>
