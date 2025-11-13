<?php
session_start();
include("../include/connection.php");

// Pastikan user login
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

$user_id   = $_SESSION['user_id'];
$user_role = $_SESSION['role'];

// ✅ Tambah pengumuman (admin/guru sahaja)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add']) && in_array($user_role, ['admin','guru'])) {
    $tajuk    = trim($_POST['tajuk']);
    $kandungan = trim($_POST['kandungan']);
    $kategori = $_POST['kategori'] ?? 'Lain-lain';
    $tarikh_tamat = !empty($_POST['tarikh_tamat']) ? $_POST['tarikh_tamat'] : null;

    $gambar = null;
    if (!empty($_FILES['gambar']['name'])) {
        $targetDir = "../uploads/";
        if (!is_dir($targetDir)) mkdir($targetDir);
        $fileName = time() . "_" . basename($_FILES['gambar']['name']);
        $targetFile = $targetDir . $fileName;
        if (move_uploaded_file($_FILES['gambar']['tmp_name'], $targetFile)) {
            $gambar = $fileName;
        }
    }

    $sql = "INSERT INTO announcements (tajuk, kandungan, pengirim_id, pengirim_role, tarikh, tarikh_tamat, gambar, kategori) 
            VALUES (?, ?, ?, ?, NOW(), ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssissss", $tajuk, $kandungan, $user_id, $user_role, $tarikh_tamat, $gambar, $kategori);
    $stmt->execute();

    header("Location: announcements.php");
    exit();
}

// ✅ Padam pengumuman (admin/guru sahaja)
if (isset($_GET['delete']) && in_array($user_role, ['admin','guru'])) {
    $id = intval($_GET['delete']);
    $stmt = $conn->prepare("DELETE FROM announcements WHERE id_announcement = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    header("Location: announcements.php");
    exit();
}

// ✅ Ambil semua pengumuman
$result = $conn->query("SELECT a.*, u.nama FROM announcements a 
                        LEFT JOIN users u ON a.pengirim_id = u.id_user 
                        ORDER BY a.tarikh DESC");
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <title>Pengumuman</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex h-screen">

  <!-- Sidebar -->
  <aside class="w-64 bg-white shadow-xl p-4 flex flex-col">
    <h2 class="text-xl font-bold mb-6">📢 <?= ucfirst($user_role) ?> Panel</h2>
    <a href="<?= $user_role ?>_dashboard.php" class="mb-2">🏠 Dashboard</a>
    <a href="profile.php" class="mb-2">👤 Profil</a>
    <a href="announcements.php" class="mb-2 font-bold text-blue-600">📢 Pengumuman</a>
  </aside>

  <!-- Main -->
  <main class="flex-1 p-6 overflow-y-auto">
    <h1 class="text-2xl font-bold mb-6">Pengumuman Rasmi</h1>

    <?php if (in_array($user_role, ['admin','guru'])): ?>
      <!-- Form tambah -->
      <form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow mb-6 space-y-3">
        <input type="text" name="tajuk" placeholder="Tajuk" class="w-full border p-2" required>
        <textarea name="kandungan" placeholder="Isi pengumuman..." class="w-full border p-2" required></textarea>
        
        <select name="kategori" class="border p-2">
          <option value="Akademik">Akademik</option>
          <option value="Event">Event</option>
          <option value="Aktiviti">Aktiviti</option>
          <option value="Lain-lain">Lain-lain</option>
        </select>

        <input type="datetime-local" name="tarikh_tamat" class="border p-2">
        <input type="file" name="gambar" class="border p-2">
        
        <button type="submit" name="add" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah</button>
      </form>
    <?php endif; ?>

    <!-- Senarai pengumuman -->
    <?php while ($row = $result->fetch_assoc()): ?>
      <div class="bg-white rounded-xl shadow-lg p-6 mb-4">
        <?php if (!empty($row['gambar'])): ?>
          <img src="../uploads/<?= htmlspecialchars($row['gambar']) ?>" class="w-full h-48 object-cover rounded mb-4">
        <?php endif; ?>
        
        <h2 class="text-xl font-bold"><?= htmlspecialchars($row['tajuk']) ?></h2>
        <p class="text-gray-600 mt-2"><?= nl2br(htmlspecialchars($row['kandungan'])) ?></p>
        <p class="text-sm text-gray-500 mt-2">📅 <?= date("d M Y", strtotime($row['tarikh'])) ?>
           <?php if ($row['tarikh_tamat']): ?> → Tamat <?= date("d M Y", strtotime($row['tarikh_tamat'])) ?><?php endif; ?>
        </p>
        <p class="text-sm text-gray-400">👤 Oleh: <?= htmlspecialchars($row['nama'] ?? $row['pengirim_role']) ?></p>

        <?php if (in_array($user_role, ['admin','guru'])): ?>
          <a href="?delete=<?= $row['id_announcement'] ?>" onclick="return confirm('Padam pengumuman ini?')" class="text-red-500">🗑 Padam</a>
        <?php endif; ?>
      </div>
    <?php endwhile; ?>
  </main>
</body>
</html>
