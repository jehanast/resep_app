<?php
include __DIR__ . '/../config/koneksi.php';

$q = trim(strtolower($_GET['q'] ?? ''));

$data = mysqli_query($conn, "SELECT * FROM resep");
$documents = [];

while ($row = mysqli_fetch_assoc($data)) {
    $text = strtolower($row['nama_resep'] . ' ' . $row['bahan'] . ' ' . $row['langkah']);

    $documents[] = [
        'id' => $row['id_resep'],
        'nama' => $row['nama_resep'],
        'gambar' => $row['gambar'],
        'text' => $text
    ];
}

$queryTerms = preg_split('/\s+/', $q);
$queryTerms = array_filter($queryTerms);

$results = [];
$totalDocs = count($documents);

foreach ($documents as $doc) {
    $score = 0;

    foreach ($queryTerms as $term) {
        $df = 0;

        foreach ($documents as $d) {
            if (strpos($d['text'], $term) !== false) {
                $df++;
            }
        }

        $tf = substr_count($doc['text'], $term);

        // Rumus IDF aman agar skor tidak negatif
        $idf = log(($totalDocs + 1) / ($df + 1)) + 1;

        $score += $tf * $idf;
    }

    if ($score > 0) {
        $doc['score'] = $score;
        $results[] = $doc;
    }
}

usort($results, function($a, $b) {
    return $b['score'] <=> $a['score'];
});
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Hasil Pencarian</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    background: #F1F5F9;
    font-family: 'Segoe UI', sans-serif;
}

.card {
    border: none;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.img-resep {
    width: 100%;
    height: 200px;
    object-fit: cover;
    background: #e5e7eb;
}

.no-img {
    height: 200px;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #64748b;
}
</style>
</head>

<body>

<div class="container my-4">

    <h4>Hasil pencarian: "<?= htmlspecialchars($q); ?>"</h4>

    <?php if ($q == '') { ?>
        <div class="alert alert-warning mt-3">
            Masukkan kata kunci pencarian.
        </div>
    <?php } elseif (count($results) == 0) { ?>
        <div class="alert alert-info mt-3">
            Resep tidak ditemukan.
        </div>
    <?php } ?>

    <div class="row g-4 mt-2">

        <?php foreach ($results as $row) { ?>
            <div class="col-12 col-sm-6 col-lg-4">
                <div class="card h-100">

                    <?php if (!empty($row['gambar']) && file_exists(__DIR__ . '/../assets/images/' . $row['gambar'])) { ?>
                        <img src="../assets/images/<?= htmlspecialchars($row['gambar']); ?>"
                             class="img-resep"
                             alt="<?= htmlspecialchars($row['nama']); ?>">
                    <?php } else { ?>
                        <div class="no-img">
                            Tidak ada gambar
                        </div>
                    <?php } ?>

                    <div class="card-body">
                        <h5><?= htmlspecialchars($row['nama']); ?></h5>

                        <small class="text-muted">
                            Score: <?= round($row['score'], 3); ?>
                        </small>
                        <br>

                        <a href="../karyawan/detail_resep.php?id=<?= $row['id']; ?>"
                           class="btn btn-primary btn-sm mt-3">
                            Detail
                        </a>
                    </div>

                </div>
            </div>
        <?php } ?>

    </div>

    <a href="../karyawan/dashboard.php" class="btn btn-secondary mt-4">
        Kembali ke Beranda
    </a>

</div>

</body>
</html>
