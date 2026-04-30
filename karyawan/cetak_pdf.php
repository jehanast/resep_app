<?php
require('../fpdf/fpdf.php');
include __DIR__ . '/../config/koneksi.php';

$id_resep = intval($_GET['id'] ?? $_GET['id_resep'] ?? 0);

if ($id_resep <= 0) {
    die('ID resep tidak valid');
}

$query = mysqli_query($conn, "SELECT * FROM resep WHERE id_resep=$id_resep");
$row = mysqli_fetch_assoc($query);

if (!$row) {
    die('Data resep tidak ditemukan');
}

class PDF extends FPDF
{
    function Header()
    {
        $this->SetFillColor(10, 31, 68);
        $this->Rect(0, 0, 210, 28, 'F');

        $this->SetTextColor(255, 255, 255);
        $this->SetFont('Arial', 'B', 15);
        $this->Cell(0, 10, 'SEPERDUA RECIPE', 0, 1, 'C');

        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 6, 'Sistem Informasi Resep', 0, 1, 'C');

        $this->Ln(10);
    }

    function Footer()
    {
        $this->SetY(-18);
        $this->SetTextColor(120, 120, 120);
        $this->SetFont('Arial', 'I', 9);
        $this->Cell(0, 10, 'Dicetak oleh Seperdua Recipe | Halaman ' . $this->PageNo(), 0, 0, 'C');
    }

    function SectionTitle($title)
    {
        $this->SetTextColor(10, 31, 68);
        $this->SetFont('Arial', 'B', 13);
        $this->Cell(0, 8, $title, 0, 1);
        $this->SetDrawColor(59, 130, 246);
        $this->Line($this->GetX(), $this->GetY(), 200, $this->GetY());
        $this->Ln(4);
    }

    function CleanText($text)
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        return trim($text);
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(16, 16, 16);
$pdf->SetAutoPageBreak(true, 22);

$pdf->SetTextColor(31, 41, 55);

$pdf->SetFont('Arial', 'B', 18);
$pdf->MultiCell(0, 9, $row['nama_resep'], 0, 'C');
$pdf->Ln(5);

$gambar_path = __DIR__ . '/../assets/images/' . $row['gambar'];

if (!empty($row['gambar']) && file_exists($gambar_path)) {
    $img_width = 120;
    $img_height = 80;
    $x = (210 - $img_width) / 2;

    $pdf->Image($gambar_path, $x, $pdf->GetY(), $img_width, $img_height);
    $pdf->Ln($img_height + 10);
}

$pdf->SectionTitle('Bahan');

$pdf->SetTextColor(31, 41, 55);
$pdf->SetFont('Arial', '', 11);

$bahan = explode("\n", $pdf->CleanText($row['bahan']));
$no = 1;

foreach ($bahan as $item) {
    $item = trim($item);

    if ($item != '') {
        $pdf->MultiCell(0, 7, $no . '. ' . $item);
        $no++;
    }
}

$pdf->Ln(4);

$pdf->SectionTitle('Langkah Memasak');

$pdf->SetTextColor(31, 41, 55);
$pdf->SetFont('Arial', '', 11);

$langkah = explode("\n", $pdf->CleanText($row['langkah']));
$no = 1;

foreach ($langkah as $item) {
    $item = trim($item);

    if ($item != '') {
        $pdf->SetFont('Arial', 'B', 11);
        $pdf->Cell(10, 7, $no . '.', 0, 0);

        $pdf->SetFont('Arial', '', 11);
        $pdf->MultiCell(0, 7, $item);
        $pdf->Ln(2);

        $no++;
    }
}

$pdf->Output('I', 'resep_' . $row['id_resep'] . '.pdf');
?>
