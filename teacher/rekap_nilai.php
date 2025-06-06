<?php
$page = 'rekap_nilai';
$page_title = 'Rekap Nilai Siswa';

require_once '../config.php';

function requireTeacherLogin() {
    if (!isLoggedIn() || $_SESSION['role'] !== 'teacher') {
        header("Location: ../allert.php");
        exit;
    }
}

requireTeacherLogin();

$student_id = isset($_GET['student_id']) ? intval($_GET['student_id']) : 0;
$class_id = isset($_GET['class_id']) ? intval($_GET['class_id']) : 0;
$semester = isset($_GET['semester']) ? intval($_GET['semester']) : 1;
$academic_year = isset($_GET['academic_year']) ? $_GET['academic_year'] : '';

if (empty($academic_year)) {
    $current_year = date('Y');
    $academic_year = $current_year . '/' . ($current_year + 1);
}

// Student details
$student_info = null;
if ($student_id > 0) {
    $student_sql = "SELECT s.*, c.name as class_name, c.level, c.specialization 
                   FROM students s 
                   LEFT JOIN classes c ON s.class_id = c.id 
                   WHERE s.id = $student_id";
    $student_result = $conn->query($student_sql);
    
    if ($student_result && $student_result->num_rows > 0) {
        $student_info = $student_result->fetch_assoc();
    }
}

$classes_sql = "SELECT * FROM classes ORDER BY level ASC, name ASC";
$classes_result = $conn->query($classes_sql);

$students_sql = "SELECT s.id, s.name, s.nis, c.name as class_name, c.level, c.specialization 
                FROM students s 
                LEFT JOIN classes c ON s.class_id = c.id";

if ($class_id > 0) {
    $students_sql .= " WHERE s.class_id = $class_id";
}

$students_sql .= " ORDER BY s.name ASC";
$students_result = $conn->query($students_sql);

$grades = [];

if ($student_id > 0) {
    $grades_sql = "SELECT g.*, s.code as subject_code, s.name as subject_name, t.name as teacher_name 
                  FROM grades g 
                  JOIN subjects s ON g.subject_id = s.id 
                  LEFT JOIN teachers t ON g.teacher_id = t.id
                  WHERE g.student_id = $student_id AND g.semester = $semester";
                  
    if (!empty($academic_year)) {
        $academic_year = $conn->real_escape_string($academic_year);
        $grades_sql .= " AND g.academic_year = '$academic_year'";
    }
    
    $grades_sql .= " ORDER BY s.name ASC";
    $grades_result = $conn->query($grades_sql);
    
    if ($grades_result && $grades_result->num_rows > 0) {
        while ($grade = $grades_result->fetch_assoc()) {
            $grades[] = $grade;
        }
    }
}

