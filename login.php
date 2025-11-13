<?php
session_start();
include("include/connection.php");

$error = "";

// Bila user tekan login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['emel']);
    $password = trim($_POST['katalaluan']);

    // ✅ guna column `emel`
    $sql = "SELECT * FROM users WHERE emel = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();

        // ✅ guna column `katalaluan`
        if ($password === $user['katalaluan'] || password_verify($password, $user['katalaluan'])) {
            // ✅ guna `id_user` & `nama`
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['name'] = $user['nama'];

            // Redirect ikut role
            if ($user['role'] == "admin") {
                header("Location: admin_kvk/admin_dashboard.php");
            } elseif ($user['role'] == "guru") {
                header("Location: guru_kvk/guru_dashboard.php");
            } elseif ($user['role'] == "alumni") {
                header("Location: alumni_kvk/alumni_dashboard.php");
            }
            exit();
        } else {
            $error = "Kata laluan salah!";
        }
    } else {
        $error = "Akaun tidak dijumpai!";
    }
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Sistem Alumni - Kolej Vokasional Kangar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'primary': '#3B82F6',
                        'primary-dark': '#1D4ED8',
                        'accent': '#10B981',
                        'accent-dark': '#059669'
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        body { 
            font-family: 'Inter', sans-serif; 
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.95) 0%, rgba(51, 65, 85, 0.95) 100%),
                        url('assets/kvk_background.jpg') center/cover no-repeat fixed;
            min-height: 100vh;
            position: relative;
        }
        
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.7) 0%, rgba(30, 41, 59, 0.8) 100%);
            z-index: -1;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .slide-up {
            animation: slideUp 0.8s ease-out;
        }
        
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(50px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .input-focus {
            transition: all 0.3s ease;
        }
        
        .input-focus:focus {
            transform: scale(1.02);
            box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.4);
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .btn-gradient:hover {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            transform: translateY(-1px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
        }
        
        .btn-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: all 0.5s;
        }
        
        .btn-gradient:hover::before {
            left: 100%;
        }
        
        .error-shake {
            animation: shake 0.5s ease-in-out;
        }
        
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }
        
        .pulse-slow {
            animation: pulse 3s infinite;
        }
        
        .gradient-border {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2px;
            border-radius: 1rem;
        }
        
        .gradient-border-inner {
            background: white;
            border-radius: calc(1rem - 2px);
        }
        
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 50%;
            animation: particleFloat 20s infinite linear;
        }
        
        @keyframes particleFloat {
            0% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { transform: translateY(-10px) rotate(360deg); opacity: 0; }
        }
        
        .logo-glow {
            filter: drop-shadow(0 0 20px rgba(59, 130, 246, 0.3));
        }
        
        .welcome-text {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4 relative">

    <!-- Background Particles -->
    <div class="particles">
        <div class="particle" style="left: 10%; animation-delay: 0s; width: 4px; height: 4px;"></div>
        <div class="particle" style="left: 20%; animation-delay: 2s; width: 6px; height: 6px;"></div>
        <div class="particle" style="left: 30%; animation-delay: 4s; width: 3px; height: 3px;"></div>
        <div class="particle" style="left: 40%; animation-delay: 6s; width: 5px; height: 5px;"></div>
        <div class="particle" style="left: 50%; animation-delay: 8s; width: 4px; height: 4px;"></div>
        <div class="particle" style="left: 60%; animation-delay: 10s; width: 7px; height: 7px;"></div>
        <div class="particle" style="left: 70%; animation-delay: 12s; width: 3px; height: 3px;"></div>
        <div class="particle" style="left: 80%; animation-delay: 14s; width: 5px; height: 5px;"></div>
        <div class="particle" style="left: 90%; animation-delay: 16s; width: 4px; height: 4px;"></div>
    </div>

    <!-- Main Login Container -->
    <div class="w-full max-w-md slide-up">
        
        <!-- Welcome Header -->
        <div class="text-center mb-8">
            <div class="inline-block floating-animation">
                <div class="w-24 h-24 bg-white rounded-full shadow-2xl flex items-center justify-center mb-4 logo-glow p-2">
                    <!-- Replace with actual logo -->
                    <img src="assets/logo_kolej.png" alt="Logo Kolej Vokasional Kangar" class="w-full h-full object-contain rounded-full" 
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <!-- Fallback icon if image not found -->
                    <div class="w-full h-full bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center" style="display: none;">
                        <i class="fas fa-graduation-cap text-white text-3xl"></i>
                    </div>
                </div>
            </div>
            <h1 class="text-4xl font-bold welcome-text mb-2">Selamat Datang</h1>
            <p class="text-white text-lg opacity-90">Sistem Alumni Kolej Vokasional Kangar</p>
            <p class="text-white text-sm opacity-75 mt-1">Portal Rasmi Alumni & Staf</p>
        </div>

        <!-- Login Card -->
        <div class="glass-effect rounded-3xl shadow-2xl p-8 relative">
            
            <!-- Decorative Elements -->
            <div class="absolute -top-2 -right-2 w-4 h-4 accent-bg rounded-full opacity-60"></div>
            <div class="absolute -bottom-2 -left-2 w-3 h-3 bg-slate-400 rounded-full opacity-40"></div>
            
            <!-- Card Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-2xl shadow-lg mb-4 p-2 border border-gray-100">
                    <!-- Kolej Logo in card header -->
                    <img src="assets/logo_kolej.png" alt="Logo KVK" class="w-full h-full object-contain" 
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <!-- Fallback gradient icon -->
                    <div class="w-full h-full accent-bg rounded-xl flex items-center justify-center" style="display: none;">
                        <i class="fas fa-user text-white text-2xl"></i>
                    </div>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Log Masuk</h2>
                <p class="text-gray-600">Masukkan maklumat akaun anda</p>
                <div class="w-16 h-1 accent-bg mx-auto mt-3 rounded-full"></div>
            </div>

            <!-- Error Message -->
            <?php if (!empty($error)): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg error-shake">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                        <p class="text-red-700 font-medium"><?= $error ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <form method="POST" class="space-y-6" id="loginForm">
                
                <!-- Email Input -->
                <div class="relative">
                    <label for="emel" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 accent-color"></i>
                        Alamat Email
                    </label>
                    <div class="gradient-border">
                        <div class="gradient-border-inner">
                            <input type="email" 
                                   name="emel" 
                                   id="emel"
                                   class="w-full px-4 py-3 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 input-focus text-gray-800 placeholder-gray-500 bg-gray-50 focus:bg-white transition-colors"
                                   placeholder="contoh@email.com"
                                   required>
                        </div>
                    </div>
                    <div class="absolute right-3 top-11 text-gray-400">
                        <i class="fas fa-user"></i>
                    </div>
                </div>
                
                <!-- Password Input -->
                <div class="relative">
                    <label for="katalaluan" class="block text-sm font-semibold text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2 accent-color"></i>
                        Kata Laluan
                    </label>
                    <div class="gradient-border">
                        <div class="gradient-border-inner">
                            <input type="password" 
                                   name="katalaluan" 
                                   id="katalaluan"
                                   class="w-full px-4 py-3 pr-12 border-0 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 input-focus text-gray-800 placeholder-gray-500 bg-gray-50 focus:bg-white transition-colors"
                                   placeholder="Masukkan kata laluan"
                                   required>
                        </div>
                    </div>
                    <button type="button" 
                            class="absolute right-4 top-11 text-gray-400 hover:text-gray-600 transition-colors duration-300" 
                            onclick="togglePassword()">
                        <i id="toggle-icon" class="fas fa-eye-slash text-lg"></i>
                    </button>
                </div>

                <!-- Remember & Forgot -->
                <div class="flex items-center justify-between text-sm">
                    <label class="flex items-center space-x-2 text-gray-600">
                        <input type="checkbox" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 focus:ring-opacity-50">
                        <span>Ingat saya</span>
                    </label>
                    <a href="#" class="accent-color hover:text-blue-800 font-medium transition-colors duration-300">
                        Lupa kata laluan?
                    </a>
                </div>

                <!-- Login Button -->
                <button type="submit" 
                        class="w-full btn-gradient text-white font-bold py-4 rounded-xl shadow-lg focus:outline-none focus:ring-4 focus:ring-blue-500 focus:ring-opacity-30 text-lg">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Log Masuk Sekarang
                </button>

            </form>

            <!-- Social Login Divider -->
            <div class="my-8 flex items-center">
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
                <span class="px-4 text-sm text-gray-500 font-medium">atau</span>
                <div class="flex-1 h-px bg-gradient-to-r from-transparent via-gray-300 to-transparent"></div>
            </div>

            <!-- Quick Login Roles -->
            <div class="grid grid-cols-3 gap-3 mb-6">
                <button onclick="quickLogin('admin')" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-xl transition-all duration-300 text-center group border border-slate-200">
                    <i class="fas fa-user-shield text-2xl text-slate-600 mb-1 group-hover:scale-110 transition-transform group-hover:text-blue-600"></i>
                    <p class="text-xs font-medium text-slate-700 group-hover:text-blue-700">Admin</p>
                </button>
                <button onclick="quickLogin('guru')" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-xl transition-all duration-300 text-center group border border-slate-200">
                    <i class="fas fa-chalkboard-teacher text-2xl text-slate-600 mb-1 group-hover:scale-110 transition-transform group-hover:text-blue-600"></i>
                    <p class="text-xs font-medium text-slate-700 group-hover:text-blue-700">Pensyarah</p>
                </button>
                <button onclick="quickLogin('alumni')" class="p-3 bg-slate-50 hover:bg-slate-100 rounded-xl transition-all duration-300 text-center group border border-slate-200">
                    <i class="fas fa-graduation-cap text-2xl text-slate-600 mb-1 group-hover:scale-110 transition-transform group-hover:text-blue-600"></i>
                    <p class="text-xs font-medium text-slate-700 group-hover:text-blue-700">Alumni</p>
                </button>
            </div>

            <!-- Register Link -->
            <div class="text-center">
                <p class="text-gray-600 mb-4">Belum mempunyai akaun?</p>
                <a href="register.php" 
                   class="inline-flex items-center px-6 py-3 bg-white border border-slate-300 text-slate-700 font-semibold rounded-xl hover:bg-slate-50 hover:border-slate-400 transition-all duration-300 shadow-sm hover:shadow-md">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar Akaun Baru
                </a>
            </div>

        </div>

        <!-- Footer Info -->
        <div class="text-center mt-8">
            <div class="flex items-center justify-center mb-4">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-3">
                    <i class="fas fa-graduation-cap text-white"></i>
                </div>
                <p class="text-white font-semibold">Kolej Vokasional Kangar</p>
            </div>
            
            <p class="text-white opacity-75 text-xs mb-1">
                <i class="fas fa-map-marker-alt mr-1"></i>
                Jalan Sekolah Derma, 01000 Kangar, Perlis
            </p>
            <p class="text-white opacity-60 text-xs">
                &copy; 2025 Kolej Vokasional Kangar. Hak cipta terpelihara.
            </p>
        </div>
    </div>

    <script>
        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById("katalaluan");
            const icon = document.getElementById("toggle-icon");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
            } else {
                passwordInput.type = "password";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            }
        }

        // Quick login for demo purposes (remove in production)
        function quickLogin(role) {
            // This is for demo - in production, remove this function
            alert(`Quick login as ${role} - Feature ini hanya untuk demo. Gunakan email dan password yang betul.`);
        }

        // Form validation and enhancement
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const email = document.getElementById('emel').value;
            const password = document.getElementById('katalaluan').value;
            
            // Basic validation
            if (!email || !password) {
                e.preventDefault();
                showError('Sila isi semua ruangan yang diperlukan.');
                return;
            }
            
            // Email validation
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                e.preventDefault();
                showError('Format email tidak sah.');
                return;
            }
            
            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Memproses...';
            submitBtn.disabled = true;
            
            // Restore button after 3 seconds if form doesn't submit
            setTimeout(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            }, 3000);
        });

        function showError(message) {
            // Create or update error message
            let errorDiv = document.querySelector('.error-message');
            if (!errorDiv) {
                errorDiv = document.createElement('div');
                errorDiv.className = 'error-message mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-lg error-shake';
                errorDiv.innerHTML = `
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-triangle text-red-500 mr-3"></i>
                        <p class="text-red-700 font-medium">${message}</p>
                    </div>
                `;
                const form = document.getElementById('loginForm');
                form.parentNode.insertBefore(errorDiv, form);
            } else {
                errorDiv.querySelector('p').textContent = message;
                errorDiv.classList.add('error-shake');
            }
            
            // Remove shake animation after it completes
            setTimeout(() => {
                errorDiv.classList.remove('error-shake');
            }, 500);
        }

        // Enhanced input focus effects
        document.querySelectorAll('input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.parentElement.querySelector('label').style.color = '#3B82F6';
                this.classList.add('ring-2', 'ring-blue-500', 'ring-opacity-50');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.parentElement.querySelector('label').style.color = '#374151';
                this.classList.remove('ring-2', 'ring-blue-500', 'ring-opacity-50');
            });
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Alt + L to focus on login
            if (e.altKey && e.key === 'l') {
                e.preventDefault();
                document.getElementById('emel').focus();
            }
        });

        // Auto-hide error messages after 5 seconds
        if (document.querySelector('.error-shake')) {
            setTimeout(() => {
                const errorDiv = document.querySelector('.error-shake');
                if (errorDiv) {
                    errorDiv.style.opacity = '0';
                    errorDiv.style.transform = 'translateY(-10px)';
                    setTimeout(() => errorDiv.remove(), 300);
                }
            }, 5000);
        }
    </script>

</body>
</html>