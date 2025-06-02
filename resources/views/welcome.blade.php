<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Manajemen Proyek - PT Arjuna Lingga Property</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .hover-scale {
            transition: all 0.3s ease;
        }

        .hover-scale:hover {
            transform: scale(1.05);
        }

        .animate-float {
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

        .building-icon {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }
    </style>
</head>

<body class="min-h-screen gradient-bg">
    <!-- Header -->
    <header class="w-full py-4 px-6">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-white p-2 rounded-lg shadow-lg">
                    <i class="fas fa-building text-2xl text-blue-600"></i>
                </div>
                <div class="text-white">
                    <h1 class="text-xl font-bold">PT Arjuna Lingga Property</h1>
                    <p class="text-sm opacity-90">Project Management System</p>
                </div>
            </div>
            <div class="text-white text-right">
                <p class="text-sm opacity-90">Internal Access Only</p>
                <p class="text-xs opacity-75" id="currentTime"></p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="max-w-4xl mx-auto text-center">
            <!-- Welcome Card -->
            <div class="glass-effect rounded-3xl p-8 md:p-12 mb-8 hover-scale">
                <div class="mb-8">
                    <div class="inline-block p-4 bg-white rounded-full shadow-xl mb-6 animate-float">
                        <i class="fas fa-hard-hat text-4xl text-blue-600 building-icon"></i>
                    </div>
                    <h2 id="welcomeTitle" class="text-4xl md:text-5xl font-bold text-white mb-4">
                        Selamat Datang
                    </h2>
                    <p class="text-xl text-white opacity-90 mb-6">
                        Sistem Informasi Manajemen Proyek Konstruksi
                    </p>
                    <div class="bg-white bg-opacity-20 rounded-full px-6 py-2 inline-block">
                        <p id="companyName" class="text-white font-medium">PT Arjuna Lingga Property</p>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div id="actionButtons" class="grid md:grid-cols-2 gap-4 mb-8">
                    <button id="loginBtn"
                        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-4 px-6 rounded-xl shadow-lg hover-scale transition-all duration-300 group">
                        <div class="flex items-center justify-center space-x-3">
                            <i class="fas fa-sign-in-alt text-blue-600 group-hover:scale-110 transition-transform"></i>
                            <span>Masuk ke Sistem</span>
                        </div>
                    </button>
                    <button id="guideBtn"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover-scale transition-all duration-300 group">
                        <div class="flex items-center justify-center space-x-3">
                            <i class="fas fa-book text-white group-hover:scale-110 transition-transform"></i>
                            <span>Panduan Sistem</span>
                        </div>
                    </button>
                </div>

                <!-- User Logged In Buttons (Hidden by default) -->
                <div id="userButtons" class="grid md:grid-cols-3 gap-4 mb-8 hidden">
                    <button id="dashboardBtn"
                        class="bg-white hover:bg-gray-50 text-gray-800 font-semibold py-4 px-6 rounded-xl shadow-lg hover-scale transition-all duration-300 group">
                        <div class="flex items-center justify-center space-x-3">
                            <i
                                class="fas fa-tachometer-alt text-blue-600 group-hover:scale-110 transition-transform"></i>
                            <span>Dashboard</span>
                        </div>
                    </button>
                    <button id="projectsBtn"
                        class="bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover-scale transition-all duration-300 group">
                        <div class="flex items-center justify-center space-x-3">
                            <i class="fas fa-project-diagram text-white group-hover:scale-110 transition-transform"></i>
                            <span>Kelola Proyek</span>
                        </div>
                    </button>
                    <button id="logoutBtn"
                        class="bg-red-600 hover:bg-red-700 text-white font-semibold py-4 px-6 rounded-xl shadow-lg hover-scale transition-all duration-300 group">
                        <div class="flex items-center justify-center space-x-3">
                            <i class="fas fa-sign-out-alt text-white group-hover:scale-110 transition-transform"></i>
                            <span>Keluar</span>
                        </div>
                    </button>
                </div>
            </div>

            <!-- Feature Cards -->
            <div class="grid md:grid-cols-3 gap-6">
                <div class="glass-effect rounded-2xl p-6 hover-scale group">
                    <div
                        class="bg-green-500 w-12 h-12 rounded-full flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform">
                        <i class="fas fa-tasks text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Manajemen Proyek</h3>
                    <p class="text-white opacity-80 text-sm">Kelola proyek konstruksi dengan efisien dan terorganisir
                    </p>
                </div>

                <div class="glass-effect rounded-2xl p-6 hover-scale group">
                    <div
                        class="bg-orange-500 w-12 h-12 rounded-full flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Monitoring Progress</h3>
                    <p class="text-white opacity-80 text-sm">Pantau kemajuan proyek secara real-time</p>
                </div>

                <div class="glass-effect rounded-2xl p-6 hover-scale group">
                    <div
                        class="bg-purple-500 w-12 h-12 rounded-full flex items-center justify-center mb-4 mx-auto group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">Kolaborasi Tim</h3>
                    <p class="text-white opacity-80 text-sm">Koordinasi tim yang lebih baik dan efektif</p>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="mt-12 glass-effect rounded-2xl p-6">
                <h3 class="text-2xl font-bold text-white mb-6">Status Sistem</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-green-400">24</div>
                        <div class="text-white opacity-80 text-sm">Proyek Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-blue-400">156</div>
                        <div class="text-white opacity-80 text-sm">Task Selesai</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-400">8</div>
                        <div class="text-white opacity-80 text-sm">Tim Aktif</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-purple-400">98%</div>
                        <div class="text-white opacity-80 text-sm">Sistem Uptime</div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-6 px-6 mt-12">
        <div class="max-w-7xl mx-auto text-center text-white opacity-80">
            <p class="text-sm">© 2025 PT Arjuna Lingga Property - Sistem Manajemen Proyek Konstruksi</p>
            <p class="text-xs mt-1">Akses terbatas untuk karyawan internal</p>
        </div>
    </footer>

    <script>
        // Simulate user login status
        let isLoggedIn = false;
        let currentUser = null;

        // Check if user is already logged in (from localStorage)
        function checkLoginStatus() {
            const userData = localStorage.getItem('currentUser');
            if (userData) {
                isLoggedIn = true;
                currentUser = JSON.parse(userData);
                updateUIForLoggedInUser();
            }
        }

        // Update UI when user is logged in
        function updateUIForLoggedInUser() {
            document.getElementById('actionButtons').classList.add('hidden');
            document.getElementById('userButtons').classList.remove('hidden');

            // Update welcome message
            document.getElementById('welcomeTitle').textContent = `Selamat Datang, ${currentUser.name}`;
            document.getElementById('companyName').textContent = `${currentUser.department} - PT Arjuna Lingga Property`;
        }

        // Update UI when user logs out
        function updateUIForLoggedOutUser() {
            document.getElementById('actionButtons').classList.remove('hidden');
            document.getElementById('userButtons').classList.add('hidden');

            // Reset welcome message
            document.getElementById('welcomeTitle').textContent = 'Selamat Datang';
            document.getElementById('companyName').textContent = 'PT Arjuna Lingga Property';
        }

        // Simulate login process
        function simulateLogin() {
            // Simulate API call delay
            const loginBtn = document.getElementById('loginBtn');
            const originalText = loginBtn.querySelector('span').textContent;
            loginBtn.querySelector('span').textContent = 'Memproses...';
            loginBtn.disabled = true;

            setTimeout(() => {
                // Simulate successful login
                currentUser = {
                    id: 1,
                    name: 'John Doe',
                    department: 'Tim Konstruksi',
                    role: 'Project Manager'
                };

                isLoggedIn = true;
                localStorage.setItem('currentUser', JSON.stringify(currentUser));
                updateUIForLoggedInUser();

                // Show success message
                showNotification('Login berhasil! Selamat datang di sistem.', 'success');

                loginBtn.querySelector('span').textContent = originalText;
                loginBtn.disabled = false;
            }, 1500);
        }

        // Logout function
        function logout() {
            isLoggedIn = false;
            currentUser = null;
            localStorage.removeItem('currentUser');
            updateUIForLoggedOutUser();
            showNotification('Anda telah keluar dari sistem.', 'info');
        }

        // Show notification
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className =
                `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;

            switch (type) {
                case 'success':
                    notification.classList.add('bg-green-500', 'text-white');
                    break;
                case 'error':
                    notification.classList.add('bg-red-500', 'text-white');
                    break;
                default:
                    notification.classList.add('bg-blue-500', 'text-white');
            }

            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas fa-info-circle"></i>
                    <span>${message}</span>
                </div>
            `;

            document.body.appendChild(notification);

            // Animate in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Remove after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 300);
            }, 3000);
        }

        // Update current time
        function updateTime() {
            const now = new Date();
            const timeString = now.toLocaleString('id-ID', {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
            document.getElementById('currentTime').textContent = timeString;
        }

        // Initialize
        updateTime();
        setInterval(updateTime, 60000);
        checkLoginStatus();

        // Event listeners for buttons
        document.getElementById('loginBtn').addEventListener('click', function() {
            // In real implementation, this would redirect to /main/login
            // For demo purposes, we'll simulate login
            if (confirm('Redirect ke halaman login? (Demo: klik OK untuk simulasi login)')) {
                window.location.href = '/main/login';
            }
        });

        document.getElementById('guideBtn').addEventListener('click', function() {
            showNotification('Fitur panduan sistem akan segera tersedia.', 'info');
        });

        // Event listeners for logged-in user buttons
        document.getElementById('dashboardBtn').addEventListener('click', function() {
            showNotification('Mengalihkan ke dashboard...', 'info');
            // window.location.href = '/main/dashboard'; // Real implementation
        });

        document.getElementById('projectsBtn').addEventListener('click', function() {
            showNotification('Mengalihkan ke halaman proyek...', 'info');
            // window.location.href = '/main/projects'; // Real implementation
        });

        document.getElementById('logoutBtn').addEventListener('click', function() {
            if (confirm('Yakin ingin keluar dari sistem?')) {
                logout();
            }
        });

        // Add click effect to all buttons
        document.querySelectorAll('button').forEach(button => {
            button.addEventListener('click', function() {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });
    </script>
</body>

</html>
