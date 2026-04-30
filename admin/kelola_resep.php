<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$search = $_GET['search'] ?? '';
$search_safe = mysqli_real_escape_string($conn, $search);

if ($search != '') {
    $data = mysqli_query($conn, "SELECT * FROM resep
        WHERE nama_resep LIKE '%$search_safe%'
        OR bahan LIKE '%$search_safe%'
        OR langkah LIKE '%$search_safe%'
        ORDER BY id_resep DESC");
} else {
    $data = mysqli_query($conn, "SELECT * FROM resep ORDER BY id_resep DESC");
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Kelola Resep</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
    --navy: #0A1F44;
    --blue: #2563EB;
    --bg: #F1F5F9;
    --text: #1F2937;
    --muted: #64748B;
    --line: #E2E8F0;
}

body {
    margin: 0;
    background: var(--bg);
    font-family: 'Segoe UI', Arial, sans-serif;
    color: var(--text);
}

.topbar {
    background: var(--navy);
    color: white;
    padding: 15px 18px;
}

.page {
    max-width: 1180px;
    margin: 0 auto;
    padding: 24px 18px 50px;
}

.panel {
    background: white;
    border-radius: 18px;
    padding: 20px;
    box-shadow: 0 10px 26px rgba(0,0,0,0.08);
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 14px;
    margin-bottom: 18px;
}

.panel-header h5 {
    margin: 0;
    font-weight: 700;
}

.search-form {
    display: grid;
    grid-template-columns: 1fr 90px auto;
    gap: 10px;
    margin-bottom: 18px;
}

.search-form .form-control,
.search-form .btn {
    border-radius: 12px;
}

.table-responsive {
    border-radius: 14px;
    border: 1px solid var(--line);
    overflow-x: auto;
}

.table {
    margin-bottom: 0;
    vertical-align: middle;
}

.img-resep {
    width: 86px;
    height: 64px;
    object-fit: cover;
    border-radius: 10px;
    background: #E5E7EB;
}

.no-img {
    width: 86px;
    height: 64px;
    border-radius: 10px;
    background: #E5E7EB;
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
}

.text-limit {
    max-width: 260px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.aksi {
    display: flex;
    flex-wrap: wrap;
    gap: 6px;
}

.aksi .btn {
    border-radius: 10px;
}

.mobile-list {
    display: none;
}

.recipe-card {
    background: white;
    border: 1px solid var(--line);
    border-radius: 16px;
    overflow: hidden;
    margin-bottom: 14px;
}

.recipe-card-img,
.recipe-card-no-img {
    width: 100%;
    height: 170px;
}

.recipe-card-img {
    object-fit: cover;
    display: block;
}

.recipe-card-no-img {
    background: #E5E7EB;
    color: #64748B;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 38px;
}

.recipe-card-body {
    padding: 15px;
}

.recipe-card-body h5 {
    font-weight: 700;
    margin-bottom: 10px;
}

.recipe-card-body p {
    color: var(--muted);
    font-size: 14px;
    margin-bottom: 8px;
}

.recipe-card-actions {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 8px;
    margin-top: 12px;
}

.recipe-card-actions .btn {
    border-radius: 10px;
}

@media (max-width: 768px) {
    .topbar {
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: stretch !important;
    }

    .topbar .btn {
        width: 100%;
    }

    .page {
        padding: 18px 14px 38px;
    }

    .panel {
        padding: 16px;
        border-radius: 16px;
    }

    .panel-header {
        flex-direction: column;
        align-items: stretch;
    }

    .panel-header .btn {
        width: 100%;
    }

    .search-form {
        grid-template-columns: 1fr;
    }

    .desktop-table {
        display: none;
    }

    .mobile-list {
        display: block;
    }
}

@media (max-width: 420px) {
    .recipe-card-actions {
        grid-template-columns: 1fr;
    }
}
</style>
</head>

<body>

<div class="topbar d-flex justify-content-between align-items-center">
    <div>
        <strong>Kelola Resep</strong>
    </div>

    <a href="dashboard.php" class="btn btn-light btn-sm">
        Kembali
    </a>
</div>

<main class="page">

    <div class="panel">

        <div class="panel-header">
            <h5>Data Resep</h5>

            <a href="tambah_resep.php" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle"></i> Tambah Resep
            </a>
        </div>

        <form method="GET" class="search-form">
            <input type="text"
                   name="search"
                   class="form-control"
                   placeholder="Cari resep..."
                   value="<?= htmlspecialchars($search); ?>">

            <button type="submit" class="btn btn-primary">
                Cari
            </button>

            <?php if ($search != '') { ?>
                <a href="kelola_resep.php" class="btn btn-secondary">
                    Reset
                </a>
            <?php } ?>
        </form>

        <?php if (mysqli_num_rows($data) == 0) { ?>
            <div class="alert alert-info mb-0">
                Data resep belum tersedia.
            </div>
        <?php } ?>

        <?php if (mysqli_num_rows($data) > 0) { ?>

            <div class="desktop-table">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Resep</th>
                                <th>Bahan</th>
                                <th>Langkah</th>
                                <th style="min-width:170px;">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                        <?php
                        $no = 1;
                        mysqli_data_seek($data, 0);
                        while ($row = mysqli_fetch_assoc($data)) {
                        ?>
                            <tr>
                                <td><?= $no++; ?></td>

                                <td>
                                    <?php if (!empty($row['gambar']) && file_exists(__DIR__ . '/../assets/images/' . $row['gambar'])) { ?>
                                        <img src="../assets/images/<?= htmlspecialchars($row['gambar']); ?>"
                                             class="img-resep"
                                             alt="<?= htmlspecialchars($row['nama_resep']); ?>">
                                    <?php } else { ?>
                                        <div class="no-img">
                                            <i class="bi bi-image"></i>
                                        </div>
                                    <?php } ?>
                                </td>

                                <td>
                                    <strong><?= htmlspecialchars($row['nama_resep']); ?></strong>
                                </td>

                                <td>
                                    <div class="text-limit">
                                        <?= htmlspecialchars($row['bahan']); ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="text-limit">
                                        <?= htmlspecialchars($row['langkah']); ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="aksi">
                                        <a href="../karyawan/detail_resep.php?id=<?= $row['id_resep']; ?>"
                                           class="btn btn-primary btn-sm">
                                            Detail
                                        </a>

                                        <a href="edit_resep.php?id_resep=<?= $row['id_resep']; ?>"
                                           class="btn btn-warning btn-sm">
                                            Edit
                                        </a>

                                        <a href="hapus_resep.php?id_resep=<?= $row['id_resep']; ?>"
                                           onclick="return confirm('Yakin ingin menghapus resep ini?')"
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

            <div class="mobile-list">
                <?php
                mysqli_data_seek($data, 0);
                while ($row = mysqli_fetch_assoc($data)) {
                ?>
                    <div class="recipe-card">

                        <?php if (!empty($row['gambar']) && file_exists(__DIR__ . '/../assets/images/' . $row['gambar'])) { ?>
                            <img src="../assets/images/<?= htmlspecialchars($row['gambar']); ?>"
                                 class="recipe-card-img"
                                 alt="<?= htmlspecialchars($row['nama_resep']); ?>">
                        <?php } else { ?>
                            <div class="recipe-card-no-img">
                                <i class="bi bi-image"></i>
                            </div>
                        <?php } ?>

                        <div class="recipe-card-body">
                            <h5><?= htmlspecialchars($row['nama_resep']); ?></h5>

                            <p>
                                <strong>Bahan:</strong>
                                <?= htmlspecialchars(substr($row['bahan'], 0, 90)); ?>...
                            </p>

                            <p>
                                <strong>Langkah:</strong>
                                <?= htmlspecialchars(substr($row['langkah'], 0, 90)); ?>...
                            </p>

                            <div class="recipe-card-actions">
                                <a href="../karyawan/detail_resep.php?id=<?= $row['id_resep']; ?>"
                                   class="btn btn-primary btn-sm">
                                    Detail
                                </a>

                                <a href="edit_resep.php?id_resep=<?= $row['id_resep']; ?>"
                                   class="btn btn-warning btn-sm">
                                    Edit
                                </a>

                                <a href="hapus_resep.php?id_resep=<?= $row['id_resep']; ?>"
                                   onclick="return confirm('Yakin ingin menghapus resep ini?')"
                                   class="btn btn-danger btn-sm">
                                    Hapus
                                </a>
                            </div>
                        </div>

                    </div>
                <?php } ?>
            </div>

        <?php } ?>

    </div>

</main>

</body>
</html>
