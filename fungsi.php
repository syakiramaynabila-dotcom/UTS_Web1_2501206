
<?php
function e($teks)
{
    return htmlspecialchars($teks, ENT_QUOTES, 'UTF-8');
}

function rupiah($angka)
{
    return 'Rp' . number_format($angka, 0, ',', '.');
}

function ambilFlash()
{
    if (isset($_SESSION['flash'])) {
        $pesan = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $pesan;
    }

    return '';
}
