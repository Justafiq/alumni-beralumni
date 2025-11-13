<?php
session_start();
include("include/connection.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nama = trim($_POST['nama']);
    $emel = trim($_POST['emel']);
    $password = $_POST['katalaluan'];
    $confirm = $_POST['confirm_katalaluan'];
    $role = $_POST['role'] === 'guru' ? 'guru' : 'alumni';
    $tahun_alumni = !empty($_POST['tahun_alumni']) ? intval($_POST['tahun_alumni']) : null;

    if ($password !== $confirm) {
        $message = "❌ Kata laluan tidak sama.";
    } else {
        $check = $conn->prepare("SELECT id_user FROM users WHERE emel = ? LIMIT 1");
        $check->bind_param("s", $emel);
        $check->execute();
        $res = $check->get_result();

        if ($res->num_rows > 0) {
            $message = "⚠️ Emel sudah digunakan.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);

            if ($role === "alumni") {
                $sql = "INSERT INTO users (nama, emel, katalaluan, tahun_alumni, role) 
                        VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssis", $nama, $emel, $hash, $tahun_alumni, $role);
            } else {
                $sql = "INSERT INTO users (nama, emel, katalaluan, role) 
                        VALUES (?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $nama, $emel, $hash, $role);
            }

            if ($stmt->execute()) {
                $_SESSION['success'] = "✅ Pendaftaran berjaya! Sila log masuk.";
                header("Location: login.php");
                exit();
            } else {
                $message = "❌ Ralat semasa mendaftar.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akaun</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #3363c2d8; }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen p-4">

<div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-6 text-center">
    <!-- 🏫 LOGO -->
    <img src="assets/logo_kolej.png" alt="Logo Kolej Vokasional Kangar" 
         class="w-20 h-20 mx-auto mb-3 object-contain">
         
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Daftar Akaun</h1>

    <?php if (!empty($message)): ?>
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            <?= $message ?>
        </div>
    <?php endif; ?>

    <form method="POST" class="space-y-4 text-left">
        <div>
            <label class="block font-semibold">Nama</label>
            <input type="text" name="nama" required class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block font-semibold">Emel</label>
            <input type="email" name="emel" required class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block font-semibold">Kata Laluan</label>
            <input type="password" name="katalaluan" required class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block font-semibold">Sahkan Kata Laluan</label>
            <input type="password" name="confirm_katalaluan" required class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
        </div>

        <div>
            <label class="block font-semibold">Daftar Sebagai</label>
            <select name="role" id="role" onchange="toggleAlumniField()" class="w-full border rounded p-2">
                <option value="alumni">Alumni</option>
                <option value="guru">Guru</option>
            </select>
        </div>

        <!-- Tahun Alumni Auto Dropdown -->
        <div id="tahunAlumniField">
            <label class="block font-semibold">Tahun Alumni</label>
            <select name="tahun_alumni" class="w-full border rounded p-2 focus:ring focus:ring-blue-200">
                <option value="">-- Pilih Tahun --</option>
                <?php
                    $tahun_sekarang = date("Y");
                    for ($tahun = $tahun_sekarang; $tahun >= 2009; $tahun--) {
                        echo "<option value='$tahun'>$tahun</option>";
                    }
                ?>
            </select>
            <p class="text-gray-500 text-sm mt-1">Kolej mula beroperasi dari tahun 2009.</p>
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Daftar
        </button>
    </form>

    <p class="text-center text-sm mt-4">
        Sudah ada akaun? <a href="login.php" class="text-blue-600 hover:underline">Log Masuk</a>
    </p>
</div>

<script>
function toggleAlumniField() {
    const role = document.getElementById("role").value;
    const tahun = document.getElementById("tahunAlumniField");
    tahun.style.display = role === "alumni" ? "block" : "none";
}
toggleAlumniField();
</script>
</body>
</html>
