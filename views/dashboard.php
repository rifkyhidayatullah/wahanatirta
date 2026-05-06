<?php
/** @var mysqli $conn */
$labels = []; $values = [];
$resGraph = mysqli_query($conn, "SELECT tanggal, SUM(jumlah) as total FROM penjualan GROUP BY tanggal ORDER BY tanggal DESC LIMIT 7");
while($row = mysqli_fetch_assoc($resGraph)) {
    $labels[] = $row['tanggal'];
    $values[] = $row['total'];
}

// Logic simpan batas baru
if (isset($_POST['update_batas'])) {
    $new_batas = $_POST['batas_tinggi'];
    mysqli_query($conn, "UPDATE settings SET batas_tinggi = '$new_batas' WHERE id = 1");
    echo "<script>window.location='?page=dashboard';</script>";
}
$set = mysqli_fetch_assoc(mysqli_query($conn, "SELECT batas_tinggi FROM settings WHERE id = 1"));
$batas = $set['batas_tinggi'] ?? 20;
?>

<!-- Header Section -->
<div class="flex justify-between items-center mb-10">
    <div>
        <h2 class="text-4xl font-black text-white tracking-tighter uppercase">Dashboard</h2>
        <p class="text-slate-500 text-sm italic">Analisis Naive Bayes Depot Wahanatirta</p>
    </div>
    <div class="hidden md:block text-right">
        <div class="inline-flex items-center bg-cyan-500/10 border border-cyan-500/20 px-4 py-2 rounded-2xl">
            <span class="w-2 h-2 bg-cyan-500 rounded-full animate-pulse mr-2"></span>
            <p class="text-cyan-400 font-mono text-[10px] uppercase tracking-widest font-bold">Sistem Aktif</p>
        </div>
    </div>
</div>

<!-- TOP GRID: Skala Depot, Total Sales, & Prediksi (Satu Baris Sejajar) -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    
    <!-- 1. Skala Depot -->
    <div class="glass p-6 rounded-[32px] border border-white/5 flex flex-col justify-between">
        <h4 class="text-cyan-400 text-[10px] font-bold uppercase tracking-widest mb-4">Skala Depot (Batas)</h4>
        <div class="flex items-center justify-between">
            <form action="" method="post" class="flex items-center gap-2">
                <input type="number" name="batas_tinggi" value="<?= $batas ?>" class="w-16 bg-slate-900 border border-slate-700 p-2 rounded-xl text-white text-center font-bold text-sm outline-none focus:ring-1 focus:ring-cyan-500">
                <button type="submit" name="update_batas" class="bg-cyan-600 hover:bg-cyan-500 text-white p-2 rounded-xl transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
                </button>
            </form>
            <div class="text-right">
                <p class="text-slate-500 text-[10px] italic leading-none">Jualan ≥ <?= $batas ?></p>
                <p class="text-green-400 font-black text-xl uppercase italic">Tinggi</p>
            </div>
        </div>
    </div>

    <!-- 2. Total Penjualan -->
    <div class="glass p-6 rounded-[32px] border border-white/5 flex flex-col justify-center">
        <p class="text-slate-500 text-[10px] uppercase tracking-widest mb-1">Akumulasi Penjualan</p>
        <div class="flex items-baseline gap-2">
            <h3 class="text-4xl font-black text-white"><?= mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah) as total FROM penjualan"))['total'] ?? 0 ?></h3>
            <span class="text-slate-500 font-medium text-sm">Galon</span>
        </div>
    </div>

    <div class="glass p-6 rounded-[32px] border border-white/5">
        <p class="text-slate-500 text-[10px] uppercase tracking-widest mb-4">Prediksi Naive Bayes</p>
        <form action="" method="post" class="flex gap-2">
            <select name="p_produk" class="flex-1 bg-slate-900 border border-slate-700 px-3 py-2 rounded-xl text-[11px] text-white outline-none">
                <?php 
                $p = mysqli_query($conn, "SELECT * FROM produk");
                while($dp = mysqli_fetch_assoc($p)) echo "<option value='{$dp['id']}'>{$dp['nama_produk']}</option>";
                ?>
            </select>
            <button name="cek_nb" class="bg-white text-black px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-tighter hover:bg-cyan-400 transition-all">Analisis</button>
        </form>
        <?php if(isset($_POST['cek_nb'])): $hasil = hitungNB($_POST['p_produk'], date('l'), "Cerah"); ?>
            <div class="mt-3 text-[11px] font-bold text-cyan-400 animate-pulse">RESULT: <?= $hasil ?></div>
        <?php endif; ?>
    </div>
</div>


<!-- BOTTOM: Chart Section -->
<div class="glass p-8 rounded-[40px] border border-white/5 overflow-hidden">
    <div class="flex justify-between items-center mb-6">
        <p class="text-slate-500 text-[10px] uppercase tracking-widest font-bold font-mono">Sales Statistics (7 Days)</p>
    </div>
    <div class="relative h-[250px]">
        <canvas id="mainChart"></canvas>
    </div>
</div>

<script>
    const ctx = document.getElementById('mainChart').getContext('2d');
    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(34, 211, 238, 0.2)');
    gradient.addColorStop(1, 'rgba(34, 211, 238, 0)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(array_reverse($labels)) ?>,
            datasets: [{
                data: <?= json_encode(array_reverse($values)) ?>,
                borderColor: '#22d3ee',
                borderWidth: 4,
                pointBackgroundColor: '#22d3ee',
                pointBorderColor: 'rgba(255,255,255,0.1)',
                pointRadius: 5,
                fill: true,
                backgroundColor: gradient,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { 
                y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.03)' }, ticks: { color: '#64748b', font: { size: 10 } } },
                x: { grid: { display: false }, ticks: { color: '#64748b', font: { size: 10 } } }
            },
            plugins: { legend: { display: false } }
        }
    });
</script>