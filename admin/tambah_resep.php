<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST['simpan'])) {
    $nama_resep = mysqli_real_escape_string($conn, $_POST['nama_resep']);
    $bahan = mysqli_real_escape_string($conn, $_POST['bahan']);
    $langkah = mysqli_real_escape_string($conn, $_POST['langkah']);

    $nama_gambar = '';

    if (!empty($_FILES['gambar']['name'])) {
        $gambar = $_FILES['gambar']['name'];
        $tmp = $_FILES['gambar']['tmp_name'];

        $ext = strtolower(pathinfo($gambar, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png'];

        if (!in_array($ext, $allowed)) {
            echo "<script>
                alert('Format gambar harus JPG, JPEG, atau PNG');
                window.history.back();
            </script>";
            exit;
        }

        $nama_gambar = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $gambar);

        move_uploaded_file($tmp, "../assets/images/" . $nama_gambar);
    }

    mysqli_query($conn, "INSERT INTO resep 
        (nama_resep, bahan, langkah, gambar, id_kategori, id_user)
        VALUES 
        ('$nama_resep', '$bahan', '$langkah', '$nama_gambar', NULL, NULL)");

    echo "<script>
        alert('Resep berhasil ditambahkan');
        window.location='dashboard.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Tambah Resep</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #F1F5F9;
    font-family: 'Poppins', sans-serif;
}

.topbar {
    background: #0A1F44;
    color: white;
    padding: 15px;
}

.form-card {
    background: white;
    border-radius: 18px;
    padding: 25px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #1E3A8A, #3B82F6);
    border: none;
    border-radius: 25px;
}

.form-control {
    border-radius: 12px;
}

.preview {
    width: 100%;
    max-height: 220px;
    object-fit: cover;
    border-radius: 12px;
    display: none;
}

@media(max-width: 576px) {
    .topbar {
        flex-direction: column;
        align-items: stretch !important;
        gap: 10px;
    }

    .topbar a {
        width: 100%;
    }

    .form-card {
        padding: 20px;
    }
}
</style>
</head>

<body>

<div class="topbar d-flex justify-content-between align-items-center">
    <div>Tambah Resep</div>
    <a href="dashboard.php" class="btn btn-light btn-sm">Kembali</a>
</div>

<div class="container mt-4 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">

            <div class="form-card">
                <h4 class="mb-3">Form Tambah Resep</h4>

                <form method="POST" enctype="multipart/form-data">

                    <div class="mb-3">
                        <label class="form-label">Nama Resep</label>
                        <input type="text" name="nama_resep" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bahan</label>
                        <textarea name="bahan" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Langkah</label>
                        <textarea name="langkah" class="form-control" rows="5" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Gambar</label>
                        <input type="file"
                               name="gambar"
                               id="gambar"
                               class="form-control"
                               accept=".jpg,.jpeg,.png"
                               onchange="previewImage(event)">
                    </div>

                    <img id="preview" class="preview mb-3">

                    <button type="submit" name="simpan" class="btn btn-primary w-100">
                        Simpan Resep
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

<script>
function previewImage(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('preview');

    if (!file) {
        preview.style.display = 'none';
        return;
    }

    preview.src = URL.createObjectURL(file);
    preview.style.display = 'block';
}
</script>

</body>
</html>
