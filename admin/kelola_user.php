<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

if (isset($_POST['tambah'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    mysqli_query($conn, "INSERT INTO users (nama, email, password, role)
        VALUES ('$nama', '$email', '$password', '$role')");

    header("Location: kelola_user.php");
    exit;
}

if (isset($_GET['hapus'])) {
    $id_user = intval($_GET['hapus']);

    mysqli_query($conn, "DELETE FROM users WHERE id_user=$id_user");

    header("Location: kelola_user.php");
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM users ORDER BY id_user DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Kelola User</title>

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

.card {
    border: none;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.btn-primary {
    background: linear-gradient(135deg, #1E3A8A, #3B82F6);
    border: none;
}

.table-responsive {
    border-radius: 12px;
    overflow-x: auto;
}

.table {
    background: white;
    margin-bottom: 0;
    vertical-align: middle;
}

.aksi {
    display: flex;
    gap: 6px;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .topbar {
        flex-direction: column;
        align-items: stretch !important;
        gap: 10px;
    }

    .topbar a {
        width: 100%;
    }

    .header-card {
        flex-direction: column;
        align-items: stretch !important;
        gap: 10px;
    }

    .header-card button {
        width: 100%;
    }

    .aksi .btn {
        width: 100%;
    }

    table {
        font-size: 14px;
    }
}
</style>
</head>

<body>

<div class="topbar d-flex justify-content-between align-items-center">
    <div>Kelola User</div>
    <a href="dashboard.php" class="btn btn-light btn-sm">Kembali</a>
</div>

<div class="container mt-4 mb-5">

    <div class="card p-3 p-md-4">

        <div class="header-card d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Data User</h5>

            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahModal">
                + Tambah User
            </button>
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th style="min-width:150px;">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($data)) { ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($row['nama']); ?></td>
                        <td><?= htmlspecialchars($row['email']); ?></td>
                        <td><?= htmlspecialchars($row['role']); ?></td>
                        <td>
                            <div class="aksi">
                                <a href="edit_user.php?id_user=<?= $row['id_user']; ?>"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="kelola_user.php?hapus=<?= $row['id_user']; ?>"
                                   onclick="return confirm('Yakin ingin menghapus user ini?')"
                                   class="btn btn-danger btn-sm">
                                    Hapus
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>

    </div>

</div>

<div class="modal fade" id="tambahModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="karyawan">Karyawan</option>
                        </select>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>
                    <button type="submit" name="tambah" class="btn btn-primary">
                        Simpan
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
