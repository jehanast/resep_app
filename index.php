<?php session_start(); ?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>Seperdua Recipe</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">
<link href="assets/style.css" rel="stylesheet">

<style>
:root {
    --navy: #0A1F44;
    --navy2: #1E3A8A;
    --accent: #3B82F6;
}

body {
    font-family: 'Segoe UI', sans-serif;
    background: #F1F5F9;
}

.hero {
    min-height: 90vh;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    color: white;
    padding: 80px 16px;
    background:
        linear-gradient(135deg, rgba(10,31,68,0.78), rgba(30,58,138,0.65)),
        url('assets/lp.jpg') center/cover no-repeat;
}

.hero-content {
    width: 100%;
    max-width: 720px;
    background: rgba(10,31,68,0.48);
    padding: 42px;
    border-radius: 20px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.14);
    box-shadow: 0 12px 30px rgba(0,0,0,0.3);
}

.hero-content h1 {
    font-weight: 800;
}

.btn-main {
    background: linear-gradient(135deg, var(--navy2), var(--accent));
    border: none;
    border-radius: 30px;
    padding: 12px 30px;
    color: white;
}

.btn-main:hover {
    transform: translateY(-2px);
    color: white;
}

.feature-box {
    height: 100%;
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    transition: .3s;
}

.feature-box:hover {
    transform: translateY(-6px);
}

.search-box {
    max-width: 700px;
    margin: 0 auto;
    background: white;
    padding: 20px;
    border-radius: 18px;
    box-shadow: 0 8px 22px rgba(0,0,0,0.1);
}

.search-form {
    display: grid;
    grid-template-columns: 1fr 95px 70px;
    gap: 10px;
}

.search-form input {
    height: 48px;
    border-radius: 12px;
}

.search-form button {
    border-radius: 12px;
    font-weight: 600;
}

@media(max-width: 768px) {
    .hero {
        min-height: 75vh;
        padding: 60px 16px;
    }

    .hero-content {
        padding: 28px 22px;
        border-radius: 16px;
    }

    .hero-content h1 {
        font-size: 32px;
    }

    .hero-content .lead {
        font-size: 16px;
    }

    .search-form {
        grid-template-columns: 1fr;
    }

    .search-form button {
        width: 100%;
        height: 46px;
    }
}

@media(max-width: 576px) {
    .hero-content h1 {
        font-size: 28px;
    }

    .feature-box {
        padding: 20px;
    }
}
</style>
</head>

<body>

<?php if (isset($_GET['logout'])) { ?>
<div class="alert alert-success text-center m-0">
    Berhasil logout
</div>
<?php } ?>

<?php include __DIR__ . '/layout/navbar.php'; ?>

<section class="hero">
    <div class="hero-content" data-aos="fade-up">

        <h1 class="display-4">Smart Recipe System</h1>

        <p class="lead text-light mt-3">
            Temukan resep terbaik dengan pencarian cerdas dan voice command.
        </p>

        <?php if (isset($_SESSION['user'])) { ?>

            <?php if ($_SESSION['user']['role'] == 'admin') { ?>
                <a href="admin/dashboard.php" class="btn btn-main mt-3">
                    Masuk Dashboard
                </a>
            <?php } else { ?>
                <a href="karyawan/dashboard.php" class="btn btn-main mt-3">
                    Masuk Dashboard
                </a>
            <?php } ?>

        <?php } else { ?>
            <a href="auth/login.php" class="btn btn-main mt-3">
                Mulai Sekarang
            </a>
        <?php } ?>

    </div>
</section>

<section id="fitur" class="py-5 bg-light">
    <div class="container text-center">

        <h2 data-aos="fade-up">Fitur Unggulan</h2>

        <div class="row g-4 mt-3">

            <div class="col-md-4" data-aos="fade-up">
                <div class="feature-box">
                    <h3>🔍</h3>
                    <h5>Smart Search</h5>
                    <p class="mb-0">TF-IDF untuk hasil pencarian akurat.</p>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="feature-box">
                    <h3>🎤</h3>
                    <h5>Voice Command</h5>
                    <p class="mb-0">Cari resep tanpa menyentuh layar.</p>
                </div>
            </div>

            <div class="col-md-4" data-aos="fade-up" data-aos-delay="400">
                <div class="feature-box">
                    <h3>📱</h3>
                    <h5>Responsive</h5>
                    <p class="mb-0">Bisa diakses di semua perangkat.</p>
                </div>
            </div>

        </div>

    </div>
</section>

<section class="py-5">
    <div class="container text-center" data-aos="zoom-in">

        <h3>Cari Resep Cepat</h3>
        <p class="text-muted">Ketik kata kunci atau gunakan voice search.</p>

        <div class="search-box mt-3">
            <form action="tfidf/search.php" method="GET" class="search-form">
                <input type="text"
                       id="searchInput"
                       name="q"
                       class="form-control"
                       placeholder="Contoh: ayam goreng"
                       required>

                <button type="submit" class="btn btn-primary">
                    Cari
                </button>

                <button type="button" onclick="startVoice()" class="btn btn-warning">
                    🎤
                </button>
            </form>
        </div>

    </div>
</section>

<?php include __DIR__ . '/layout/footer.php'; ?>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>

<script>
AOS.init({
    duration: 800,
    once: true
});

function startVoice() {
    const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;

    if (!SpeechRecognition) {
        alert('Browser belum mendukung voice search.');
        return;
    }

    const recognition = new SpeechRecognition();
    recognition.lang = 'id-ID';

    recognition.onresult = function(event) {
        const text = event.results[0][0].transcript;
        const input = document.getElementById('searchInput');

        input.value = text;
        window.location.href = 'tfidf/search.php?q=' + encodeURIComponent(text);
    };

    recognition.onerror = function() {
        alert('Voice search gagal. Coba ulangi lagi.');
    };

    recognition.start();
}
</script>

</body>
</html>
