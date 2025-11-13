<?php
session_start();
include("../include/connection.php");

// pastikan login
if (!isset($_SESSION['role'])) {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Post tidak dijumpai!");
}

$id_forum = intval($_GET['id']);

// ambil detail post
$sql = "SELECT * FROM forum WHERE id_forum = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_forum);
$stmt->execute();
$post = $stmt->get_result()->fetch_assoc();

if (!$post) {
    die("Post tidak dijumpai!");
}

// ambil reply
$sql_reply = "SELECT * FROM forum_reply WHERE id_forum = ? ORDER BY created_at ASC";
$stmt = $conn->prepare($sql_reply);
$stmt->bind_param("i", $id_forum);
$stmt->execute();
$replies = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="ms">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($post['tajuk']); ?> - Forum</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

  <!-- Navbar -->
  <nav class="bg-white shadow-md p-4 flex justify-between items-center">
    <a href="guru_dashboard.php" class="text-blue-600 font-bold">⬅ Back</a>
    <h1 class="font-bold">Forum Post</h1>
  </nav>

  <!-- Post -->
  <main class="container mx-auto px-4 py-6 max-w-3xl">
    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
      <?php if (!empty($post['gambar'])): ?>
        <img src="<?php echo htmlspecialchars($post['gambar']); ?>" class="w-full h-64 object-cover">
      <?php endif; ?>
      <div class="p-6">
        <h2 class="text-2xl font-bold mb-2"><?php echo htmlspecialchars($post['tajuk']); ?></h2>
        <p class="text-gray-700 mb-4"><?php echo nl2br(htmlspecialchars($post['kandungan'])); ?></p>
        <small class="text-gray-500">Posted on <?php echo $post['created_at']; ?></small>
      </div>
    </div>

    <!-- Replies -->
    <h3 class="text-xl font-semibold mb-4">💬 Komen</h3>
    <div class="space-y-4">
      <?php if (mysqli_num_rows($replies) > 0): ?>
        <?php while($r = mysqli_fetch_assoc($replies)): ?>
          <div class="bg-white p-4 rounded-lg shadow">
            <p class="text-gray-800"><?php echo nl2br(htmlspecialchars($r['reply_text'])); ?></p>
            <small class="text-gray-500">Posted at <?php echo $r['created_at']; ?></small>
          </div>
        <?php endwhile; ?>
      <?php else: ?>
        <p class="text-gray-500">There’s no one here yet, be the first to comment!</p>
      <?php endif; ?>
    </div>
  </main>

</body>
</html>
