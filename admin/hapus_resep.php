<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id_resep = intval($_GET['id_resep'] ?? $_GET['id'] ?? 0);

if ($id_resep > 0) {
    mysqli_query($conn, "DELETE FROM resep WHERE id_resep=$id_resep");
}

header("Location: dashboard.php");
exit;
?>
