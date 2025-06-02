<?php
$page = 'dashboard';
$page_title = 'Dashboard';

require_once 'config.php';
requireLogin();

$student_id = $_SESSION['user_id'];
$student_sql = "SELECT s.*, c.name as class_name, c.specialization 
                FROM students s 
                LEFT JOIN classes c ON s.class_id = c.id 
                WHERE s.id = $student_id";
$student_result = $conn->query($student_sql);
$student = $student_result->fetch_assoc();

$news_sql = "SELECT n.*, t.name as author_name 
             FROM news n 
             LEFT JOIN teachers t ON n.author_id = t.id 
             WHERE n.is_published = 1 
             ORDER BY n.published_at DESC 
             LIMIT 3";
$news_result = $conn->query($news_sql);

$faq_sql = "SELECT * FROM faqs WHERE is_published = 1 ORDER BY id LIMIT 3";
$faq_result = $conn->query($faq_sql);

include 'includes/header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="content-card">
                <h2>Selamat Datang <?php echo htmlspecialchars($student['name']); ?></h2>
                <p>Anda masuk sebagai siswa <?php echo htmlspecialchars($student['specialization'] ?? 'MIPA'); ?></p>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="col-12 mb-4">
            <h5>Berita Terbaru</h5>
        </div>
        <?php if ($news_result->num_rows > 0): ?>
            <?php while ($news = $news_result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card dashboard-card">
                        <?php if (!empty($news['image'])): ?>
                            <img src="img/<?php echo htmlspecialchars($news['image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($news['title']); ?>">
                        <?php else: ?>
                            <img src="" class="card-img-top" alt="Thumbnail">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($news['title']); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars(substr($news['content'], 0, 100) . '...'); ?></p>
                            <a href="landingpage.php?id=<?= $news['id']; ?>" class="btn btn-primary btn-sm">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">Belum ada berita terbaru.</div>
            </div>
        <?php endif; ?>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="content-card">
                <h5>Frequently Asked Question</h5>
                <p class="text-muted">Pertanyaan yang sering diajukan</p>
                
                <div class="accordion" id="faqAccordion">
                    <?php if ($faq_result->num_rows > 0): ?>
                        <?php $counter = 1; ?>
                        <?php while ($faq = $faq_result->fetch_assoc()): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?php echo $counter; ?>">
                                        <?php echo htmlspecialchars($faq['question']); ?>
                                    </button>
                                </h2>
                                <div id="faq<?php echo $counter; ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        <?php echo htmlspecialchars($faq['answer']); ?>
                                    </div>
                                </div>
                            </div>
                            <?php $counter++; ?>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="alert alert-info">Belum ada FAQ tersedia.</div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-md-6">
    <div class="content-card">
        <h5>Pengumuman</h5>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <a href="https://drive.google.com/file/d/1vznPodg4BZTu0f2-wsu_7dDtBBLT8rI3/view?usp=sharing" target="_blank">
                    Pedoman Pembelajaran Akademik Tahun 2024/2025
                </a>
            </li>
            <li class="list-group-item">
                <a href="https://drive.google.com/file/d/1znqo04QGebk9V_bdAH6a69oEFq-bQAB5/view?usp=sharing" target="_blank">
                    Perpanjangan Pembayaran UKT Semester Genap
                </a>
            </li>
            <li class="list-group-item">
                <a href="https://drive.google.com/file/d/18YGdWWxpG7vndk33VAuiQRkMsr9WhpdU/view?usp=sharing" target="_blank">
                    Pelaksanaan UAS Semester Genap
                </a>
            </li>
            <li class="list-group-item">
                <a href="https://drive.google.com/file/d/1NoIaPNkIOUVXMKWMt12FI1B_DgIFcRoj/view?usp=sharing" target="_blank">
                    Kalender Akademik Tahun 2024/2025
                </a>
            </li>
            <li class="list-group-item">
                <a href="https://drive.google.com/file/d/19Xbek5hkOQoY-CIYzPIA9l9l49cS249N/view?usp=sharing" target="_blank">
                    Libur Nasional dan Cuti Bersama Hari Raya Idul Fitri 1446 H
                </a>
            </li>
        </ul>
    </div>
</div>
</div>
</div>

<?php
include 'includes/footer.php';
?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>