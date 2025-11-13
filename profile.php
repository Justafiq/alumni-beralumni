<?php
session_start();
include("include/connection.php");

// Handle logout POST
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['role']) || !isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$role = $_SESSION['role'];
$user_id = $_SESSION['user_id'];

// Ambil data ikut role
if ($role == "alumni") {
    $sql = "SELECT * FROM alumni WHERE id_alumni = ?";
} elseif ($role == "guru") {
    $sql = "SELECT * FROM guru WHERE id_guru = ?";
} elseif ($role == "admin") {
    $sql = "SELECT * FROM admin WHERE id_admin = ?";
} else {
    die("Role tidak sah!");
}

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Kalau alumni, ambil maklumat kerjaya
$kerjaya = null;
if ($role == "alumni") {
    $sql2 = "SELECT * FROM info_kerjaya WHERE id_alumni = ? LIMIT 1";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("i", $user_id);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $kerjaya = $result2->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="UTF-8">
    <title>Profil <?php echo ucfirst($role); ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { width: 80%; max-width: 900px; margin: 30px auto; }
        .top-bar { display: flex; justify-content: space-between; margin-bottom: 15px; }
        .top-bar a, .top-bar form button {
            padding: 8px 15px; border-radius: 5px; text-decoration: none; color: #fff; border: none; cursor: pointer;
        }
        .btn-back { background: #6c757d; }
        .btn-back:hover { background: #5a6268; }
        .btn-logout { background: #dc3545; }
        .btn-logout:hover { background: #b02a37; }
        .card {
            background: #fff;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        h2 { margin-top: 0; color: #007BFF; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 10px; text-align: left; }
        th { width: 30%; background: #f8f8f8; }
    </style>
</head>
<body>
<div class="container">
    <div class="top-bar">
        <a href="dashboard.php" class="btn-back">← Back</a>
        <form method="POST" style="display:inline;">
            <button type="submit" name="logout" class="btn-logout">Log Keluar</button>
        </form>
    </div>

    <div class="card">
        <h2>Profil <?php echo ucfirst($role); ?></h2>
        <table>
            <?php if ($role == "alumni"): ?>
                <tr><th>Nama Penuh</th><td><?php echo $user['nama_penuh']; ?></td></tr>
                <tr><th>No IC</th><td><?php echo $user['no_ic']; ?></td></tr>
                <tr><th>Jantina</th><td><?php echo $user['jantina']; ?></td></tr>
                <tr><th>No Telefon</th><td><?php echo $user['no_telefon']; ?></td></tr>
                <tr><th>Tarikh Lahir</th><td><?php echo $user['tarikh_lahir']; ?></td></tr>
                <tr><th>Email</th><td><?php echo $user['emel']; ?></td></tr>
                <tr><th>Program</th><td><?php echo $user['program_diambil']; ?></td></tr>
                <tr><th>Tahun Masuk</th><td><?php echo $user['tahun_kemasukan']; ?></td></tr>
                <tr><th>Tahun Tamat</th><td><?php echo $user['tahun_tamat_pengajian']; ?></td></tr>
                <tr><th>CGPA</th><td><?php echo $user['cgpa']; ?></td></tr>
            <?php elseif ($role == "guru"): ?>
                <tr><th>Nama Penuh</th><td><?php echo $user['nama_penuh']; ?></td></tr>
                <tr><th>No KP</th><td><?php echo $user['no_kp']; ?></td></tr>
                <tr><th>Email</th><td><?php echo $user['emel']; ?></td></tr>
                <tr><th>No Telefon</th><td><?php echo $user['no_telefon']; ?></td></tr>
                <tr><th>Jawatan</th><td><?php echo $user['jawatan']; ?></td></tr>
                <tr><th>Bahagian</th><td><?php echo $user['bahagian']; ?></td></tr>
            <?php elseif ($role == "admin"): ?>
                <tr><th>Nama Penuh</th><td><?php echo $user['nama_penuh']; ?></td></tr>
                <tr><th>Email</th><td><?php echo $user['emel']; ?></td></tr>
                <tr><th>No Telefon</th><td><?php echo $user['no_telefon']; ?></td></tr>
            <?php endif; ?>
        </table>
    </div>

    <?php if ($role == "alumni" && $kerjaya): ?>
        <div class="card">
            <h2>Maklumat Kerjaya</h2>
            <table>
                <tr><th>Jawatan</th><td><?php echo $kerjaya['jawatan']; ?></td></tr>
                <tr><th>Syarikat</th><td><?php echo $kerjaya['nama_syarikat']; ?></td></tr>
                <tr><th>Industri</th><td><?php echo $kerjaya['industri']; ?></td></tr>
                <tr><th>Lokasi</th><td><?php echo $kerjaya['lokasi_kerja']; ?></td></tr>
                <tr><th>Tarikh Mula</th><td><?php echo $kerjaya['tarikh_mula']; ?></td></tr>
                <tr><th>Tarikh Tamat</th><td><?php echo $kerjaya['tarikh_tamat']; ?></td></tr>
                <tr><th>LinkedIn</th><td>
                    <?php if (!empty($kerjaya['pautan_linkedin'])): ?>
                        <a href="<?php echo $kerjaya['pautan_linkedin']; ?>" target="_blank">Profil LinkedIn</a>
                    <?php else: ?>
                        Tiada pautan
                    <?php endif; ?>
                </td></tr>
            </table>
        </div>
    <?php endif; ?>
</div>
</body>
</html>