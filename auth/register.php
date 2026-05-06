<?php
/** @var mysqli $conn */
include '../config/koneksi.php';

if (isset($_POST['register'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Cek password sama atau tidak
    if ($password !== $confirm) {
        $error_msg = "Konfirmasi password tidak sesuai!";
    } else {
        // Enkripsi password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        // Simpan ke database
        $q = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
        if (mysqli_query($conn, $q)) {
            echo "<script>alert('Registrasi Berhasil! Silakan Login'); window.location='login.php';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Register WahanaTirta</title>
</head>
<body class="bg-[#0f172a] flex items-center justify-center h-screen font-sans">
    <div class="bg-white/5 backdrop-blur-lg p-10 rounded-[40px] border border-white/10 shadow-2xl w-96">
        <h1 class="text-3xl font-black text-white text-center mb-2 tracking-tighter">WAHANA <span class="text-cyan-400">TIRTA</span></h1>
        <p class="text-slate-500 text-center mb-8 text-xs uppercase tracking-widest font-bold">Buat Akun Baru</p>
        
        <?php if(isset($error_msg)) : ?>
            <p class="text-red-400 text-center mb-4 text-xs italic"><?= $error_msg ?></p>
        <?php endif; ?>

        <form action="" method="post" class="space-y-4">
            <input type="text" name="username" placeholder="Username" class="w-full bg-slate-900 border border-slate-700 p-4 rounded-2xl text-white outline-none focus:ring-2 focus:ring-cyan-500 text-sm" required>
            <input type="password" name="password" placeholder="Password" class="w-full bg-slate-900 border border-slate-700 p-4 rounded-2xl text-white outline-none focus:ring-2 focus:ring-cyan-500 text-sm" required>
            <input type="password" name="confirm_password" placeholder="Confirm Password" class="w-full bg-slate-900 border border-slate-700 p-4 rounded-2xl text-white outline-none focus:ring-2 focus:ring-cyan-500 text-sm" required>
            
            <button type="submit" name="register" class="w-full bg-white text-black font-black py-4 rounded-2xl transition-all hover:bg-cyan-400 shadow-xl shadow-black/20 text-xs tracking-widest">BUAT AKUN</button>
        </form>

        <div class="mt-8 text-center border-t border-white/5 pt-6">
            <a href="login.php" class="text-slate-500 text-[10px] uppercase font-bold hover:text-white transition-colors">Sudah punya akun? Log In</a>
        </div>
    </div>
</body>
</html>