function calculateLetterGrade($total_score) {
    if ($total_score >= 90) return 'A';
    if ($total_score >= 85) return 'A-';
    if ($total_score >= 80) return 'B+';
    if ($total_score >= 75) return 'B';
    if ($total_score >= 70) return 'B-';
    if ($total_score >= 65) return 'C+';
    if ($total_score >= 60) return 'C';
    if ($total_score >= 55) return 'D+';
    if ($total_score >= 45) return 'D';
    return 'E';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_grade' || $_POST['action'] === 'edit_grade') {
        $is_edit = ($_POST['action'] === 'edit_grade');
        $grade_id = isset($_POST['grade_id']) ? intval($_POST['grade_id']) : 0;
        
        $subject_id = intval($_POST['subject_id']);
        $assignment_score = floatval($_POST['assignment_score']);
        $midterm_score = floatval($_POST['midterm_score']);
        $final_score = floatval($_POST['final_score']);
        
        // Total score 
        $total_score = ($assignment_score * 0.3) + ($midterm_score * 0.3) + ($final_score * 0.4);
        
        // Nilai huruf
        $letter_grade = calculateLetterGrade($total_score);
        
        $teacher_id = $_SESSION['user_id'];
        
        if ($is_edit && $grade_id > 0) {
            $update_sql = "UPDATE grades SET 
                          assignment_score = $assignment_score,
                          midterm_score = $midterm_score,
                          final_score = $final_score,
                          total_score = $total_score,
                          letter_grade = '$letter_grade',
                          teacher_id = $teacher_id
                          WHERE id = $grade_id";
            
            if ($conn->query($update_sql)) {
                $success_message = "Nilai berhasil diperbarui.";
            } else {
                $error_message = "Gagal memperbarui nilai: " . $conn->error;
            }
        } else {
            $insert_sql = "INSERT INTO grades 
                          (student_id, subject_id, teacher_id, academic_year, semester, 
                           assignment_score, midterm_score, final_score, total_score, letter_grade) 
                          VALUES 
                          ($student_id, $subject_id, $teacher_id, '$academic_year', $semester, 
                           $assignment_score, $midterm_score, $final_score, $total_score, '$letter_grade')";
            
            if ($conn->query($insert_sql)) {
                $success_message = "Nilai berhasil ditambahkan.";
            } else {
                $error_message = "Gagal menambahkan nilai: " . $conn->error;
            }
        }
        
        header("Location: rekap_nilai.php?student_id=$student_id&class_id=$class_id&semester=$semester&academic_year=$academic_year");
        exit;
    } elseif ($_POST['action'] === 'delete_grade' && isset($_POST['grade_id'])) {
        $grade_id = intval($_POST['grade_id']);
        
        $delete_sql = "DELETE FROM grades WHERE id = $grade_id";
        
        if ($conn->query($delete_sql)) {
            $success_message = "Nilai berhasil dihapus.";
        } else {
            $error_message = "Gagal menghapus nilai: " . $conn->error;
        }
        
        header("Location: rekap_nilai.php?student_id=$student_id&class_id=$class_id&semester=$semester&academic_year=$academic_year");
        exit;
    } elseif ($_POST['action'] === 'edit_grade') {
        $grade_id = intval($_POST['grade_id']);
        $subject_id = intval($_POST['subject_id']); // This comes from hidden field
        $assignment_score = floatval($_POST['assignment_score']);
        $midterm_score = floatval($_POST['midterm_score']);
        $final_score = floatval($_POST['final_score']);
        
        // Calculate total score
        $total_score = ($assignment_score * 0.3) + ($midterm_score * 0.3) + ($final_score * 0.4);
        
        // Calculate letter grade
        $letter_grade = calculateLetterGrade($total_score);
        
        // Update existing grade
        $update_sql = "UPDATE grades 
                       SET assignment_score = ?, 
                           midterm_score = ?, 
                           final_score = ?, 
                           total_score = ?, 
                           letter_grade = ?
                       WHERE id = ? AND subject_id = ?";
                       
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ddddsii", 
            $assignment_score, 
            $midterm_score, 
            $final_score, 
            $total_score, 
            $letter_grade,
            $grade_id,
            $subject_id
        );
        
        if ($stmt->execute()) {
            $_SESSION['success_message'] = "Nilai berhasil diperbarui.";
        } else {
            $_SESSION['error_message'] = "Gagal memperbarui nilai: " . $conn->error;
        }
        $stmt->close();
        
        header("Location: rekap_nilai.php?student_id=$student_id&semester=$semester&academic_year=$academic_year");
        exit;
    }
}

