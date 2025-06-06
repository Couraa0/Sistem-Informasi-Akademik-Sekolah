<?php
require_once '../config.php';

// Tambahkan ini agar fungsi requireTeacherLogin tersedia
if (!function_exists('requireTeacherLogin')) {
    function requireTeacherLogin() {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'teacher') {
            header("Location: ../allert.php");
            exit;
        }
    }
}

requireTeacherLogin();

$page = 'tugas_siswa';
$page_title = 'Kelola Tugas Siswa';

// Ambil data assignments
$assignments = [];
$sql = "SELECT a.*, s.name as subject_name, c.name as class_name 
        FROM assignments a
        LEFT JOIN subjects s ON a.subject_id = s.id
        LEFT JOIN classes c ON a.class_id = c.id
        ORDER BY a.deadline DESC";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $assignments[] = $row;
    }
}

// Ambil data subjects dan classes untuk form
$subjects = $conn->query("SELECT * FROM subjects ORDER BY name ASC");
$classes = $conn->query("SELECT * FROM classes ORDER BY level, name ASC");

// Handle tambah/edit/hapus
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        $title = $conn->real_escape_string($_POST['title'] ?? '');
        $subject_id = intval($_POST['subject_id'] ?? 0);
        $class_id = intval($_POST['class_id'] ?? 0);
        $deadline = $conn->real_escape_string($_POST['deadline'] ?? '');
        $teacher_id = $_SESSION['user_id'];
        if ($_POST['action'] === 'add') {
            $insert = "INSERT INTO assignments (title, subject_id, class_id, teacher_id, deadline, created_at) 
                       VALUES ('$title', $subject_id, $class_id, $teacher_id, '$deadline', NOW())";
            $conn->query($insert);
        } elseif ($_POST['action'] === 'edit' && isset($_POST['assignment_id'])) {
            $assignment_id = intval($_POST['assignment_id']);
            $update = "UPDATE assignments SET title='$title', subject_id=$subject_id, class_id=$class_id, deadline='$deadline' 
                       WHERE id=$assignment_id";
            $conn->query($update);
        } elseif ($_POST['action'] === 'delete' && isset($_POST['assignment_id'])) {
            $assignment_id = intval($_POST['assignment_id']);
            $delete = "DELETE FROM assignments WHERE id=$assignment_id";
            $conn->query($delete);
        }
        header("Location: tugas_siswa.php");
        exit;
    }
}

include 'includes/header.php';
?>

<style>
/* Table style for compact view */
.table-compact th, .table-compact td {
    padding: 0.4rem 0.5rem !important;
    font-size: 14px;
}
.table-compact th {
    background: #f8f9fa;
}
@media (max-width: 767.98px) {
    .table-compact th, .table-compact td {
        font-size: 12px;
        padding: 0.3rem 0.3rem !important;
    }
    .modal-dialog {
        max-width: 98vw;
        margin: 1rem auto;
    }
}
/* Tambahkan margin kiri agar konten tidak menempel sidebar pada desktop */
@media (min-width: 768px) {
    .container-fluid.px-4 {
        margin-left: 250px;
    }
}
</style>

<div class="container-fluid px-4">
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="mb-0"><?php echo $page_title; ?></h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="fas fa-plus me-1"></i>Tambah Tugas
            </button>
        </div>
    </div>
    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle table-compact">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Judul</th>
                        <th>Mata Pelajaran</th>
                        <th>Kelas</th>
                        <th>Deadline</th>
                        <th>Dibuat</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($assignments): $no=1; foreach ($assignments as $a): ?>
                    <tr>
                        <td><?php echo $no++; ?></td>
                        <td><?php echo htmlspecialchars($a['title']); ?></td>
                        <td><?php echo htmlspecialchars($a['subject_name']); ?></td>
                        <td><?php echo htmlspecialchars($a['class_name']); ?></td>
                        <td><?php echo htmlspecialchars(date('d M Y', strtotime($a['deadline']))); ?></td>
                        <td><?php echo htmlspecialchars(date('d M Y', strtotime($a['created_at']))); ?></td>
                        <td>
                            <button class="btn btn-warning btn-sm me-1" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editModal"
                                data-id="<?php echo $a['id']; ?>"
                                data-title="<?php echo htmlspecialchars($a['title'], ENT_QUOTES); ?>"
                                data-subject="<?php echo $a['subject_id']; ?>"
                                data-class="<?php echo $a['class_id']; ?>"
                                data-deadline="<?php echo $a['deadline']; ?>">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="post" action="" class="d-inline" onsubmit="return confirm('Yakin hapus tugas ini?');">
                                <input type="hidden" name="action" value="delete">
                                <input type="hidden" name="assignment_id" value="<?php echo $a['id']; ?>">
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr>
                        <td colspan="7" class="text-center">Belum ada tugas.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <input type="hidden" name="action" value="add">
      <div class="modal-header">
        <h5 class="modal-title" id="addModalLabel">Tambah Tugas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mata Pelajaran</label>
            <select name="subject_id" class="form-select" required>
                <option value="">Pilih Mata Pelajaran</option>
                <?php if ($subjects) while($s = $subjects->fetch_assoc()): ?>
                    <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="class_id" class="form-select" required>
                <option value="">Pilih Kelas</option>
                <?php if ($classes) while($c = $classes->fetch_assoc()): ?>
                    <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['name']); ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" name="deadline" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form method="post" class="modal-content">
      <input type="hidden" name="action" value="edit">
      <input type="hidden" name="assignment_id" id="edit-assignment-id">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Edit Tugas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Judul</label>
            <input type="text" name="title" id="edit-title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mata Pelajaran</label>
            <select name="subject_id" id="edit-subject" class="form-select" required>
                <!-- Akan diisi via JS -->
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="class_id" id="edit-class" class="form-select" required>
                <!-- Akan diisi via JS -->
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Deadline</label>
            <input type="date" name="deadline" id="edit-deadline" class="form-control" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>

<script>
// Data subjects dan classes untuk edit (agar tidak reload query)
const subjects = <?php
    $subjects2 = $conn->query("SELECT * FROM subjects ORDER BY name ASC");
    $arr = [];
    while($s = $subjects2->fetch_assoc()) $arr[] = $s;
    echo json_encode($arr);
?>;
const classes = <?php
    $classes2 = $conn->query("SELECT * FROM classes ORDER BY level, name ASC");
    $arr2 = [];
    while($c = $classes2->fetch_assoc()) $arr2[] = $c;
    echo json_encode($arr2);
?>;

document.addEventListener('DOMContentLoaded', function() {
    var editModal = document.getElementById('editModal');
    editModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        document.getElementById('edit-assignment-id').value = button.getAttribute('data-id');
        document.getElementById('edit-title').value = button.getAttribute('data-title');
        // Mata pelajaran
        var subjectSelect = document.getElementById('edit-subject');
        subjectSelect.innerHTML = '';
        subjects.forEach(function(s) {
            var opt = document.createElement('option');
            opt.value = s.id;
            opt.text = s.name;
            if (s.id == button.getAttribute('data-subject')) opt.selected = true;
            subjectSelect.appendChild(opt);
        });
        // Kelas
        var classSelect = document.getElementById('edit-class');
        classSelect.innerHTML = '';
        classes.forEach(function(c) {
            var opt = document.createElement('option');
            opt.value = c.id;
            opt.text = c.name;
            if (c.id == button.getAttribute('data-class')) opt.selected = true;
            classSelect.appendChild(opt);
        });
        document.getElementById('edit-deadline').value = button.getAttribute('data-deadline');
    });
});
</script>

<?php include 'includes/footer.php'; ?>
