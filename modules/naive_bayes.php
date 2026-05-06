<?php
function hitungNB($id_produk, $hari_target, $cuaca_target) {
    global $conn;

    // Ambil Threshold dari Database
    // Ambil Threshold dari Database (Sesuaikan dengan struktur tabel baru)
$resThreshold = mysqli_query($conn, "SELECT batas_tinggi FROM settings WHERE id = 1");
$threshold = mysqli_fetch_assoc($resThreshold)['batas_tinggi'];

    // Total Data Produk Ini
    $resTotal = mysqli_query($conn, "SELECT COUNT(*) as total FROM penjualan WHERE id_produk = '$id_produk'");
    $total = mysqli_fetch_assoc($resTotal)['total'];

    if($total < 3) return "DATA KURANG";

    // P(Tinggi) & P(Rendah)
    $resTinggi = mysqli_query($conn, "SELECT COUNT(*) as jml FROM penjualan WHERE id_produk = '$id_produk' AND jumlah >= $threshold");
    $jmlTinggi = mysqli_fetch_assoc($resTinggi)['jml'];
    $jmlRendah = $total - $jmlTinggi;

    $pTinggi = ($jmlTinggi + 1) / ($total + 2);
    $pRendah = ($jmlRendah + 1) / ($total + 2);

    // Likelihood Hari & Cuaca (Laplace Smoothing)
    $qHT = mysqli_query($conn, "SELECT COUNT(*) as jml FROM penjualan WHERE id_produk = '$id_produk' AND hari = '$hari_target' AND jumlah >= $threshold");
    $pHariTinggi = (mysqli_fetch_assoc($qHT)['jml'] + 1) / ($jmlTinggi + 7);

    $qCT = mysqli_query($conn, "SELECT COUNT(*) as jml FROM penjualan WHERE id_produk = '$id_produk' AND cuaca = '$cuaca_target' AND jumlah >= $threshold");
    $pCuacaTinggi = (mysqli_fetch_assoc($qCT)['jml'] + 1) / ($jmlTinggi + 3);

    $qHR = mysqli_query($conn, "SELECT COUNT(*) as jml FROM penjualan WHERE id_produk = '$id_produk' AND hari = '$hari_target' AND jumlah < $threshold");
    $pHariRendah = (mysqli_fetch_assoc($qHR)['jml'] + 1) / ($jmlRendah + 7);

    $qCR = mysqli_query($conn, "SELECT COUNT(*) as jml FROM penjualan WHERE id_produk = '$id_produk' AND cuaca = '$cuaca_target' AND jumlah < $threshold");
    $pCuacaRendah = (mysqli_fetch_assoc($qCR)['jml'] + 1) / ($jmlRendah + 3);

    // Final Score
    $scoreTinggi = $pTinggi * $pHariTinggi * $pCuacaTinggi;
    $scoreRendah = $pRendah * $pHariRendah * $pCuacaRendah;

    return ($scoreTinggi > $scoreRendah) ? "TINGGI" : "RENDAH";
}
?>