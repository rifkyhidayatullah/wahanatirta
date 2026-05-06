<?php
/** @var mysqli $conn */
$id = $_GET['id'];
$row = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM penjualan WHERE id = '$id'"));

if (isset($_POST['update'])) {
    $id_p   = $_POST['id_produk'];
    $tgl    = $_POST['tanggal'];
    $hari   = $_POST['hari'];
    $cuaca  = $_POST['cuaca'];
    $jml    = $_POST['jumlah'];

    $q = "UPDATE penjualan SET id_produk='$id_p', tanggal='$tgl', hari='$hari', cuaca='$cuaca', jumlah='$jml' WHERE id='$id'";
    if (mysqli_query($conn, $q)) {
        echo "<script>alert('Data Berhasil Diupdate!'); window.location='?page=penjualan';</script>";
    }
}
?>

<div class="mb-10">
    <h2 class="text-3xl font-bold text-white">Edit Data Penjualan</h2>
</div>

<div class="glass p-8 rounded-3xl border border-white/5 max-w-2xl">
    <form action="" method="post" class="space-y-4 text-sm">
        <div>
            <label class="block mb-2 text-slate-400">Produk</label>
            <select name="id_produk" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white">
                <?php 
                $prod = mysqli_query($conn, "SELECT * FROM produk");
                while($p = mysqli_fetch_assoc($prod)) {
                    $sel = ($p['id'] == $row['id_produk']) ? 'selected' : '';
                    echo "<option value='{$p['id']}' $sel>{$p['nama_produk']}</option>";
                }
                ?>
            </select>
        </div>
        <input type="date" name="tanggal" value="<?= $row['tanggal'] ?>" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white mb-4">
        <input type="number" name="jumlah" value="<?= $row['jumlah'] ?>" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white mb-4">
        
        <div class="flex gap-4">
            <button type="submit" name="update" class="flex-1 bg-cyan-600 text-white font-bold py-3 rounded-xl">SIMPAN PERUBAHAN</button>
            <a href="?page=penjualan" class="flex-1 bg-white/5 text-center py-3 rounded-xl text-white">BATAL</a>
        </div>
    </form>
</div>