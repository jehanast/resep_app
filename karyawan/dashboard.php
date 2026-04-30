<?php
session_start();
include __DIR__ . '/../config/koneksi.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'karyawan') {
    header("Location: ../auth/login.php");
    exit;
}

$data = mysqli_query($conn, "SELECT * FROM resep ORDER BY id_resep DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Dashboard Karyawan</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

<style>
:root {
    --navy: #0A1F44;
    --blue: #2563EB;
    --blue2: #3B82F6;
    --white: #FFFFFF;
    --muted: #64748B;
    --line: #E2E8F0;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    min-height: 100vh;
    background: var(--navy);
    font-family: 'Segoe UI', Arial, sans-serif;
    color: #111827;
}

.app-header {
    background: var(--navy);
    color: white;
    border-bottom: 1px solid rgba(255,255,255,.08);
}

.header-inner {
    max-width: 1180px;
    margin: 0 auto;
    padding: 14px 18px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
}

.brand {
    display: flex;
    align-items: center;
    gap: 10px;
    min-width: 0;
}

.brand img {
    width: 34px;
    height: 34px;
    object-fit: contain;
    flex: 0 0 auto;
}

.brand span {
    font-size: 22px;
    font-weight: 700;
    line-height: 1.1;
}

.user-area {
    display: flex;
    align-items: center;
    gap: 10px;
    flex: 0 0 auto;
}

.user-name {
    color: white;
    font-size: 15px;
}

.page {
    max-width: 1180px;
    margin: 0 auto;
    padding: 28px 18px 48px;
}

.hero {
    background: linear-gradient(135deg, #1E3A8A, #3B82F6);
    color: white;
    border-radius: 18px;
    padding: 28px;
    margin-bottom: 22px;
    box-shadow: 0 10px 25px rgba(0,0,0,.18);
}

.hero h2 {
    margin: 0 0 8px;
    font-size: 30px;
    font-weight: 700;
}

.hero p {
    margin: 0;
    font-size: 16px;
}

.search-panel {
    background: white;
    border-radius: 18px;
    padding: 18px;
    margin-bottom: 22px;
    box-shadow: 0 10px 25px rgba(0,0,0,.14);
}

.search-form {
    display: grid;
    grid-template-columns: 1fr 96px 74px;
    gap: 10px;
}

.search-form .form-control {
    height: 48px;
    border-radius: 12px;
}

.search-form .btn {
    border-radius: 12px;
    font-weight: 600;
}

.category-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 22px;
}

.category-item {
    color: white;
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.22);
    padding: 8px 15px;
    border-radius: 999px;
    font-size: 14px;
}

.recipe-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 22px;
}

.recipe-card {
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 10px 24px rgba(0,0,0,.16);
    transition: .2s ease;
}

.recipe-card:hover {
    transform: translateY(-4px);
}

.recipe-img,
.no-img {
    width: 100%;
    height: 168px;
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
    font-size: 40px;
}

.recipe-body {
    padding: 16px;
    text-align: center;
}

.recipe-body h5 {
    margin: 0 0 12px;
    font-size: 17px;
    font-weight: 700;
}

.recipe-body .btn {
    border-radius: 12px;
    padding: 8px 16px;
}

.empty-box {
    grid-column: 1 / -1;
    background: white;
    padding: 28px;
    border-radius: 16px;
    text-align: center;
    color: var(--muted);
}

.modal-img {
    width: 100%;
    max-height: 300px;
    object-fit: cover;
    border-radius: 12px;
}

@media (max-width: 992px) {
    .recipe-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .header-inner {
        flex-direction: column;
        align-items: stretch;
        text-align: center;
        padding: 14px 16px;
    }

    .brand {
        justify-content: center;
    }

    .brand span {
        font-size: 20px;
    }

    .user-area {
        justify-content: center;
        flex-wrap: wrap;
    }

    .user-area .btn {
        width: 100%;
    }

    .page {
        padding: 22px 14px 38px;
    }

    .hero {
        padding: 22px;
        border-radius: 16px;
    }

    .hero h2 {
        font-size: 24px;
    }

    .hero p {
        font-size: 14px;
    }

    .search-form {
        grid-template-columns: 1fr;
    }

    .search-form .btn {
        width: 100%;
        height: 46px;
    }

    .category-list {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px;
    }

    .category-item {
        text-align: center;
    }
}

