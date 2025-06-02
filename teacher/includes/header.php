<?php
// Check if the user is logged in and has teacher role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'teacher') {
    header("Location: /UAS-PBW/teacher_login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - <?php echo $site_name; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <!-- <link rel="stylesheet" href="/UAS-PBW/css/style.css"> -->
    
    <style>
    .body {
        font-family: 'Poppins', sans-serif;
        background-color: #ffffff;
        color: #333;
        margin: 0;
    }

    .sidebar {
        background-color: #ffffff;
        width: 250px;
        min-height: 100vh;
        position: fixed;
        z-index: 100;
        border-right: 1px solid #eaeaea;
        transition: all 0.3s ease;
    }

    .sidebar-header {
        padding: 20px;
        text-align: center;
        background-color: #4285F4;
        border-bottom: 1px solid #eaeaea;
        color: #4285F4;
        font-weight: 600;
        font-size: 18px;
        letter-spacing: 1px;
    }

    .sidebar-user {
        padding: 20px;
        text-align: center;
        background-color: #f8f9fb;
        border-bottom: 1px solid #eaeaea;
    }

    .sidebar-user img {
        border-radius: 50%;
        margin-bottom: 10px;
        width: 70px;
        height: 70px;
        object-fit: cover;
        border: 2px solid #4285F4;
    }

    .sidebar-user h5,
    .sidebar-user p {
        margin: 0;
        color: #333;
    }

    .sidebar-user p.text-muted {
        font-size: 13px;
    }

    .nav {
        list-style: none;
        padding-left: 0;
    }

    .nav-item {
        border-bottom: 1px solid #f1f1f1;
    }

    .nav-link {
        display: block;
        color: #555;
        padding: 12px 20px;
        text-decoration: none;
        transition: all 0.3s;
    }

    .nav-link i {
        margin-right: 10px;
        width: 20px;
        text-align: center;
        color: #4285F4;
    }

    .nav-link:hover,
    .nav-link.active {
        background-color: #e8f0fe;
        color: #4285F4;
        font-weight: 500;
    }

    .content-wrapper {
        margin-left: 250px;
        padding: 30px;
        transition: margin-left 0.3s;
        background-color: #ffffff;
    }

    .page-title {
        font-size: 26px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 30px;
    }

    /* Card */
    .card {
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        background-color: #ffffff;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-4px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.06);
    }

    .card-title {
        font-weight: 500;
        color: #4285F4;
    }

    .card-text {
        font-size: 20px;
        font-weight: 600;
        color: #333;
    }

    #sidebarToggle {
        display: none;
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 999;
        background-color: #4285F4;
        color: #fff;
        border: none;
        padding: 10px 12px;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }

    #sidebarToggle:hover {
        background-color: #3367d6;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .sidebar {
            margin-left: -250px;
        }

        .sidebar.active {
            margin-left: 0;
        }

        .content-wrapper {
            margin-left: 0;
        }

        .content-wrapper.active {
            margin-left: 250px;
        }

        #sidebarToggle {
            display: block;
        }
    }
</style>

</head>
<body>
    <!-- Sidebar toggle button for mobile -->
    <button class="btn btn-dark d-md-none" id="sidebarToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h3 class="text-white mb-0">Guru</h3>
        </div>
        
        <div class="sidebar-user p-3 text-center text-white mb-3">
            <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQLIXzJxeiDISiLFZr4mwqnTnTrO_Ozr5a3SQ&s" alt="Profile Picture" class="rounded-circle mb-3" width="80">
            <h5 class="mb-1"><?php echo $_SESSION['name']; ?></h5>
            <p class="mb-0 small"><?php echo $_SESSION['nip']; ?></p>
            <p class="mb-0 small text-muted"><?php echo $_SESSION['specialization']; ?></p>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="/UAS-PBW/teacher/dashboard.php" class="nav-link <?php echo $page == 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a href="/UAS-PBW/teacher/data_siswa.php" class="nav-link <?php echo $page == 'data_siswa' ? 'active' : ''; ?>">
                    <i class="fas fa-users"></i> Data Siswa
                </a>
            </li>
            <li class="nav-item">
                <a href="/UAS-PBW/teacher/rekap_nilai.php" class="nav-link <?php echo $page == 'rekap_nilai' ? 'active' : ''; ?>">
                    <i class="fas fa-chart-bar"></i> Rekap Nilai
                </a>
            </li>
            <li class="nav-item mt-3">
                <a href="/UAS-PBW/auth.php?logout=1" class="nav-link text-danger">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>