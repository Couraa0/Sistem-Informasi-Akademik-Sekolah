<?php
$page = 'jadwal';
$page_title = 'Jadwal Mata Pelajaran';

require_once 'config.php';
requireLogin();

$student_id = $_SESSION['user_id'];
$student_sql = "SELECT s.*, c.id as class_id, c.name as class_name 
                FROM students s 
                LEFT JOIN classes c ON s.class_id = c.id 
                WHERE s.id = $student_id";
$student_result = $conn->query($student_sql);
$student = $student_result->fetch_assoc();

$current_year = date('Y');
$academic_year = ($current_year) . '/' . ($current_year + 1);
$semester = (date('n') > 6) ? 1 : 2; 

$schedules_sql = "SELECT sch.*, s.code as subject_code, s.name as subject_name, 
                 t.name as teacher_name, sch.room
                 FROM schedules sch
                 JOIN subjects s ON sch.subject_id = s.id
                 JOIN teachers t ON sch.teacher_id = t.id
                 WHERE sch.class_id = {$student['class_id']}
                   AND sch.academic_year = '$academic_year'
                   AND sch.semester = $semester
                 ORDER BY FIELD(sch.day_of_week, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'), 
                          sch.start_time";
$schedules_result = $conn->query($schedules_sql);

$schedules = [];
if ($schedules_result->num_rows > 0) {
    while ($row = $schedules_result->fetch_assoc()) {
        $schedules[] = [
            'subject_name' => $row['subject_name'],
            'subject_code' => $row['subject_code'],
            'teacher_name' => $row['teacher_name'],
            'type' => 'Offline', 
            'day' => $row['day_of_week'],
            'start_time' => date('H:i', strtotime($row['start_time'])),
            'end_time' => date('H:i', strtotime($row['end_time'])),
            'room' => $row['room']
        ];
    }
} else {
    $schedules = [
        [
            'subject_name' => 'Matematika',
            'subject_code' => 'MTK14827',
            'teacher_name' => 'Budi Santoso',
            'type' => 'Offline',
            'day' => 'Senin',
            'start_time' => '08:00',
            'end_time' => '09:30',
            'room' => 'Ruang 101'
        ],
        [
            'subject_name' => 'Bahasa Inggris',
            'subject_code' => 'BIG11227',
            'teacher_name' => 'Siti Aminah',
            'type' => 'Offline',
            'day' => 'Selasa',
            'start_time' => '10:00',
            'end_time' => '11:30',
            'room' => 'Ruang 102'
        ],
        [
            'subject_name' => 'Kimia',
            'subject_code' => 'KIM12345',
            'teacher_name' => 'Andi Prasetyo',
            'type' => 'Offline',
            'day' => 'Rabu',
            'start_time' => '13:00',
            'end_time' => '14:30',
            'room' => 'Ruang 103'
        ],
        [
            'subject_name' => 'Biologi',
            'subject_code' => 'BIO67890',
            'teacher_name' => 'Dewi Lestari',
            'type' => 'Offline',
            'day' => 'Kamis',
            'start_time' => '08:00',
            'end_time' => '09:30',
            'room' => 'Ruang 104'
        ],
    ];
}

$search_query = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';

$filtered_schedules = array_filter($schedules, function ($schedule) use ($search_query) {
    return empty($search_query) ||
        strpos(strtolower($schedule['subject_name']), $search_query) !== false ||
        strpos(strtolower($schedule['subject_code']), $search_query) !== false ||
        strpos(strtolower($schedule['teacher_name']), $search_query) !== false;
});

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="id">
<head></head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Sistem Akademik</title>
    <link rel="stylesheet" href="css/jadwal.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <div style="color:white">............................................................................................................................................................................................................................................................................................................................................................</div>

<body>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <h2><?= $page_title ?></h2>
                </div>
            </div>      
            <div class="row">
                <div class="col-12">
                    <form method="get" action="" class="search-bar">
                        <input type="text" class="form-control" name="search" placeholder="Mata Pelajaran / Kode Mata Pelajaran / Guru" value="<?= htmlspecialchars($search_query) ?>">
                        <button type="submit" class="btn">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="row">
                <?php if (!empty($filtered_schedules)): ?>
                    <?php foreach ($filtered_schedules as $schedule): ?>
                    <div class="col-12">
                        <div class="subject-card">
                            <div>
                                <div class="subject-name"><?= htmlspecialchars($schedule['subject_name']) ?></div>
                                <div>
                                    <span class="subject-code"><?= htmlspecialchars($schedule['subject_code']) ?></span>
                                    <span class="subject-type"><?= htmlspecialchars($schedule['type']) ?></span>
                                </div>
                                <div class="teacher-info">
                                    <div>Guru Mata Pelajaran</div>
                                    <div class="teacher-name"><?= htmlspecialchars($schedule['teacher_name']) ?></div>
                                </div>
                            </div>
                            <div class="schedule-info">
                                <div>
                                    <i class="far fa-clock"></i> Hari dan Waktu
                                </div>
                                <div>
                                    <?= htmlspecialchars($schedule['day']) ?>, <?= $schedule['start_time'] ?> - <?= $schedule['end_time'] ?>
                                </div>
                                <div class="room-info">
                                    <div>
                                        <i class="fas fa-door-open"></i> Ruang Kelas
                                    </div>
                                    <div>
                                        <?= htmlspecialchars($schedule['room']) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12">
                        <div class="alert alert-info">Tidak ada jadwal yang ditemukan.</div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
    include 'includes/footer.php';
    ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
</body>
</html>