@media (max-width: 576px) {
    .recipe-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .recipe-img,
    .no-img {
        height: 185px;
    }

    .recipe-body {
        padding: 15px;
    }
}
</style>
</head>

<body>

<header class="app-header">
    <div class="header-inner">
        <div class="brand">
            <img src="../assets/logo.png" alt="Logo">
            <span>Seperdua Recipe</span>
        </div>

        <div class="user-area">
            <span class="user-name">
                <i class="bi bi-person-circle"></i>
                <?= htmlspecialchars($_SESSION['user']['nama']); ?>
            </span>
            <a href="../auth/logout.php" class="btn btn-danger btn-sm">Logout</a>
        </div>
    </div>
</header>

<main class="page">

    <section class="hero">
        <h2>Halo, <?= htmlspecialchars($_SESSION['user']['nama']); ?></h2>
        <p>Cari dan akses resep dengan cepat.</p>
    </section>

    <section class="search-panel">
    <form action="../tfidf/search.php" method="GET" class="search-form" id="searchForm">
        <input type="text"
               id="searchInput"
               name="q"
               class="form-control"
               placeholder="Cari resep..."
               required>

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-search"></i> Cari
        </button>

        <button type="button" onclick="startVoice()" class="btn btn-warning">
            <i class="bi bi-mic-fill"></i>
        </button>
    </form>
</section>


    <div class="category-list">
        <span class="category-item">Semua</span>
        <span class="category-item">Ayam</span>
        <span class="category-item">Burger</span>
        <span class="category-item">Snack</span>
    </div>

    <section class="recipe-grid">

        <?php if (mysqli_num_rows($data) == 0) { ?>
            <div class="empty-box">
                Belum ada resep yang tersedia.
            </div>
        <?php } ?>

        <?php while ($row = mysqli_fetch_assoc($data)) { ?>

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

                    <button class="btn btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modal<?= $row['id_resep']; ?>">
                        <i class="bi bi-eye"></i> Lihat
                    </button>
                </div>
            </div>

            <div class="modal fade" id="modal<?= $row['id_resep']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">
                                <?= htmlspecialchars($row['nama_resep']); ?>
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <?php if (!empty($row['gambar']) && file_exists(__DIR__ . '/../assets/images/' . $row['gambar'])) { ?>
                                <img src="../assets/images/<?= htmlspecialchars($row['gambar']); ?>"
                                     class="modal-img mb-3"
                                     alt="<?= htmlspecialchars($row['nama_resep']); ?>">
                            <?php } ?>

                            <h6>Bahan</h6>
                            <p><?= nl2br(htmlspecialchars($row['bahan'])); ?></p>

                            <h6>Langkah</h6>
                            <p><?= nl2br(htmlspecialchars($row['langkah'])); ?></p>
                        </div>

                        <div class="modal-footer">
                            <a href="cetak_pdf.php?id=<?= $row['id_resep']; ?>" class="btn btn-success">
                                <i class="bi bi-printer"></i> Cetak PDF
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>

    </section>

</main>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function startVoice() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        alert("Gunakan Google Chrome untuk fitur voice search");
        return;
    }

    const recognition = new SpeechRecognition();
    recognition.lang = "id-ID";
    recognition.interimResults = false;
    recognition.maxAlternatives = 1;

    recognition.onstart = function () {
        document.getElementById("searchInput").placeholder = "Mendengarkan suara...";
    };

    recognition.onresult = function (event) {
        const hasil = event.results[0][0].transcript;
        const input = document.getElementById("searchInput");

        input.value = hasil;

        window.location.href = "../tfidf/search.php?q=" + encodeURIComponent(hasil);
    };

    recognition.onerror = function (event) {
        alert("Voice error: " + event.error);
        document.getElementById("searchInput").placeholder = "Cari resep...";
    };

    recognition.onend = function () {
        document.getElementById("searchInput").placeholder = "Cari resep...";
    };

    recognition.start();
}
</script>

</body>
</html>
