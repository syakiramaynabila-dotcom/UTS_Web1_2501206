
<?php
session_start();

require_once 'data/menu.php';
require_once 'includes/fungsi.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = array();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : '';

if ($action === 'tambah') {
    $kode = isset($_POST['kode']) ? $_POST['kode'] : '';
    $jumlahInput = isset($_POST['jumlah']) ? $_POST['jumlah'] : '';

    // Validasi kode menu
    if (!is_string($kode) || !isset($menu[$kode])) {
        $_SESSION['flash'] = 'Kode menu tidak valid.';
        header('Location: index.php');
        exit;
    }

    // Validasi jumlah harus berupa bilangan bulat
    if (
        !is_string($jumlahInput) ||
        !preg_match('/^[0-9]+$/', $jumlahInput)
    ) {
        $_SESSION['flash'] = 'Jumlah harus berupa bilangan bulat.';
        header('Location: index.php');
        exit;
    }

    $jumlah = (int) $jumlahInput;

    // Jumlah harus antara 1 sampai 10
    if ($jumlah < 1 || $jumlah > 10) {
        $_SESSION['flash'] = 'Jumlah harus antara 1 sampai 10.';
        header('Location: index.php');
        exit;
    }

    // Cek jumlah menu yang sudah ada di keranjang
    $jumlahLama = isset($_SESSION['keranjang'][$kode])
        ? $_SESSION['keranjang'][$kode]
        : 0;

    // Maksimal 10 untuk setiap jenis menu
    if ($jumlahLama + $jumlah > 10) {
        $_SESSION['flash'] = 'Maksimal 10 untuk setiap menu.';
        header('Location: index.php');
        exit;
    }

    // Masukkan menu ke keranjang
    $_SESSION['keranjang'][$kode] = $jumlahLama + $jumlah;
    $_SESSION['flash'] = 'Menu berhasil ditambahkan ke keranjang.';

    header('Location: index.php');
    exit;
}

// Menyimpan kategori favorit selama 7 hari
if ($action === 'simpan_kategori') {
    $kategori = isset($_POST['kategori'])
        ? $_POST['kategori']
        : '';

    $kategoriValid = array('Semua', 'Makanan', 'Minuman');

    if (!in_array($kategori, $kategoriValid, true)) {
        $_SESSION['flash'] = 'Kategori tidak valid.';
        header('Location: index.php');
        exit;
    }

    setcookie(
        'kategori_favorit',
        $kategori,
        time() + (7 * 24 * 60 * 60),
        '/'
    );

    $_SESSION['flash'] = 'Kategori favorit berhasil disimpan.';

    header('Location: index.php?kategori=' . urlencode($kategori));
    exit;
}

// Menghapus satu menu dari keranjang
if ($action === 'hapus') {
    $kode = isset($_POST['kode']) ? $_POST['kode'] : '';

    if (isset($_SESSION['keranjang'][$kode])) {
        unset($_SESSION['keranjang'][$kode]);
        $_SESSION['flash'] = 'Menu berhasil dihapus.';
    } else {
        $_SESSION['flash'] = 'Menu tidak ditemukan di keranjang.';
    }

    header('Location: keranjang.php');
    exit;
}

// Mengosongkan seluruh keranjang
if ($action === 'kosongkan') {
    $_SESSION['keranjang'] = array();
    $_SESSION['flash'] = 'Keranjang berhasil dikosongkan.';

    header('Location: keranjang.php');
    exit;
}

// Jika action tidak dikenal
$_SESSION['flash'] = 'Aksi tidak dikenali.';
header('Location: index.php');
exit;