// Export all students' grades to Excel
if (isset($_GET['export']) && $_GET['export'] === 'excel_all') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=rekap_nilai_semua_siswa.xls");
    echo "<table border='1'>";
    echo "<tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>NIS</th>
            <th>Kelas</th>
            <th>Mata Pelajaran</th>
            <th>Nilai Tugas</th>
            <th>Nilai UTS</th>
            <th>Nilai UAS</th>
            <th>Nilai Akhir</th>
            <th>Grade</th>
            <th>Semester</th>
            <th>Tahun Akademik</th>
          </tr>";
    $all_sql = "SELECT s.name as student_name, s.nis, c.level, c.name as class_name, c.specialization, 
                       sub.name as subject_name, g.assignment_score, g.midterm_score, g.final_score, 
                       g.total_score, g.letter_grade, g.semester, g.academic_year
                FROM grades g
                JOIN students s ON g.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN subjects sub ON g.subject_id = sub.id
                ORDER BY s.name, g.semester, sub.name";
    $all_result = $conn->query($all_sql);
    $no = 1;
    while ($row = $all_result->fetch_assoc()) {
        $kelas = htmlspecialchars($row['level'] . ' ' . $row['class_name'] . ' ' . $row['specialization']);
        echo "<tr>
                <td>{$no}</td>
                <td>".htmlspecialchars($row['student_name'])."</td>
                <td>".htmlspecialchars($row['nis'])."</td>
                <td>{$kelas}</td>
                <td>".htmlspecialchars($row['subject_name'])."</td>
                <td>".number_format($row['assignment_score'], 1)."</td>
                <td>".number_format($row['midterm_score'], 1)."</td>
                <td>".number_format($row['final_score'], 1)."</td>
                <td>".number_format($row['total_score'], 1)."</td>
                <td>".htmlspecialchars($row['letter_grade'])."</td>
                <td>".htmlspecialchars($row['semester'])."</td>
                <td>".htmlspecialchars($row['academic_year'])."</td>
              </tr>";
        $no++;
    }
    echo "</table>";
    exit;
}

// Export nilai per semester ke Excel
if (isset($_GET['export']) && $_GET['export'] === 'excel_semester' && isset($_GET['semester_export'])) {
    $semester_export = intval($_GET['semester_export']);
    $semester_label = "Semester_" . $semester_export;
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=rekap_nilai_{$semester_label}.xls");
    echo "<table border='1'>";
    echo "<tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>NIS</th>
            <th>Kelas</th>
            <th>Mata Pelajaran</th>
            <th>Nilai Tugas</th>
            <th>Nilai UTS</th>
            <th>Nilai UAS</th>
            <th>Nilai Akhir</th>
            <th>Grade</th>
            <th>Semester</th>
            <th>Tahun Akademik</th>
          </tr>";
    $sql = "SELECT s.name as student_name, s.nis, c.level, c.name as class_name, c.specialization, 
                   sub.name as subject_name, g.assignment_score, g.midterm_score, g.final_score, 
                   g.total_score, g.letter_grade, g.semester, g.academic_year
            FROM grades g
            JOIN students s ON g.student_id = s.id
            LEFT JOIN classes c ON s.class_id = c.id
            LEFT JOIN subjects sub ON g.subject_id = sub.id
            WHERE g.semester = $semester_export
            ORDER BY s.name, sub.name";
    $result = $conn->query($sql);
    $no = 1;
    while ($row = $result->fetch_assoc()) {
        $kelas = htmlspecialchars($row['level'] . ' ' . $row['class_name'] . ' ' . $row['specialization']);
        echo "<tr>
                <td>{$no}</td>
                <td>".htmlspecialchars($row['student_name'])."</td>
                <td>".htmlspecialchars($row['nis'])."</td>
                <td>{$kelas}</td>
                <td>".htmlspecialchars($row['subject_name'])."</td>
                <td>".number_format($row['assignment_score'], 1)."</td>
                <td>".number_format($row['midterm_score'], 1)."</td>
                <td>".number_format($row['final_score'], 1)."</td>
                <td>".number_format($row['total_score'], 1)."</td>
                <td>".htmlspecialchars($row['letter_grade'])."</td>
                <td>".htmlspecialchars($row['semester'])."</td>
                <td>".htmlspecialchars($row['academic_year'])."</td>
              </tr>";
        $no++;
    }
    echo "</table>";
    exit;
}

$subjects_sql = "SELECT * FROM subjects ORDER BY name ASC";
$subjects_result = $conn->query($subjects_sql);

include 'includes/header.php';
?>

