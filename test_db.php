<?php
include("include/connection.php");

$sql = "SELECT * FROM admin";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<h2>✅ Database Connected & Data Admin</h2>";
    while($row = $result->fetch_assoc()) {
        echo "ID: " . $row["id_admin"]. " - Nama: " . $row["nama"]. " - Emel: " . $row["emel"]. "<br>";
    }
} else {
    echo "❌ Database kosong atau tak jumpa data.";
}

$conn->close();
?>
