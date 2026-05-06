<?php
include 'config/koneksi.php';
include 'modules/naive_bayes.php';

if (!isset($_SESSION['login'])) {
    header("Location: auth/login.php");
    exit;
}

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WahanaTirta</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800&display=swap');
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(12px); }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
    </style>
</head>
<body class="bg-[#0b0f1a] text-slate-200 no-scrollbar">
 
    <div class="flex flex-col md:flex-row min-h-screen">
        
        <aside class="w-full md:w-72 md:h-screen glass border-b md:border-r border-white/5 p-6 md:p-8 md:sticky md:top-0 z-50">
            <div class="flex items-center justify-between md:block">
                <h1 class="text-xl md:text-2xl font-black tracking-tighter text-white md:mb-12">
                    WAHANA<span class="text-cyan-400">TIRTA.</span>
                </h1>
                
                <div class="md:hidden">
                    <button id="menuBtn" class="text-white p-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path></svg>
                    </button>
                </div>
            </div>

  
            <nav id="navMenu" class="hidden md:block mt-8 md:mt-0 space-y-2 md:space-y-6">
                <a href="?page=dashboard" class="flex items-center gap-4 p-3 rounded-2xl transition-all <?= $page == 'dashboard' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-900/40' : 'text-slate-400 hover:bg-white/5' ?>">
                    <span class="text-sm font-bold uppercase tracking-wider">Dashboard</span>
                </a>
                <a href="?page=penjualan" class="flex items-center gap-4 p-3 rounded-2xl transition-all <?= $page == 'penjualan' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-900/40' : 'text-slate-400 hover:bg-white/5' ?>">
                    <span class="text-sm font-bold uppercase tracking-wider">Data Penjualan</span>
                </a>
                <a href="?page=laporan" class="flex items-center gap-4 p-3 rounded-2xl transition-all <?= $page == 'laporan' ? 'bg-cyan-600 text-white shadow-lg shadow-cyan-900/40' : 'text-slate-400 hover:bg-white/5' ?>">
                    <span class="text-sm font-bold uppercase tracking-wider">Laporan</span>
                </a>
                <a href="auth/logout.php" class="flex items-center gap-4 p-3 rounded-2xl text-red-400 hover:bg-red-500/10 md:mt-20">
                    <span class="text-sm font-bold uppercase tracking-wider">Log Out</span>
                </a>
            </nav>
        </aside>

       
        <main class="flex-1 p-6 md:p-12 w-full overflow-x-hidden">
            <div class="max-w-7xl mx-auto">
                <?php 
                    if ($page == 'dashboard') include 'views/dashboard.php';
                    elseif ($page == 'penjualan') include 'views/penjualan.php';
                    elseif ($page == 'edit_penjualan') include 'views/edit_penjualan.php';
                    elseif ($page == 'hapus_penjualan') include 'views/hapus_penjualan.php';
                    elseif ($page == 'laporan') include 'views/laporan.php';
                ?>
            </div>
        </main>
    </div>

    <script>
        const btn = document.getElementById('menuBtn');
        const menu = document.getElementById('navMenu');
        
        btn.addEventListener('click', () => {
            menu.classList.toggle('hidden');
        });
    </script>

</body>
</html>