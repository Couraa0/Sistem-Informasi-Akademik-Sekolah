<?php
$is_login_page = true;

require_once 'config.php';
require_once 'auth.php';

if (isLoggedIn()) {
    if ($_SESSION['role'] === 'teacher') {
        header("Location: teacher/dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - <?php echo $site_name; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-page">
        <div class="container">
            <div class="text-center mb-4">
                <h1 class="login-title">Selamat Datang Siswa</h1>
                <p class="text-white">Silahkan Login Untuk Mengakses Sistem Informasi Akademik</p>
            </div>
            <div class="login-box mx-auto">
                <form method="post" action="">
                    <div class="mb-3">
                        <label for="email" class="form-label small text-muted">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Masukkan email anda" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label small text-muted">Password</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Masukkan password anda" required>
                    </div>
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <div class="mb-3">
                        <button type="submit" name="login" class="login-button">Masuk</button>
                    </div>
                    <div class="text-center">
                        <a href="teacher_login.php" class="small">Login sebagai Guru</a> | 
                        <a href="#" class="small">Lupa Password?</a>
                    </div>
                </form>
            </div>
            <div class="text-center mt-5 text-white small">
                <?php echo $site_year; ?> - Sistem Akademik by Kelompok 6
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>