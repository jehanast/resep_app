<?php
session_start();

// Hapus semua session
session_unset();
session_destroy();

// Hapus cache biar tidak bisa back
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Location: ../index.php?logout=success");

// Redirect ke landing page
header("Location: ../index.php");
exit;
?>