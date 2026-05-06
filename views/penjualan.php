<?php
/** @var mysqli $conn */
if (isset($_POST['update'])) {
    $id_edit = $_POST['id_penjualan'];
    $id_p    = $_POST['id_produk'];
    $tgl     = $_POST['tanggal'];
    $hari    = $_POST['hari'];
    $cuaca   = $_POST['cuaca'];
    $jml     = $_POST['jumlah'];

    $q = "UPDATE penjualan SET id_produk='$id_p', tanggal='$tgl', hari='$hari', cuaca='$cuaca', jumlah='$jml' WHERE id='$id_edit'";
    if (mysqli_query($conn, $q)) {
        echo "<script>window.location='?page=penjualan';</script>";
    }
}


if (isset($_POST['tambah'])) {
    $id_p = $_POST['id_produk']; 
    $tgl  = $_POST['tanggal']; 
    $hari = $_POST['hari']; 
    $cuaca = $_POST['cuaca']; 
    $jml  = $_POST['jumlah'];

    $q = "INSERT INTO penjualan (id_produk, tanggal, hari, cuaca, jumlah) VALUES ('$id_p', '$tgl', '$hari', '$cuaca', '$jml')";
    
    if (mysqli_query($conn, $q)) {
        echo "<script>window.location='?page=penjualan';</script>";
    }
}
?>

