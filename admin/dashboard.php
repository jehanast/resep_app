<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

$search = $_GET['search'] ?? '';
$search_safe = mysqli_real_escape_string($conn, $search);

$query = mysqli_query($conn, "SELECT * FROM resep 
    WHERE nama_resep LIKE '%$search_safe%'
    ORDER BY id_resep DESC");

$total_resep = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM resep"));
$total_user = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard Admin</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
    --navy: #0A1F44;
    --blue: #1E3A8A;
    --accent: #3B82F6;
    --bg: #F1F5F9;
    --text: #1F2937;
    --muted: #64748B;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    background: var(--bg);
    font-family: 'Segoe UI', Arial, sans-serif;
    color: var(--text);
}

.sidebar {
    width: 245px;
    height: 100vh;
    background: var(--navy);
    color: white;
    position: fixed;
    left: 0;
    top: 0;
    padding: 20px;
    z-index: 100;
}

.sidebar-brand {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 26px;
    font-weight: 700;
    font-size: 20px;
}

.sidebar-brand img {
    width: 38px;
    height: 38px;
    object-fit: contain;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 10px;
    color: #CBD5E1;
    text-decoration: none;
    padding: 12px 14px;
    border-radius: 12px;
    margin-bottom: 8px;
    transition: 0.2s;
}

.sidebar a:hover,
.sidebar a.active {
    background: var(--blue);
    color: white;
}

.content {
    margin-left: 245px;
    padding: 24px;
}

.topbar-mobile {
    display: none;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 16px;
    margin-bottom: 22px;
}

.page-header h3 {
    margin: 0;
    font-weight: 700;
}

.page-header p {
    margin: 5px 0 0;
    color: var(--muted);
}

.stat-grid {
    display: grid;
    grid-template-columns: repeat(2, minmax(0, 260px));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    color: white;
    border-radius: 18px;
    padding: 22px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.12);
}

.stat-card h6 {
    margin: 0 0 8px;
    opacity: .9;
}

.stat-card h2 {
    margin: 0;
    font-weight: 700;
}

