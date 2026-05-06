<?php
/** @var mysqli $conn */
include '../config/koneksi.php';

if (isset($_POST['login'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $result = mysqli_query($conn, "SELECT * FROM users WHERE username = '$username'");
    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['password'])) {
            $_SESSION['login'] = true;
            $_SESSION['username'] = $username;
            header("Location: ../index.php");
            exit;
        }
    }
    $error = true;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Login WahanaTirta</title>
</head>
<body class="bg-[#0f172a] flex items-center justify-center h-screen">
    <div class="bg-white/5 backdrop-blur-lg p-10 rounded-3xl border border-white/10 shadow-2xl w-96">
        <h1 class="text-3xl font-bold text-white text-center mb-2">WAHANA<span class="text-cyan-400">TIRTA</span></h1>
        <p class="text-slate-400 text-center mb-8 text-sm">Login Wir</p>
        
        <?php if(isset($error)) : ?>
            <p class="text-red-400 text-center mb-4 text-xs italic">Username atau Password salah!</p>
        <?php endif; ?>

        <form action="" method="post" class="space-y-6">
            <input type="text" name="username" placeholder="Username" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white outline-none focus:ring-2 focus:ring-cyan-500">
            <input type="password" name="password" placeholder="Password" class="w-full bg-slate-900 border border-slate-700 p-3 rounded-xl text-white outline-none focus:ring-2 focus:ring-cyan-500">
            <button type="submit" name="login" class="w-full bg-cyan-600 hover:bg-cyan-500 text-white font-bold py-3 rounded-xl transition-all shadow-lg shadow-cyan-900/20">LOG IN</button>
        </form>
        <div class="mt-8 text-center">
    <p class="text-slate-500 text-xs">Belum punya akun?</p>
    <a href="register.php" class="text-cyan-400 text-xs font-bold hover:underline">Buat Akun Baru Disini</a>
</div>
    </div>
</body>
</html>