<div class="mb-10">
    <h2 class="text-3xl font-bold text-white uppercase tracking-tighter">Data Penjualan</h2>
    <p class="text-slate-500 text-sm italic">Input Data Penjualan Galon Depot Wahanatirta</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <div class="glass p-8 rounded-3xl border border-white/5 h-fit">
        <form action="" method="post" class="space-y-4 text-sm text-slate-300">
            <input type="hidden" name="tambah" value="1">
            <div>
                <label class="block mb-2 text-xs uppercase font-bold text-cyan-500">Pilih Produk</label>
                <select name="id_produk" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white outline-none focus:ring-2 focus:ring-cyan-500">
                    <?php 
                    $prod = mysqli_query($conn, "SELECT * FROM produk");
                    while($p = mysqli_fetch_assoc($prod)) echo "<option value='{$p['id']}'>{$p['nama_produk']}</option>";
                    ?>
                </select>
            </div>
            <div>
                <label class="block mb-2 text-xs uppercase font-bold text-cyan-500">Tanggal & Kondisi</label>
                <input type="date" name="tanggal" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white mb-3" required>
                <div class="grid grid-cols-2 gap-3">
                    <select name="hari" class="bg-slate-900 border border-slate-700 p-3 rounded-xl text-white"><option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option><option>Minggu</option></select>
                    <select name="cuaca" class="bg-slate-900 border border-slate-700 p-3 rounded-xl text-white"><option>Cerah</option><option>Mendung</option><option>Hujan</option></select>
                </div>
            </div>
            <div>
                <label class="block mb-2 text-xs uppercase font-bold text-cyan-500">Volume (Galon)</label>
                <input type="number" name="jumlah" placeholder="0" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white outline-none focus:ring-2 focus:ring-cyan-500" required>
            </div>
            <button type="submit" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-cyan-900/20">SAVE DATA</button>
        </form>
    </div>

    <div class="lg:col-span-2 glass p-8 rounded-3xl border border-white/5">
        <table class="w-full text-left text-sm">
            <thead class="text-slate-500 uppercase text-[10px] tracking-widest border-b border-white/10">
                <tr>
                    <th class="pb-4">Details</th>
                    <th class="pb-4">Qty</th>
                    <th class="pb-4 text-center">Action</th>
                </tr>
            </thead>
            <tbody class="text-slate-300">
                <?php 
                $data = mysqli_query($conn, "SELECT p.*, pr.nama_produk FROM penjualan p JOIN produk pr ON p.id_produk = pr.id ORDER BY p.tanggal DESC LIMIT 10");
                while($d = mysqli_fetch_assoc($data)) : ?>
                <tr class="border-b border-white/5 hover:bg-white/5 transition-colors group">
                    <td class="py-4">
                        <p class="font-bold text-white"><?= $d['nama_produk'] ?></p>
                        <p class="text-[10px] text-slate-500 italic"><?= $d['tanggal'] ?> | <?= $d['hari'] ?> - <?= $d['cuaca'] ?></p>
                    </td>
                    <td class="py-4 font-mono text-cyan-400 font-bold"><?= $d['jumlah'] ?></td>
                    <td class="py-4 text-center">
                        <button onclick='openEditModal(<?= json_encode($d) ?>)' class="bg-yellow-500/10 text-yellow-500 p-2 rounded-lg hover:bg-yellow-500 hover:text-white transition-all mr-2">Edit</button>
                        <a href="?page=hapus_penjualan&id=<?= $d['id'] ?>" class="bg-red-500/10 text-red-500 p-2 rounded-lg hover:bg-red-500 hover:text-white transition-all" onclick="return confirm('Hapus data?')">Del</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div id="editModal" class="fixed inset-0 bg-[#0b0f1a]/80 backdrop-blur-sm z-[99] flex items-center justify-center opacity-0 pointer-events-none transition-all duration-300">
    <div class="bg-[#1e293b] w-full max-w-md p-8 rounded-[40px] border border-white/10 shadow-2xl transform scale-90 transition-all duration-300" id="modalCard">
        <div class="flex justify-between items-center mb-8">
            <h3 class="text-xl font-bold text-white">Edit Record</h3>
            <button onclick="closeEditModal()" class="text-slate-400 hover:text-white text-2xl font-bold">&times;</button>
        </div>

        <form action="" method="post" class="space-y-5 text-sm text-slate-300">
            <input type="hidden" name="id_penjualan" id="edit_id">
            <div>
                <label class="block mb-2 text-xs text-slate-500 uppercase">Produk</label>
                <select name="id_produk" id="edit_produk" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white outline-none focus:ring-2 focus:ring-cyan-500">
                    <?php 
                    $prod = mysqli_query($conn, "SELECT * FROM produk");
                    while($p = mysqli_fetch_assoc($prod)) echo "<option value='{$p['id']}'>{$p['nama_produk']}</option>";
                    ?>
                </select>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <input type="date" name="tanggal" id="edit_tgl" class="bg-slate-900 border border-slate-700 p-3 rounded-xl text-white">
                <input type="number" name="jumlah" id="edit_jml" class="bg-slate-900 border border-slate-700 p-3 rounded-xl text-white" placeholder="Qty">
            </div>
            <div class="grid grid-cols-2 gap-4">
                <select name="hari" id="edit_hari" class="bg-slate-900 border border-slate-700 p-3 rounded-xl text-white"><option>Senin</option><option>Selasa</option><option>Rabu</option><option>Kamis</option><option>Jumat</option><option>Sabtu</option><option>Minggu</option></select>
                <select name="cuaca" id="edit_cuaca" class="bg-slate-900 border border-slate-700 p-3 rounded-xl text-white"><option>Cerah</option><option>Mendung</option><option>Hujan</option></select>
            </div>
            <button type="submit" name="update" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-4 rounded-2xl transition-all mt-4">UPDATE CHANGES</button>
        </form>
    </div>
</div>

<script>
function openEditModal(data) {
    const modal = document.getElementById('editModal');
    const card = document.getElementById('modalCard');
    
    document.getElementById('edit_id').value = data.id;
    document.getElementById('edit_produk').value = data.id_produk;
    document.getElementById('edit_tgl').value = data.tanggal;
    document.getElementById('edit_hari').value = data.hari;
    document.getElementById('edit_cuaca').value = data.cuaca;
    document.getElementById('edit_jml').value = data.jumlah;

    modal.classList.remove('opacity-0', 'pointer-events-none');
    card.classList.remove('scale-90');
    card.classList.add('scale-100');
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    const card = document.getElementById('modalCard');
    
    modal.classList.add('opacity-0', 'pointer-events-none');
    card.classList.remove('scale-100');
    card.classList.add('scale-90');
}
</script>