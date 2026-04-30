<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

// 🔐 CEK ADMIN
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

// AMBIL DATA
$id = intval($_GET['id'] ?? 0);
$data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM resep WHERE id_resep=$id"));

if (!$data) {
    echo "Data tidak ditemukan";
    exit;
}

// PROSES UPDATE
if (isset($_POST['update'])) {

    $nama = $_POST['nama'];
    $bahan = $_POST['bahan'];
    $langkah = $_POST['langkah'];

    $gambar_lama = $data['gambar'];
    $file = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    if ($file) {
        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png'];

        if (in_array($ext, $allowed)) {

            $nama_file = time().'.'.$ext;
            move_uploaded_file($tmp, "../assets/img/".$nama_file);

            // hapus gambar lama
            if ($gambar_lama && file_exists("../assets/img/".$gambar_lama)) {
                unlink("../assets/img/".$gambar_lama);
            }

        } else {
            echo "<script>alert('Format gambar harus JPG/PNG');</script>";
            exit;
        }

    } else {
        $nama_file = $gambar_lama;
    }

    mysqli_query($conn, "UPDATE resep SET 
        nama_resep='$nama',
        bahan='$bahan',
        langkah='$langkah',
        gambar='$nama_file'
        WHERE id_resep=$id
    ");

    echo "<script>alert('Berhasil diupdate');window.location='dashboard.php';</script>";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Resep</title>

<meta name="viewport" content="width=device-width, initial-scale=1">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background:#F1F5F9;
    font-family:'Poppins', sans-serif;
}

.topbar {
    background:#0A1F44;
    color:white;
    padding:15px;
}

.form-card {
    background:white;
    padding:25px;
    border-radius:20px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);
}

.btn-primary {
    background:linear-gradient(135deg,#1E3A8A,#3B82F6);
    border:none;
    border-radius:25px;
}

.preview {
    width:100%;
    max-height:200px;
    object-fit:cover;
    border-radius:10px;
}
</style>

</head>

<body>

<div class="topbar d-flex justify-content-between">
    <div>✏️ Edit Resep</div>
    <a href="dashboard.php" class="btn btn-light btn-sm">← Kembali</a>
</div>

<div class="container mt-4">

    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="form-card">

                <h4 class="mb-3">Edit Resep</h4>

                <form method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label>Nama Resep</label>
                        <input type="text" name="nama" class="form-control" value="<?= $data['nama_resep']; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label>Bahan</label>
                        <textarea name="bahan" class="form-control" rows="3"><?= $data['bahan']; ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Langkah</label>
                        <textarea name="langkah" class="form-control" rows="3"><?= $data['langkah']; ?></textarea>
                    </div>

                    <!-- GAMBAR LAMA -->
                    <div class="mb-3">
                        <label>Gambar Saat Ini</label><br>
                        <img src="../assets/img/<?= $data['gambar']; ?>" class="preview mb-2">
                    </div>

                    <!-- UPLOAD BARU -->
                    <div class="mb-3">
                        <label>Ganti Gambar</label>
                        <input type="file" name="gambar" class="form-control" onchange="previewImage(event)">
                    </div>

                    <!-- PREVIEW BARU -->
                    <img id="preview" class="preview mb-3" style="display:none;">

                    <button name="update" class="btn btn-primary w-100">
                        Update Resep
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>

<script>
function previewImage(event){
    const img = document.getElementById('preview');
    img.src = URL.createObjectURL(event.target.files[0]);
    img.style.display = 'block';
}
</script>

</body>
</html>