.stat-blue {
    background: linear-gradient(135deg, #1E3A8A, #3B82F6);
}

.stat-green {
    background: linear-gradient(135deg, #059669, #10B981);
}

.search-card {
    background: white;
    border-radius: 18px;
    padding: 18px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.08);
    margin-bottom: 24px;
}

.search-card .form-control {
    height: 48px;
    border-radius: 12px 0 0 12px;
}

.search-card .btn {
    border-radius: 0 12px 12px 0;
}

.recipe-grid {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 22px;
}

.recipe-card {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 24px rgba(0,0,0,0.10);
    transition: .2s;
}

.recipe-card:hover {
    transform: translateY(-4px);
}

.recipe-img,
.no-img {
    width: 100%;
    height: 175px;
}

.recipe-img {
    object-fit: cover;
    display: block;
    background: #E5E7EB;
}

.no-img {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #E5E7EB;
    color: #64748B;
    font-size: 42px;
}

.recipe-body {
    padding: 16px;
}

.recipe-body h5 {
    font-size: 17px;
    font-weight: 700;
    margin-bottom: 14px;
}

.action-row {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.action-row .btn {
    border-radius: 10px;
}

.empty-box {
    grid-column: 1 / -1;
    background: white;
    padding: 28px;
    border-radius: 16px;
    text-align: center;
    color: var(--muted);
}

@media (max-width: 1100px) {
    .recipe-grid {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
}

@media (max-width: 768px) {
    .sidebar {
        display: none;
    }

    .topbar-mobile {
        display: block;
        background: var(--navy);
        color: white;
        padding: 14px 16px;
        position: sticky;
        top: 0;
        z-index: 90;
    }

    .mobile-brand {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .mobile-brand-left {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        font-size: 18px;
    }

    .mobile-brand-left img {
        width: 34px;
        height: 34px;
        object-fit: contain;
    }

    .mobile-menu {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
        margin-top: 12px;
    }

    .mobile-menu a {
        background: rgba(255,255,255,0.10);
        color: white;
        text-decoration: none;
        padding: 10px;
        border-radius: 10px;
        text-align: center;
        font-size: 14px;
    }

    .content {
        margin-left: 0;
        padding: 18px 14px 38px;
    }

    .page-header {
        flex-direction: column;
    }

    .stat-grid {
        grid-template-columns: 1fr;
    }

    .search-card {
        padding: 14px;
    }

    .recipe-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .recipe-img,
    .no-img {
        height: 185px;
    }

    .action-row .btn {
        flex: 1 1 calc(50% - 8px);
    }
}

@media (max-width: 420px) {
    .mobile-menu {
        grid-template-columns: 1fr;
    }

    .action-row .btn {
        flex: 1 1 100%;
    }
}
</style>
</head>

<body>

<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="../assets/logo.png" alt="Logo">
        <span>Seperdua</span>
    </div>

    <a href="dashboard.php" class="active">
        <i class="bi bi-house"></i> Dashboard
    </a>

    <a href="kelola_resep.php">
        <i class="bi bi-journal-text"></i> Kelola Resep
    </a>

    <a href="tambah_resep.php">
        <i class="bi bi-plus-circle"></i> Tambah Resep
    </a>

    <a href="kelola_user.php">
        <i class="bi bi-people"></i> Kelola User
    </a>

    <a href="../auth/logout.php">
        <i class="bi bi-box-arrow-right"></i> Logout
    </a>
</aside>

<div class="topbar-mobile">
    <div class="mobile-brand">
        <div class="mobile-brand-left">
            <img src="../assets/logo.png" alt="Logo">
            <span>Admin Panel</span>
        </div>
        <span><?= htmlspecialchars($_SESSION['user']['nama']); ?></span>
    </div>

    <div class="mobile-menu">
        <a href="dashboard.php">Dashboard</a>
        <a href="kelola_resep.php">Kelola Resep</a>
        <a href="tambah_resep.php">Tambah Resep</a>
        <a href="kelola_user.php">Kelola User</a>
        <a href="../auth/logout.php">Logout</a>
    </div>
</div>

<main class="content">

    <div class="page-header">
        <div>
            <h3>Halo, <?= htmlspecialchars($_SESSION['user']['nama']); ?></h3>
            <p>Selamat datang di sistem manajemen resep.</p>
        </div>
    </div>

    <section class="stat-grid">
        <div class="stat-card stat-blue">
            <h6>Total Resep</h6>
            <h2><?= $total_resep; ?></h2>
        </div>

        <div class="stat-card stat-green">
            <h6>Total User</h6>
            <h2><?= $total_user; ?></h2>
        </div>
    </section>

    <section class="search-card">
        <form method="GET">
            <div class="input-group">
                <input type="text"
                       name="search"
                       class="form-control"
                       placeholder="Cari resep..."
                       value="<?= htmlspecialchars($search); ?>">
                <button class="btn btn-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </section>

    <section class="recipe-grid">

        <?php if (mysqli_num_rows($query) == 0) { ?>
            <div class="empty-box">
                Resep tidak ditemukan.
            </div>
        <?php } ?>

        <?php while ($row = mysqli_fetch_assoc($query)) { ?>

            <div class="recipe-card">

                <?php if (!empty($row['gambar']) && file_exists(__DIR__ . '/../assets/images/' . $row['gambar'])) { ?>
                    <img src="../assets/images/<?= htmlspecialchars($row['gambar']); ?>"
                         class="recipe-img"
                         alt="<?= htmlspecialchars($row['nama_resep']); ?>">
                <?php } else { ?>
                    <div class="no-img">
                        <i class="bi bi-image"></i>
                    </div>
                <?php } ?>

                <div class="recipe-body">
                    <h5><?= htmlspecialchars($row['nama_resep']); ?></h5>

                
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

    </section>

</main>

</body>
</html>
