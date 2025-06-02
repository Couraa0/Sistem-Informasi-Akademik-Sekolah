<?php
$page = 'tugas';
$page_title = 'Tugas';

require_once 'config.php';
requireLogin();

$student_id = $_SESSION['user_id'];
$student_sql = "SELECT s.*, c.id as class_id 
                FROM students s 
                LEFT JOIN classes c ON s.class_id = c.id 
                WHERE s.id = $student_id";
$student_result = $conn->query($student_sql);
$student = $student_result->fetch_assoc();
$class_id = $student['class_id'] ?? 0;

$subjects_sql = "SELECT * FROM subjects ORDER BY name";
$subjects_result = $conn->query($subjects_sql);

$subjects = [];
if ($subjects_result->num_rows > 0) {
    while ($row = $subjects_result->fetch_assoc()) {
        $schedule_sql = "SELECT * FROM schedules 
                        WHERE subject_id = {$row['id']} 
                        AND class_id = $class_id 
                        ORDER BY day_of_week, start_time 
                        LIMIT 1";
        $schedule_result = $conn->query($schedule_sql);
        $schedule = $schedule_result->fetch_assoc();
        
        $start_time = isset($schedule) ? date('H:i', strtotime($schedule['start_time'])) : '08:00';
        $end_time = isset($schedule) ? date('H:i', strtotime($schedule['end_time'])) : '09:30';
        
        $subjects[] = [
            'id' => $row['id'],
            'name' => $row['name'],
            'active' => ($row['id'] == ($_GET['subject'] ?? 0)),
            'time' => "$start_time - $end_time"
        ];
    }
} else {
    $subjects = [
        ['id' => 1, 'name' => 'Matematika', 'active' => true, 'time' => '08:00 - 09:30'],
        ['id' => 2, 'name' => 'Bahasa Inggris', 'active' => false, 'time' => '10:00 - 11:30'],
        ['id' => 3, 'name' => 'Biologi', 'active' => false, 'time' => '08:00 - 09:30'],
        ['id' => 4, 'name' => 'Kimia', 'active' => false, 'time' => '10:00 - 11:30'],
        ['id' => 5, 'name' => 'Fisika', 'active' => false, 'time' => '08:00 - 09:30']
    ];
}

$selected_subject_id = isset($_GET['subject']) ? intval($_GET['subject']) : ($subjects[0]['id'] ?? 1);

foreach ($subjects as &$subject) {
    $subject['active'] = ($subject['id'] == $selected_subject_id);
}
unset($subject); 

$selected_subject = array_filter($subjects, function($subject) use ($selected_subject_id) {
    return $subject['id'] == $selected_subject_id;
});
$selected_subject = reset($selected_subject) ?: ['name' => 'Matematika', 'time' => '08:00 - 09:30'];

$assignments_sql = "SELECT a.*, s.name as subject_name, t.name as teacher_name 
                   FROM assignments a
                   JOIN subjects s ON a.subject_id = s.id
                   JOIN teachers t ON a.teacher_id = t.id
                   WHERE a.subject_id = $selected_subject_id
                   AND a.class_id = $class_id
                   ORDER BY a.deadline DESC";

$table_check = $conn->query("SHOW TABLES LIKE 'assignments'");
$assignments_table_exists = $table_check->num_rows > 0;

$current_assignments = [];
if ($assignments_table_exists) {
    $assignments_result = $conn->query($assignments_sql);
    
    if ($assignments_result && $assignments_result->num_rows > 0) {
        while ($row = $assignments_result->fetch_assoc()) {
            $current_assignments[] = [
                'title' => $row['title'],
                'date' => date('d F Y', strtotime($row['created_at'])),
                'deadline' => date('d F Y', strtotime($row['deadline'])),
                'teacher' => $row['teacher_name']
            ];
        }
    }
}

