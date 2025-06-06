<?php
$page = 'akademik';
$page_title = 'Akademik';

require_once 'config.php';
requireLogin();

$student_id = $_SESSION['user_id'];

// Total Nilai dan Peringkat
$total_sql = "SELECT SUM(total_score) as total_score FROM grades WHERE student_id = $student_id";
$total_result = $conn->query($total_sql);
$total_nilai = $total_result->fetch_assoc()['total_score'] ?? 0;
$total_nilai = number_format($total_nilai);

// Ranking berdasarkan total nilai terbanyak
$rank_sql = "SELECT s.id, s.name, SUM(g.total_score) as total_score 
             FROM students s 
             JOIN grades g ON s.id = g.student_id 
             GROUP BY s.id 
             ORDER BY total_score DESC";
$rank_result = $conn->query($rank_sql);

$rank = 0;
$found = false;
if ($rank_result->num_rows > 0) {
    $counter = 1;
    while ($row = $rank_result->fetch_assoc()) {
        if ($row['id'] == $student_id) {
            $rank = $counter;
            $found = true;
            break;
        }
        $counter++;
    }
}

if (!$found) {
    $rank = "N/A";
}

$semester_sql = "SELECT DISTINCT semester FROM grades WHERE student_id = $student_id ORDER BY semester";
$semester_result = $conn->query($semester_sql);
$semesters = [];

if ($semester_result->num_rows > 0) {
    while ($row = $semester_result->fetch_assoc()) {
        $semesters[] = $row['semester'];
    }
}

$selected_semester = isset($_GET['semester']) ? (int)$_GET['semester'] : ($semesters[0] ?? 1);

$grades_sql = "SELECT g.*, s.code as subject_code, s.name as subject_name 
               FROM grades g 
               JOIN subjects s ON g.subject_id = s.id 
               WHERE g.student_id = $student_id AND g.semester = $selected_semester";
$grades_result = $conn->query($grades_sql);

// Export nilai siswa per semester ke Excel
if (isset($_GET['export']) && $_GET['export'] === 'excel_semester' && isset($_GET['semester_export'])) {
    $semester_export = intval($_GET['semester_export']);
    $semester_label = "Semester_" . $semester_export;
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=nilai_saya_{$semester_label}.xls");
    echo "<table border='1'>";
    echo "<tr>
            <th>No</th>
            <th>Kode Mapel</th>
            <th>Nama Mapel</th>
            <th>Nilai Angka</th>
            <th>Nilai Huruf</th>
          </tr>";
    $sql = "SELECT s.code as subject_code, s.name as subject_name, g.total_score, g.letter_grade
            FROM grades g
            JOIN subjects s ON g.subject_id = s.id
            WHERE g.student_id = $student_id AND g.semester = $semester_export";
    $result = $conn->query($sql);
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$no}</td>
                <td>".htmlspecialchars($row['subject_code'])."</td>
                <td>".htmlspecialchars($row['subject_name'])."</td>
                <td>".number_format($row['total_score'], 2)."</td>
                <td>".htmlspecialchars($row['letter_grade'])."</td>
              </tr>";
        $no++;
    }
    echo "</table>";
    exit;
}

include 'includes/header.php';
?>

<style>
    @media (max-width: 991.98px) {
    .akademik-content .content-card {
        padding: 0 !important;
    }
    .akademik-content .table-responsive {
        margin: 0 -12px;
    }
}
.akademik-content .table-responsive {
    overflow-x: auto;
}
.akademik-content table {
    min-width: 1100px;
    width: 100%;
    background: #fff;
}
.akademik-content th, .akademik-content td {
    vertical-align: middle !important;
    white-space: nowrap;
}
@media (max-width: 767.98px) {
    .akademik-content .content-card {
        padding: 0 !important;
    }
    .akademik-content table {
        font-size: 14px;
        min-width: 600px;
    }
    .akademik-content .btn,
    .akademik-content .btn-outline-primary {
        font-size: 13px;
        padding: 6px 10px;
    }
}
</style>

<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <div class="content-card">
                <div class="akademik-content">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="content-card bg-primary text-white text-center">
                                <h2 class="display-4 mb-0"><?php echo $total_nilai; ?></h2>
                                <p class="mb-0">TOTAL NILAI</p>
                            </div>
                        </div>
                        <div class="col-md-6 mb-4">
                            <div class="content-card bg-success text-white text-center">
                                <h2 class="display-4 mb-0"><?php echo $rank; ?></h2>
                                <p class="mb-0">PERINGKAT SEKOLAH</p>
                            </div>
                        </div>
                    </div>
                    <?php if (!empty($semesters)): ?>
                        <div class="mt-4">
                            <div class="d-flex mb-3 flex-wrap align-items-center">
                                <?php foreach ($semesters as $semester): ?>
                                    <a href="?semester=<?php echo $semester; ?>" class="btn <?php echo $semester == $selected_semester ? 'btn-primary' : 'btn-outline-primary'; ?> me-2 mb-2">
                                        Semester <?php echo $semester; ?>
                                    </a>
                                <?php endforeach; ?>
                                <!-- Download Excel per semester -->
                                <div class="btn-group ms-auto mb-2">
                                    <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-file-excel me-1"></i>Download Nilai Excel
                                    </button>
                                    <ul class="dropdown-menu">
                                        <?php foreach ($semesters as $sem): ?>
                                            <li>
                                                <a class="dropdown-item" href="?export=excel_semester&semester_export=<?php echo $sem; ?>">
                                                    Semester <?php echo $sem; ?>
                                                </a>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <div class="content-card">
                                <div class="card-header bg-light">
                                    <h5 class="mb-0">Semester <?php echo $selected_semester; ?></h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>No</th>
                                                    <th>Kode Mapel</th>
                                                    <th>Nama Mapel</th>
                                                    <th>Nilai Angka</th>
                                                    <th>Nilai Huruf</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                if ($grades_result->num_rows > 0): 
                                                    $counter = 1;
                                                    while ($grade = $grades_result->fetch_assoc()):
                                                ?>
                                                <tr>
                                                    <td><?php echo $counter++; ?></td>
                                                    <td><?php echo htmlspecialchars($grade['subject_code']); ?></td>
                                                    <td><?php echo htmlspecialchars($grade['subject_name']); ?></td>
                                                    <td><?php echo number_format($grade['total_score'], 2); ?></td>
                                                    <td><?php echo htmlspecialchars($grade['letter_grade']); ?></td>
                                                </tr>
                                                <?php 
                                                    endwhile; 
                                                else:
                                                ?>
                                                <tr>
                                                    <td colspan="5" class="text-center">Tidak ada data nilai untuk semester ini.</td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mt-4">
                            Belum ada data nilai tersedia.
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