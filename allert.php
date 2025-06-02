<?php
$page_title = "Allert";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?php echo $page_title; ?> - Sistem Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #ff5858 0%, #f09819 100%);
        }
        .forbidden-card {
            border: none;
            border-radius: 18px;
            background: #fff;
            box-shadow: 0 8px 32px rgba(0,0,0,0.15);
            max-width: 400px;
        }
        .forbidden-icon {
            font-size: 4rem;
            color: #ff5858;
            margin-bottom: 10px;
        }
        .forbidden-title {
            font-weight: bold;
            color: #ff5858;
            letter-spacing: 1px;
        }
        .forbidden-desc {
            color: #333;
        }
        .btn-login {
            background: #ff5858;
            border: none;
        }
        .btn-login:hover {
            background: #f09819;
        }
    </style>
</head>
<body>
    <div class="container vh-100 d-flex justify-content-center align-items-center">
        <div class="card forbidden-card p-4 text-center">
            <div class="forbidden-icon mb-2">
                <i class="fas fa-ban"></i>
            </div>
            <h3 class="forbidden-title mb-2">Dilarang Masuk!</h3>
            <p class="forbidden-desc mb-3">
                Anda <b>tidak diizinkan</b> mengakses halaman ini tanpa login.<br>
                Silakan login terlebih dahulu untuk melanjutkan.
            </p>
            <a href="index.php" class="btn btn-login text-white px-4 py-2 rounded-pill">
                <i class="fas fa-sign-in-alt me-2"></i>Login Sekarang
            </a>
        </div>
    </div>
</body>
</html>
