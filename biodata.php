<?php
$page = 'biodata';
$page_title = 'Biodata';

require_once 'config.php';
requireLogin();

$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'biodata';

$student_email = $_SESSION['email']; // pastikan session email di-set saat login
$student_sql = "SELECT s.*, c.name as class_name, c.level, c.specialization 
                FROM students s 
                LEFT JOIN classes c ON s.class_id = c.id 
                WHERE s.email = '$student_email'";
$student_result = $conn->query($student_sql);

$student = [];

if ($student_result->num_rows > 0) {
    $student = $student_result->fetch_assoc();
} else {
    header("Location: index.php");
    exit;
}

$teacher_sql = "SELECT t.name FROM teachers t 
                JOIN classes c ON c.id = {$student['class_id']} 
                LIMIT 1";
$teacher_result = $conn->query($teacher_sql);
$wali_kelas = ($teacher_result->num_rows > 0) ? $teacher_result->fetch_assoc()['name'] : 'Tidak ada data';

include 'includes/header.php';
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="content-card">
                    <div class="d-flex">
                        <a href="?tab=biodata" class="biodata-tab <?php echo $active_tab == 'biodata' ? 'active' : ''; ?>">Biodata</a>
                        <a href="akademik.php" class="biodata-tab <?php echo $active_tab == 'akademik' ? 'active' : ''; ?>">Akademik</a>
                    </div>

                    <?php if ($active_tab == 'biodata'): ?>
                    <div class="biodata-content mt-4">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <?php
                                    $profile_image = !empty($student['image']) ? htmlspecialchars($student['image']) : 'img/Profil.jpg';
                                ?>
                                <img src="<?php echo $profile_image; ?>" class="profile-avatar" alt="Profile Photo">
                                <h5 class="mt-2"><?php echo htmlspecialchars($student['name']); ?></h5>
                                <p class="mb-1"><?php echo htmlspecialchars($student['nis']); ?></p>
                                <div class="mt-3">
                                    <span class="badge bg-primary px-3 py-2 me-2"><?php echo htmlspecialchars($student['specialization'] ?? 'MIPA'); ?></span>
                                    <span class="badge bg-info px-3 py-2"><?php echo htmlspecialchars($student['year_enrolled']); ?></span>
                                </div>
                                <div class="mt-3">
                                    <p class="mb-1">Wali Kelas</p>
                                    <h6><?php echo htmlspecialchars($wali_kelas); ?></h6>
                                </div>
                                <div class="mt-3 d-flex justify-content-center">
                                    <a href="https://www.facebook.com/profile.php?id=61555856880836" class="btn btn-sm btn-outline-primary rounded-circle me-2"><i class="fab fa-facebook-f"></i></a>
                                    <a href="https://x.com/couraa0" class="btn btn-sm btn-outline-info rounded-circle me-2"><i class="fab fa-twitter"></i></a>
                                    <a href="https://www.instagram.com/couraa0" class="btn btn-sm btn-outline-danger rounded-circle"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                            <div class="col-md-9">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <div class="profile-info">
                                            <p class="mb-1 small text-muted">NIS</p>
                                            <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['nis']); ?></p>
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
                                            <p class="mb-1 small text-muted">Agama</p>
                                            <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['religion']); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="profile-info">
                                            <p class="mb-1 small text-muted">Jenis Kelamin</p>
                                            <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['gender']); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="profile-info">
                                            <p class="mb-1 small text-muted">Tempat Tanggal Lahir</p>
                                            <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['birth_place'] . ', ' . date('d F Y', strtotime($student['birth_date']))); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="profile-info">
                                            <p class="mb-1 small text-muted">Alamat</p>
                                            <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['address']); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="profile-info">
                                            <p class="mb-1 small text-muted">No Hp</p>
                                            <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['phone_number']); ?></p>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <div class="profile-info">
                                            <p class="mb-1 small text-muted">Nama Orang Tua / Wali</p>
                                            <p class="mb-0 fw-bold"><?php echo htmlspecialchars($student['parent_name']); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
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