if (empty($current_assignments)) {
    $assignments_by_subject = [
        'Matematika' => [
            ['title' => 'Kerjakan Soal Aljabar', 'date' => '24 Maret 2025', 'deadline' => '25 Maret 2025', 'teacher' => 'Budi Santoso'],
            ['title' => 'Latihan Trigonometri', 'date' => '21 Maret 2025', 'deadline' => '22 Maret 2025', 'teacher' => 'Budi Santoso']
        ],
        'Bahasa Inggris' => [
            ['title' => 'Essay tentang Lingkungan', 'date' => '23 Maret 2025', 'deadline' => '24 Maret 2025', 'teacher' => 'Siti Aminah'],
            ['title' => 'Latihan Grammar', 'date' => '20 Maret 2025', 'deadline' => '21 Maret 2025', 'teacher' => 'Siti Aminah']
        ],
        'Biologi' => [
            ['title' => 'Laporan Praktikum Fotosintesis', 'date' => '22 Maret 2025', 'deadline' => '23 Maret 2025', 'teacher' => 'Dewi Lestari'],
            ['title' => 'Presentasi Ekosistem', 'date' => '19 Maret 2025', 'deadline' => '20 Maret 2025', 'teacher' => 'Dewi Lestari']
        ],
        'Kimia' => [
            ['title' => 'Tugas Kimia Dasar', 'date' => '21 Maret 2025', 'deadline' => '22 Maret 2025', 'teacher' => 'Andi Prasetyo'],
            ['title' => 'Praktikum Asam Basa', 'date' => '18 Maret 2025', 'deadline' => '19 Maret 2025', 'teacher' => 'Andi Prasetyo']
        ],
        'Fisika' => [
            ['title' => 'Tugas Gelombang', 'date' => '20 Maret 2025', 'deadline' => '21 Maret 2025', 'teacher' => 'Rina Sari'],
            ['title' => 'Praktikum Hukum Newton', 'date' => '17 Maret 2025', 'deadline' => '18 Maret 2025', 'teacher' => 'Rina Sari']
        ]
    ];
    
    $current_assignments = $assignments_by_subject[$selected_subject['name']] ?? [];
}

include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?> - Sistem Akademik</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="css/tugas.css">
</head>
<body>
    <div class="content-wrapper">
        <div class="d-flex">
            <!-- List mata pelajaran -->
            <div class="sidebar-container">
                <div class="p-3">
                    <h5>Mata Pelajaran</h5>
                </div>
                <ul class="subject-list">
                    <?php foreach ($subjects as $subject): ?>
                    <li class="<?= $subject['active'] ? 'active' : '' ?>">
                        <a href="?subject=<?= $subject['id'] ?>" class="<?= $subject['active'] ? 'text-primary' : '' ?>">
                            <?= htmlspecialchars($subject['name']) ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="h2"><?= $page_title ?></h1>
                    <nav aria-label="breadcrumb" class="breadcrumb-custom">
                    </nav>
                </div>

                <div class="card-matematika">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="display-5 mb-2"><?= htmlspecialchars($selected_subject['name']) ?></h2>
                            <p class="mb-0 fs-5"><?= htmlspecialchars($selected_subject['time']) ?></p>
                        </div>
                        <div class="form-check form-switch">
                            <input class="form-check-input toggle-switch" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
                        </div>
                    </div>
                </div>

                <!-- Upcoming section -->
                <div class="d-flex justify-content-end mb-3">
                    <p class="text-muted">Tanggal: <strong><?= date('l d F Y') ?></strong></p>
                </div>

                <!-- Assignment list -->
                <div class="tasks-container">
                    <?php if (!empty($current_assignments)): ?>
                        <?php foreach ($current_assignments as $assignment): ?>
                        <div class="task-card">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex align-items-center">
                                    <div class="task-icon">
                                        <i class="bi bi-file-earmark-text"></i>
                                    </div>
                                    <div>
                                        <p class="mb-0 fw-medium"><?= htmlspecialchars($assignment['title']) ?></p>
                                        <small class="text-muted">
                                            <?= htmlspecialchars($assignment['date']) ?>
                                            <?php if (!empty($assignment['deadline'])): ?>
                                            <span class="text-muted">(Deadline: <?= htmlspecialchars($assignment['deadline']) ?>)</span>
                                            <?php endif; ?>
                                            <br>
                                            <span class="text-muted">Diupload oleh: <?= htmlspecialchars($assignment['teacher']) ?></span>
                                        </small>
                                    </div>
                                </div>
                                <button class="btn btn-light border-0" type="button">
                                    <i class="bi bi-three-dots-vertical"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="alert alert-info">Tidak ada tugas untuk mata pelajaran ini.</div>
                    <?php endif; ?>
                </div>

                <!-- Button Lihat Semua -->
                <div class="text-end mt-3 mb-4">
                    <a href="#" class="text-decoration-none">Lihat Semua</a>
                </div>
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