<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$id_user = intval($_GET['id_user'] ?? $_GET['id'] ?? 0);

$query = mysqli_query($conn, "SELECT * FROM users WHERE id_user=$id_user");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    echo "Data user tidak ditemukan";
    exit;
}

if (isset($_POST['update'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    if (!empty($_POST['password'])) {
        $password = mysqli_real_escape_string($conn, $_POST['password']);

        mysqli_query($conn, "UPDATE users SET
            nama='$nama',
            email='$email',
            password='$password',
            role='$role'
            WHERE id_user=$id_user");
    } else {
        mysqli_query($conn, "UPDATE users SET
            nama='$nama',
            email='$email',
            role='$role'
            WHERE id_user=$id_user");
    }

    echo "<script>
        alert('Data user berhasil diupdate');
        window.location='kelola_user.php';
    </script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Edit User</title>

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

.form-control,
.form-select {
    border-radius: 12px;
}

@media (max-width: 576px) {
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
    <div>Edit User</div>
    <a href="kelola_user.php" class="btn btn-light btn-sm">Kembali</a>
</div>

<div class="container mt-4 mb-5">

    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">

            <div class="form-card">

                <h4 class="mb-3">Form Edit User</h4>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text"
                               name="nama"
                               class="form-control"
                               value="<?= htmlspecialchars($user['nama']); ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               value="<?= htmlspecialchars($user['email']); ?>"
                               required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password Baru</label>
                        <input type="password"
                               name="password"
                               class="form-control"
                               placeholder="Kosongkan jika tidak ingin mengganti password">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select" required>
                            <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : ''; ?>>
                                Admin
                            </option>
                            <option value="karyawan" <?= $user['role'] == 'karyawan' ? 'selected' : ''; ?>>
                                Karyawan
                            </option>
                        </select>
                    </div>

                    <button type="submit" name="update" class="btn btn-primary w-100">
                        Update User
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>

</body>
</html>
