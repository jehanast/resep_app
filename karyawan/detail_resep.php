<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

// 🔐 CEK LOGIN
if (!isset($_SESSION['user'])) {
    header("Location: ../auth/login.php");
    exit;
}

// AMANKAN ID
$id = intval($_GET['id'] ?? 0);

$query = mysqli_query($conn, "SELECT * FROM resep WHERE id_resep=$id");
$data = mysqli_fetch_assoc($query);

if (!$data) {
    echo "Data tidak ditemukan";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Detail Resep</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>

<body>

<div class="container mt-4 fade-in">

    <!-- 🔙 BUTTON -->
    <a href="dashboard.php" class="btn btn-primary btn-sm mb-3">
        ← Kembali
    </a>

    <div class="card shadow p-4">

        <h2 class="mb-3"><?= $data['nama_resep']; ?></h2>

        <!-- GAMBAR -->
        <img src="../assets/img/<?= $data['gambar']; ?>" 
             class="img-fluid mb-3 rounded"
             style="max-height:300px; object-fit:cover;">

        <!-- BAHAN -->
        <h5>🧂 Bahan</h5>
        <p><?= nl2br($data['bahan']); ?></p>

        <!-- LANGKAH -->
        <h5>👨‍🍳 Langkah</h5>
        <p><?= nl2br($data['langkah']); ?></p>

        <!-- PDF -->
        <a href="cetak_pdf.php?id=<?= $row['id_resep']; ?>" class="btn btn-success">
    <i class="bi bi-printer"></i> Cetak PDF
</a>


    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>