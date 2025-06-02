<?php
require_once 'config.php';
require_once 'auth.php';

if (!isLoggedIn() && !isset($is_login_page)) {
    header("Location: index.php");
    exit;
}

$current_date = date('d M Y');

$current_user = $_SESSION['name'] ?? 'Guest User';

$class_info = 'MIPA'; 
if (isLoggedIn() && isset($_SESSION['class_id'])) {
    $class_id = $_SESSION['class_id'];
    $class_sql = "SELECT c.specialization FROM classes c WHERE c.id = $class_id LIMIT 1";
    $class_result = $conn->query($class_sql);
    if ($class_result && $class_result->num_rows > 0) {
        $class_info = $class_result->fetch_assoc()['specialization'];
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - ' . $site_name : $site_name; ?></title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <style>
        .header-bar {
            background-color: white;
            padding: 15px 20px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            height: 80px;
            position: fixed; 
            top: 0;
            right: 0;
            left: 0;
            z-index: 1030; 
            margin: 10px 20px; 
            width: calc(100% - 40px); 
        }
        .breadcrumb {
            margin-bottom: 0;
            font-size: 14px; 
        }
        .header-bar .dropdown {
            margin-left: auto;
        }
        .main-content {
            padding-top: 100px; 
        }
        @media (min-width: 768px) {
            .header-bar {
                left: 250px; 
                width: calc(100% - 250px - 40px); 
            }
            .main-content {
                margin-left: 250px;
            }
        }
        @media (max-width: 767.98px) {  
            .header-bar {
                left: 0;
                width: 100%;
                margin: 0;
                border-radius: 0;
            }
            .main-content {
                margin-left: 0;
                padding-top: 90px;
            }
            #sidebarOpen {
                top: 12px !important;
                left: 12px !important;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php if (isLoggedIn() && !isset($is_login_page)): ?>
    <div class="d-flex">
        <?php include 'includes/sidebar.php'; ?>
        <div class="main-content">
            <div class="header-bar">
                <div class="d-none d-md-block">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb mb-0" style="display:flex;gap:8px;align-items:center;">
                            <li class="breadcrumb-item" style="display:inline-flex;align-items:center;">
                                <a href="dashboard.php">Dashboard</a>
                            </li>
                            <?php if (isset($page_title)): ?>
                                <li class="breadcrumb-item active" aria-current="page" style="display:inline-flex;align-items:center;">
                                    <?php echo $page_title; ?>
                                </li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex align-items-center w-100 justify-content-end">
                    <div class="me-3 d-none d-md-block">
                        <span class="me-2"><?php echo $current_date; ?> &nbsp; | &nbsp; <?php echo htmlspecialchars($class_info); ?></span>
                    </div>
                    <!-- Hanya tampilkan nama user di mobile -->
                    <div class="d-block d-md-none fw-semibold">
                        <i class="fas fa-user-circle me-2"></i><?php echo htmlspecialchars($current_user); ?>
                    </div>
                    <div class="dropdown d-none d-md-block">
                        <button class="btn dropdown-toggle d-flex align-items-center" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2"><?php echo htmlspecialchars($current_user); ?></span>
                            <i class="fas fa-user-circle fa-lg"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li><a class="dropdown-item" href="biodata.php">Profil</a></li>
                            <li><a class="dropdown-item" href="index.php?logout=1">Logout</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="content-wrapper pt-3">
<?php endif; ?>