<div class="content-wrapper">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-12 d-flex justify-content-between align-items-center">
                <?php if ($student_info): ?>
                <a href="rekap_nilai.php" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
                </a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (isset($success_message)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo $success_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo $error_message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <?php if (!$student_info): ?>
        <div class="row mb-4">
            <div class="col-12 d-flex flex-wrap justify-content-between align-items-center gap-2">
                <h1 class="page-title mb-0"><?php echo $page_title; ?></h1>
                <div>
                    <!-- Dropdown download per semester -->
                    <div class="btn-group mb-2">
                        <button type="button" class="btn btn-outline-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-file-excel me-1"></i>Download Rekap Nilai Per Semester
                        </button>
                        <ul class="dropdown-menu">
                            <li>
                                <a class="dropdown-item" href="rekap_nilai.php?export=excel_semester&semester_export=1">Semester 1</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="rekap_nilai.php?export=excel_semester&semester_export=2">Semester 2</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Pilih Kelas dan Siswa</h5>
                    </div>
                    <div class="card-body">
                        <form method="get" action="" class="row g-3">
                            <div class="col-md-4">
                                <label for="class_id" class="form-label">Kelas</label>
                                <select class="form-select" id="class_id" name="class_id" onchange="this.form.submit()">
                                    <option value="0">Pilih Kelas</option>
                                    <?php 
                                    if ($classes_result && $classes_result->num_rows > 0) {
                                        $classes_result->data_seek(0); 
                                        while ($class = $classes_result->fetch_assoc()) {
                                            $selected = ($class_id == $class['id']) ? 'selected' : '';
                                            echo '<option value="' . $class['id'] . '" ' . $selected . '>' . 
                                                  $class['level'] . ' ' . $class['name'] . ' ' . $class['specialization'] . 
                                                 '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-4">
                                <label for="student_id" class="form-label">Siswa</label>
                                <select class="form-select" id="student_id" name="student_id" <?php echo ($class_id == 0) ? 'disabled' : ''; ?>>
                                    <option value="0">Pilih Siswa</option>
                                    <?php 
                                    if ($students_result && $students_result->num_rows > 0) {
                                        while ($student = $students_result->fetch_assoc()) {
                                            echo '<option value="' . $student['id'] . '">' . 
                                                  $student['name'] . ' (' . $student['nis'] . ')' . 
                                                 '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            
                            <div class="col-md-2">
                                <label for="semester" class="form-label">Semester</label>
                                <select class="form-select" id="semester" name="semester">
                                    <option value="1" <?php echo ($semester == 1) ? 'selected' : ''; ?>>Semester 1</option>
                                    <option value="2" <?php echo ($semester == 2) ? 'selected' : ''; ?>>Semester 2</option>
                                </select>
                            </div>
                            
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">Tampilkan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Display all students in a table -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Data Siswa</h5>
                        <?php
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
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if ($students_result && $students_result->num_rows > 0) {
                                        $students_result->data_seek(0); 
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
                                        <td>
                                            <a href="rekap_nilai.php?student_id=<?php echo $student['id']; ?>&class_id=<?php echo $class_id; ?>&semester=<?php echo $semester; ?>&academic_year=<?php echo urlencode($academic_year); ?>" class="btn btn-sm btn-success">
                                                Lihat Nilai
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="5" class="text-center py-4">Pilih kelas untuk melihat data siswa.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php else: ?>
        <!-- Student details and grades display -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">Informasi Siswa</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="../img/Profil.jpg" class="rounded-circle" width="100" alt="Student Photo">
                            <h4 class="mt-3 mb-0"><?php echo htmlspecialchars($student_info['name']); ?></h4>
                            <p class="text-muted"><?php echo htmlspecialchars($student_info['nis']); ?></p>
                        </div>
                        <hr>
                        <div class="mb-2">
                            <strong>Kelas:</strong> 
                            <?php echo htmlspecialchars($student_info['level'] . ' ' . $student_info['class_name'] . ' ' . $student_info['specialization']); ?>
                        </div>
                        <div class="mb-2">
                            <strong>Email:</strong> 
                            <?php echo htmlspecialchars($student_info['email']); ?>
                        </div>
                        <div class="mb-2">
                            <strong>Tahun Masuk:</strong> 
                            <?php echo htmlspecialchars($student_info['year_enrolled']); ?>
                        </div>
                        <?php if (!empty($student_info['phone_number'])): ?>
                        <div class="mb-2">
                            <strong>No. Telepon:</strong> 
                            <?php echo htmlspecialchars($student_info['phone_number']); ?>
                        </div>
                        <?php endif; ?>
                        
                        <div class="d-grid gap-2 mt-4">
                            <a href="detail_siswa.php?id=<?php echo $student_info['id']; ?>" class="btn btn-outline-info">
                                <i class="fas fa-user me-2"></i>Lihat Detail Lengkap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card h-100">
                    <div class="card-header bg-light d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Nilai Semester <?php echo $semester; ?></h5>
                        <div>
                            <select id="semesterSelect" class="form-select form-select-sm d-inline-block w-auto me-2" 
                                    onchange="window.location.href='rekap_nilai.php?student_id=<?php echo $student_id; ?>&class_id=<?php echo $class_id; ?>&semester=' + this.value + '&academic_year=<?php echo urlencode($academic_year); ?>'">
                                <option value="1" <?php echo ($semester == 1) ? 'selected' : ''; ?>>Semester 1</option>
                                <option value="2" <?php echo ($semester == 2) ? 'selected' : ''; ?>>Semester 2</option>
                            </select>
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addGradeModal">
                                <i class="fas fa-plus me-1"></i>Tambah Nilai
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Mata Pelajaran</th>
                                        <th>Nilai Tugas</th>
                                        <th>Nilai UTS</th>
                                        <th>Nilai UAS</th>
                                        <th>Nilai Akhir</th>
                                        <th>Grade</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    if (!empty($grades)) {
                                        $counter = 1;
                                        foreach ($grades as $grade) {
                                    ?>
                                    <tr>
                                        <td><?php echo $counter++; ?></td>
                                        <td><?php echo htmlspecialchars($grade['subject_name']); ?></td>
                                        <td><?php echo number_format($grade['assignment_score'], 1); ?></td>
                                        <td><?php echo number_format($grade['midterm_score'], 1); ?></td>
                                        <td><?php echo number_format($grade['final_score'], 1); ?></td>
                                        <td><strong><?php echo number_format($grade['total_score'], 1); ?></strong></td>
                                        <td>
                                            <span class="badge <?php 
                                                $grade_class = 'bg-danger';
                                                if (substr($grade['letter_grade'], 0, 1) === 'A') $grade_class = 'bg-success';
                                                else if (substr($grade['letter_grade'], 0, 1) === 'B') $grade_class = 'bg-primary';
                                                else if (substr($grade['letter_grade'], 0, 1) === 'C') $grade_class = 'bg-warning';
                                                else if (substr($grade['letter_grade'], 0, 1) === 'D') $grade_class = 'bg-secondary';
                                                echo $grade_class;
                                            ?> px-2 py-1">
                                                <?php echo htmlspecialchars($grade['letter_grade']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-warning me-1" 
                                                    onclick='prepareEditForm(<?php echo json_encode([
                                                        "id" => $grade["id"],
                                                        "subject_id" => $grade["subject_id"],
                                                        "subject_name" => $grade["subject_name"],
                                                        "assignment_score" => $grade["assignment_score"],
                                                        "midterm_score" => $grade["midterm_score"],
                                                        "final_score" => $grade["final_score"]
                                                    ]); ?>)'>
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger" 
                                                    data-bs-toggle="modal" 
                                                    data-bs-target="#deleteGradeModal" 
                                                    onclick="document.getElementById('deleteGradeId').value = <?php echo $grade['id']; ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    } else {
                                    ?>
                                    <tr>
                                        <td colspan="8" class="text-center py-4">Belum ada data nilai untuk semester ini.</td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer bg-light">
                        <?php
                        // Calculate average grade
                        $avg_score = 0;
                        $total_subjects = count($grades);
                        
                        if ($total_subjects > 0) {
                            $sum_score = array_sum(array_column($grades, 'total_score'));
                            $avg_score = $sum_score / $total_subjects;
                        }
                        ?>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>Rata-rata Nilai:</strong> 
                                <span class="badge bg-primary px-3 py-2 ms-2"><?php echo number_format($avg_score, 2); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Add Grade Modal -->
        <div class="modal fade" id="addGradeModal" tabindex="-1" aria-labelledby="addGradeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="add_grade">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addGradeModalLabel">Tambah Nilai Baru</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="subject_id" class="form-label">Mata Pelajaran</label>
                                <select class="form-select" id="subject_id" name="subject_id" required>
                                    <option value="">Pilih Mata Pelajaran</option>
                                    <?php 
                                    if ($subjects_result && $subjects_result->num_rows > 0) {
                                        while ($subject = $subjects_result->fetch_assoc()) {
                                            echo '<option value="' . $subject['id'] . '">' . 
                                                  htmlspecialchars($subject['name']) . 
                                                 '</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="assignment_score" class="form-label">Nilai Tugas</label>
                                <input type="number" class="form-control" id="assignment_score" name="assignment_score" min="0" max="100" step="0.1" required>
                                <div class="form-text">Nilai antara 0-100</div>
                            </div>
                            <div class="mb-3">
                                <label for="midterm_score" class="form-label">Nilai UTS</label>
                                <input type="number" class="form-control" id="midterm_score" name="midterm_score" min="0" max="100" step="0.1" required>
                                <div class="form-text">Nilai antara 0-100</div>
                            </div>
                            <div class="mb-3">
                                <label for="final_score" class="form-label">Nilai UAS</label>
                                <input type="number" class="form-control" id="final_score" name="final_score" min="0" max="100" step="0.1" required>
                                <div class="form-text">Nilai antara 0-100</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Grade Modal -->
        <div class="modal fade" id="editGradeModal" tabindex="-1" aria-labelledby="editGradeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post">
                        <input type="hidden" name="action" value="edit_grade">
                        <input type="hidden" name="grade_id" id="edit_grade_id">
                        <input type="hidden" name="subject_id" id="edit_subject_id_hidden">
                        
                        <div class="modal-header">
                            <h5 class="modal-title" id="editGradeModalLabel">Edit Nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Mata Pelajaran</label>
                                <input type="text" class="form-control" id="edit_subject_name" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="edit_assignment_score" class="form-label">Nilai Tugas</label>
                                <input type="number" class="form-control" id="edit_assignment_score" name="assignment_score" min="0" max="100" step="0.1" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_midterm_score" class="form-label">Nilai UTS</label>
                                <input type="number" class="form-control" id="edit_midterm_score" name="midterm_score" min="0" max="100" step="0.1" required>
                            </div>
                            <div class="mb-3">
                                <label for="edit_final_score" class="form-label">Nilai UAS</label>
                                <input type="number" class="form-control" id="edit_final_score" name="final_score" min="0" max="100" step="0.1" required>
                            </div>
                        </div>
                        
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Grade Modal -->
        <div class="modal fade" id="deleteGradeModal" tabindex="-1" aria-labelledby="deleteGradeModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="post" action="">
                        <input type="hidden" name="action" value="delete_grade">
                        <input type="hidden" name="grade_id" id="deleteGradeId">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteGradeModalLabel">Konfirmasi Hapus Nilai</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>Apakah Anda yakin ingin menghapus nilai ini? Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<script>
    // Edit form
    function prepareEditForm(gradeData) {
        // Set hidden values and form fields
        document.getElementById('edit_grade_id').value = gradeData.id;
        document.getElementById('edit_subject_id_hidden').value = gradeData.subject_id;
        document.getElementById('edit_subject_name').value = gradeData.subject_name;
        document.getElementById('edit_assignment_score').value = gradeData.assignment_score;
        document.getElementById('edit_midterm_score').value = gradeData.midterm_score;
        document.getElementById('edit_final_score').value = gradeData.final_score;

        // Show the modal using Bootstrap modal
        const editModal = new bootstrap.Modal(document.getElementById('editGradeModal'));
        editModal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('class_id');
        const studentSelect = document.getElementById('student_id');
        
        if (classSelect && studentSelect) {
            classSelect.addEventListener('change', function() {
                if (this.value > 0) {
                    studentSelect.disabled = false;
                } else {
                    studentSelect.disabled = true;
                }
            });
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Initialize all Bootstrap modals
        var modals = document.querySelectorAll('.modal');
        modals.forEach(function(modal) {
            new bootstrap.Modal(modal);
        });
    });
</script>

<?php
include 'includes/footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>