
<?php
session_start();

require_once 'data/menu.php';
require_once 'includes/fungsi.php';

if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = array();
}

$page_title = 'Keranjang Belanja';
require 'includes/header.php';

$flash = ambilFlash();

if ($flash !== '') {
    echo '<p class="flash">' . e($flash) . '</p>';
}

$subtotal = 0;
?>

<h2>Keranjang Belanja</h2>

<?php if (count($_SESSION['keranjang']) === 0) { ?>

    <p>Keranjang kamu masih kosong.</p>
    <a href="index.php">Kembali ke Daftar Menu</a>

<?php } else { ?>

    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Nama Menu</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Total</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($_SESSION['keranjang'] as $kode => $jumlah) { ?>

                    <?php
                    if (!isset($menu[$kode])) {
                        continue;
                    }

                    $item = $menu[$kode];
                    $totalItem = $item['harga'] * $jumlah;
                    $subtotal = $subtotal + $totalItem;
                    ?>

                    <tr>
                        <td><?php echo e($item['nama']); ?></td>
                        <td><?php echo rupiah($item['harga']); ?></td>
                        <td><?php echo (int) $jumlah; ?></td>
                        <td><?php echo rupiah($totalItem); ?></td>
                        <td>
                            <form method="post" action="proses.php">
                                <input type="hidden" name="action" value="hapus">
                                <input type="hidden" name="kode"
                                    value="<?php echo e($kode); ?>">
                                <button type="submit">Hapus</button>
                            </form>
                        </td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php
    // Menghitung diskon 10% jika subtotal minimal Rp50.000
    $diskon = 0;

    if ($subtotal >= 50000) {
        $diskon = $subtotal * 10 / 100;
    }

    // Menghitung total setelah diskon
    $totalBayar = $subtotal - $diskon;
    ?>

    <div class="ringkasan">
        <h3>Ringkasan Pembayaran</h3>

        <p>
            Subtotal:
            <strong><?php echo rupiah($subtotal); ?></strong>
        </p>

        <p>
            Diskon 10%:
            <strong><?php echo rupiah($diskon); ?></strong>
        </p>

        <p>
            Total Bayar:
            <strong><?php echo rupiah($totalBayar); ?></strong>
        </p>

        <?php if ($subtotal >= 50000) { ?>

            <p class="pesan-diskon">
                Selamat! Kamu mendapatkan diskon 10%.
            </p>

        <?php } else { ?>

            <p class="pesan-diskon">
                Belanja minimal Rp50.000 untuk mendapatkan diskon 10%.
            </p>

            <p>
                Kurang belanja
                <strong><?php echo rupiah(50000 - $subtotal); ?></strong>
                lagi untuk mendapatkan diskon.
            </p>

        <?php } ?>

        <form method="post" action="proses.php">
            <input type="hidden" name="action" value="kosongkan">
            <button type="submit">Kosongkan Keranjang</button>
        </form>
    </div>

<?php } ?>

<?php require 'includes/footer.php'; ?>
```