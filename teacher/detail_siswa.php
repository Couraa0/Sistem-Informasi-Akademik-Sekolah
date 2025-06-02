<?php
$page = 'data_siswa';
$page_title = 'Detail Siswa';

require_once '../config.php';

function requireTeacherLogin() {
    if (!isLoggedIn() || $_SESSION['role'] !== 'teacher') {
        header("Location: ../allert.php");
        exit;
    }
}

requireTeacherLogin();

$student_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($student_id <= 0) {
    header("Location: data_siswa.php");
    exit;
}

// Student Details
$student_sql = "SELECT s.*, c.name as class_name, c.level, c.specialization 
               FROM students s 
               LEFT JOIN classes c ON s.class_id = c.id 
               WHERE s.id = $student_id";
$student_result = $conn->query($student_sql);

if (!$student_result || $student_result->num_rows === 0) {
    header("Location: data_siswa.php");
    exit;
}

$student = $student_result->fetch_assoc();

// Wali Kelas
$teacher_sql = "SELECT t.name FROM teachers t 
                JOIN classes c ON c.id = {$student['class_id']} 
                LIMIT 1";
$teacher_result = $conn->query($teacher_sql);
$wali_kelas = ($teacher_result && $teacher_result->num_rows > 0) ? $teacher_result->fetch_assoc()['name'] : 'Tidak ada data';

include 'includes/header.php';
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <!-- Page title and back button -->
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <h1 class="page-title">Detail Siswa</h1>
                <a href="data_siswa.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
        
        <!-- Student information section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <img src="../img/rakha.jpg" class="rounded-circle mb-3" width="150" alt="Student Photo">
                        <h3 class="mb-0"><?php echo htmlspecialchars($student['name']); ?></h3>
                        <p class="text-muted"><?php echo htmlspecialchars($student['nis']); ?></p>
                        
                        <div class="d-flex justify-content-center mt-3">
                            <span class="badge bg-primary px-3 py-2 me-2"><?php echo htmlspecialchars($student['specialization'] ?? 'MIPA'); ?></span>
                            <span class="badge bg-info px-3 py-2"><?php echo htmlspecialchars($student['year_enrolled']); ?></span>
                        </div>
                        
                        <div class="mt-4">
                            <div class="d-grid gap-2">
                                <a href="rekap_nilai.php?student_id=<?php echo $student['id']; ?>" class="btn btn-success">
                                    <i class="fas fa-chart-line me-2"></i>Lihat Nilai
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Informasi Siswa</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">NIS</p>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['nis']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">Kelas</p>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['level'] . ' ' . $student['class_name'] . ' ' . $student['specialization']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">Email</p>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['email']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">Tahun Masuk</p>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['year_enrolled']); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">Agama</p>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['religion'] ?? 'Tidak ada data'); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">Jenis Kelamin</p>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['gender'] ?? 'Tidak ada data'); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">Tempat Tanggal Lahir</p>
                                    <p class="mb-0 fw-bold">
                                        <?php 
                                        $birth_info = 'Tidak ada data';
                                        if (!empty($student['birth_place']) && !empty($student['birth_date'])) {
                                            $birth_info = htmlspecialchars($student['birth_place'] . ', ' . date('d F Y', strtotime($student['birth_date'])));
                                        }
                                        echo $birth_info;
                                        ?>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">Wali Kelas</p>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($wali_kelas); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">Alamat</p>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['address'] ?? 'Tidak ada data'); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">No Hp</p>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['phone_number'] ?? 'Tidak ada data'); ?></p>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="profile-info">
                                    <p class="mb-1 small text-muted">Nama Orang Tua / Wali</p>
                                    <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['parent_name'] ?? 'Tidak ada data'); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
include 'includes/footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>