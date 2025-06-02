<?php
$page = 'dashboard';
$page_title = 'Dashboard Guru';

require_once '../config.php';

function requireTeacherLogin() {
    if (!isLoggedIn() || $_SESSION['role'] !== 'teacher') {
        header("Location: ../allert.php");
        exit;
    }
}

requireTeacherLogin();
include 'includes/header.php';
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title"><?php echo $page_title; ?></h1>
            </div>
        </div>
        
        <!-- Stats cards -->
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body d-flex">
                        <div class="stat-icon">
                            <i class="fas fa-user-graduate fa-3x"></i>
                        </div>
                        <div class="stat-details ms-3">
                            <?php
                            // Count all students
                            $count_students = $conn->query("SELECT COUNT(*) as total FROM students")->fetch_assoc()['total'] ?? 0;
                            ?>
                            <h2 class="stat-number"><?php echo $count_students; ?></h2>
                            <p class="stat-label mb-0">Total Siswa</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body d-flex">
                        <div class="stat-icon">
                            <i class="fas fa-book fa-3x"></i>
                        </div>
                        <div class="stat-details ms-3">
                            <?php
                            // Count all subjects
                            $count_subjects = $conn->query("SELECT COUNT(*) as total FROM subjects")->fetch_assoc()['total'] ?? 0;
                            ?>
                            <h2 class="stat-number"><?php echo $count_subjects; ?></h2>
                            <p class="stat-label mb-0">Total Mata Pelajaran</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body d-flex">
                        <div class="stat-icon">
                            <i class="fas fa-school fa-3x"></i>
                        </div>
                        <div class="stat-details ms-3">
                            <?php
                            // Count all classes
                            $count_classes = $conn->query("SELECT COUNT(*) as total FROM classes")->fetch_assoc()['total'] ?? 0;
                            ?>
                            <h2 class="stat-number"><?php echo $count_classes; ?></h2>
                            <p class="stat-label mb-0">Total Kelas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent activity -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-info rounded-pill me-2">Nilai</span>
                                    Update nilai ujian tengah semester kelas XI IPS 1
                                </div>
                                <span class="text-muted small">Hari ini</span>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="badge bg-info rounded-pill me-2">Nilai</span>
                                    Update nilai ujian tengah semester kelas XI MIPA 1
                                </div>
                                <span class="text-muted small">Hari ini</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Button Shortcut -->
        <div class="row">
            <div class="col-md-3 mb-4">
                <a href="data_siswa.php" class="text-decoration-none">
                    <div class="card shortcut-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-3x mb-3 text-primary"></i>
                            <h5 class="card-title">Data Siswa</h5>
                            <p class="card-text text-muted">Kelola dan lihat data siswa</p>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-3 mb-4">
                <a href="rekap_nilai.php" class="text-decoration-none">
                    <div class="card shortcut-card h-100">
                        <div class="card-body text-center">
                            <i class="fas fa-chart-bar fa-3x mb-3 text-success"></i>
                            <h5 class="card-title">Rekap Nilai</h5>
                            <p class="card-text text-muted">Kelola nilai siswa</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<?php
include 'includes/footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>