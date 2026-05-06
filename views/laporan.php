<?php /** @var mysqli $conn */ ?>
<div class="flex justify-between items-center mb-10 no-print">
    <div>
        <h2 class="text-3xl font-bold text-white">Laporan Penjualan</h2>
        <p class="text-slate-500">Rekapitulasi data WahanaTirta</p>
    </div>
    <button onclick="window.print()" class="bg-white/10 hover:bg-white/20 text-white px-6 py-3 rounded-xl border border-white/10 transition-all font-bold">
        PRINT PDF
    </button>
</div>

<div class="glass p-10 rounded-3xl border border-white/5">
    <div class="text-center mb-8">
        <h1 class="text-2xl font-bold text-white">REKAPITULASI PENJUALAN WAHANATIRTA</h1>
        <p class="text-slate-400">Periode: Semua Data</p>
    </div>
    
    <table class="w-full text-left">
        <thead class="border-b-2 border-cyan-500 text-cyan-400 text-sm uppercase">
            <tr>
                <th class="py-4">No</th>
                <th class="py-4">Produk</th>
                <th class="py-4">Tanggal</th>
                <th class="py-4">Kondisi</th>
                <th class="py-4 text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody class="text-slate-300">
            <?php 
            $no = 1;
            $total_all = 0;
            $res = mysqli_query($conn, "SELECT p.*, pr.nama_produk FROM penjualan p JOIN produk pr ON p.id_produk = pr.id ORDER BY p.tanggal DESC");
            while($row = mysqli_fetch_assoc($res)) : 
                $total_all += $row['jumlah'];
            ?>
            <tr class="border-b border-white/5">
                <td class="py-3"><?= $no++ ?></td>
                <td class="py-3 font-semibold"><?= $row['nama_produk'] ?></td>
                <td class="py-3"><?= $row['tanggal'] ?></td>
                <td class="py-3 text-xs uppercase text-slate-500"><?= $row['hari'] ?> | <?= $row['cuaca'] ?></td>
                <td class="py-3 text-right font-bold"><?= number_format($row['jumlah']) ?> Galon</td>
            </tr>
            <?php endwhile; ?>
        </tbody>
        <tfoot>
            <tr class="text-white font-bold bg-white/5 text-lg">
                <td colspan="4" class="py-6 px-4">TOTAL KESELURUHAN</td>
                <td class="py-6 px-4 text-right text-cyan-400"><?= number_format($total_all) ?> Galon</td>
            </tr>
        </tfoot>
    </table>
</div>

<style>
@media print {
    body { background: white !important; color: black !important; }
    .no-print, aside { display: none !important; }
    main { margin-left: 0 !important; width: 100% !important; padding: 0 !important; }
    .glass { border: none !important; background: none !important; color: black !important; }
    .text-white, .text-slate-300 { color: black !important; }
}
</style>