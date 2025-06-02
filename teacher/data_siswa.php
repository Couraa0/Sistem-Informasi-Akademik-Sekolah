<?php
$page = 'data_siswa';
$page_title = 'Data Siswa';

require_once '../config.php';

function requireTeacherLogin() {
    if (!isLoggedIn() || $_SESSION['role'] !== 'teacher') {
        header("Location: ../allert.php");
        exit;
    }
}

requireTeacherLogin();

$class_filter = isset($_GET['class']) ? $_GET['class'] : '';
$search_query = isset($_GET['search']) ? $_GET['search'] : '';

$students_sql = "SELECT s.*, c.name as class_name, c.level, c.specialization 
                FROM students s 
                LEFT JOIN classes c ON s.class_id = c.id 
                WHERE 1=1";

if (!empty($class_filter)) {
    $students_sql .= " AND s.class_id = " . intval($class_filter);
}

if (!empty($search_query)) {
    $search_query = $conn->real_escape_string($search_query);
    $students_sql .= " AND (s.name LIKE '%$search_query%' OR s.nis LIKE '%$search_query%')";
}

$students_sql .= " ORDER BY c.level ASC, c.name ASC, s.name ASC";
$students_result = $conn->query($students_sql);

$classes_sql = "SELECT * FROM classes ORDER BY level ASC, name ASC";
$classes_result = $conn->query($classes_sql);

include 'includes/header.php';
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12">
                <h1 class="page-title"><?php echo $page_title; ?></h1>
            </div>
        </div>
        
        <!-- Search and filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="get" action="" class="row g-3">
                            <div class="col-md-6">
                                <label for="search" class="form-label">Cari Siswa</label>
                                <input type="text" class="form-control" id="search" name="search" placeholder="Nama atau NIS" value="<?php echo htmlspecialchars($search_query); ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="class" class="form-label">Filter Kelas</label>
                                <select class="form-select" id="class" name="class">
                                    <option value="">Semua Kelas</option>
                                    <?php 
                                    if ($classes_result && $classes_result->num_rows > 0) {
                                        while ($class = $classes_result->fetch_assoc()) {
                                            $selected = ($class_filter == $class['id']) ? 'selected' : '';
                                            echo '<option value="' . $class['id'] . '" ' . $selected . '>' . $class['level'] . ' ' . $class['name'] . ' ' . $class['specialization'] . '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-2"></i>Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Students Table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Data Siswa</h5>
                        <?php
                        // Count displayed students
                        $displayed_students = $students_result ? $students_result->num_rows : 0;
                        ?>
                        <span class="badge bg-primary"><?php echo $displayed_students; ?> Siswa</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>NIS</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th>Email</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if ($students_result && $students_result->num_rows > 0) {
                                        $counter = 1;
                                        while ($student = $students_result->fetch_assoc()) {
                                    ?>
                                    <tr>
                                        <td><?php echo $counter++; ?></td>
                                        <td><?php echo htmlspecialchars($student['nis']); ?></td>
                                        <td><?php echo htmlspecialchars($student['name']); ?></td>
                                        <td>
                                            <?php echo htmlspecialchars($student['level'] . ' ' . $student['class_name'] . ' ' . $student['specialization']); ?>
                                        </td>
                                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                                        <td>
                                            <a href="detail_siswa.php?id=<?php echo $student['id']; ?>" class="btn btn-sm btn-info me-1">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="rekap_nilai.php?student_id=<?php echo $student['id']; ?>" class="btn btn-sm btn-success">
                                                <i class="fas fa-chart-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-4">Tidak ada data siswa yang ditemukan.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
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