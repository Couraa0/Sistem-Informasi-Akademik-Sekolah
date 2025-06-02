<div class="sidebar" id="mainSidebar">
    <div class="p-3 border-bottom d-flex align-items-center justify-content-between">
        <span class="h5 mb-0">
            <i class="fas fa-school me-2"></i>
            <span class="sidebar-title-text"><?php echo $site_name; ?></span>
        </span>
        <!-- Tombol titik tiga hanya muncul di mobile -->
        <button id="sidebarClose" class="btn btn-sm d-md-none" style="font-size:1.5rem;line-height:1;">
            <i class="fas fa-ellipsis-v"></i>
        </button>
    </div>
    <div class="py-2">
        <a href="dashboard.php" class="sidebar-link <?php echo $page == 'dashboard' ? 'active' : ''; ?>">
            <i class="fas fa-home sidebar-icon"></i>
            <span class="sidebar-link-text">Dashboard</span>
        </a>
        <a href="biodata.php" class="sidebar-link <?php echo $page == 'biodata' ? 'active' : ''; ?>">
            <i class="fas fa-user sidebar-icon"></i>
            <span class="sidebar-link-text">Biodata</span>
        </a>
        <a href="akademik.php" class="sidebar-link <?php echo $page == 'akademik' ? 'active' : ''; ?>">
            <i class="fas fa-graduation-cap sidebar-icon"></i>
            <span class="sidebar-link-text">Akademik</span>
        </a>
        <a href="jadwal.php" class="sidebar-link <?php echo $page == 'jadwal' ? 'active' : ''; ?>">
            <i class="fas fa-calendar-alt sidebar-icon"></i>
            <span class="sidebar-link-text">Jadwal Mata Pelajaran</span>
        </a>
        <a href="tugas.php" class="sidebar-link <?php echo $page == 'tugas' ? 'active' : ''; ?>">
            <i class="fas fa-chalkboard-teacher sidebar-icon"></i>
            <span class="sidebar-link-text">Tugas</span>
        </a>
    </div>
</div>
<!-- Tombol titik tiga untuk buka sidebar (hanya mobile) -->
<button id="sidebarOpen" class="btn btn-primary d-md-none position-fixed" style="top:18px;left:18px;z-index:1040;font-size:1.5rem;display:none;">
    <i class="fas fa-ellipsis-v"></i>
</button>
<style>
/* Responsive sidebar */
.sidebar {
    width: 250px;
    min-height: 100vh;
    background: #fff;
    border-right: 1px solid #eee;
    position: fixed;
    top: 0;
    left: 0;
    z-index: 1020;
    transition: transform 0.3s;
}
.sidebar-link {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    color: #333;
    text-decoration: none;
    transition: background 0.2s;
}
.sidebar-link .sidebar-icon {
    font-size: 1.3rem;
    min-width: 28px;
    text-align: center;
}
.sidebar-link.active, .sidebar-link:hover {
    background: #f0f0f0;
    color: #007bff;
}
@media (max-width: 767.98px) {
    .sidebar {
        transform: translateX(-100%);
        width: 30px; 
        box-shadow: 2px 0 8px rgba(0,0,0,0.08);
    }
    .sidebar.show {
        transform: translateX(0);
    }
    .sidebar-link-text,
    .sidebar-title-text {
        display: none !important;
    }
    .sidebar-link {
        justify-content: center;
        padding: 18px 0;
    }
    .sidebar .p-3 {
        padding-left: 0 !important;
        padding-right: 0 !important;
        justify-content: center !important;
    }
    #sidebarOpen {
        display: block !important;
    }
}
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var sidebar = document.getElementById('mainSidebar');
    var openBtn = document.getElementById('sidebarOpen');
    var closeBtn = document.getElementById('sidebarClose');

    // Buka sidebar
    if (openBtn) {
        openBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.add('show');
        });
    }
    // Tutup sidebar
    if (closeBtn) {
        closeBtn.addEventListener('click', function(e) {
            e.stopPropagation();
            sidebar.classList.remove('show');
        });
    }
    // Klik di luar sidebar untuk menutup (khusus mobile)
    document.addEventListener('click', function(e) {
        if (window.innerWidth < 768) {
            if (!sidebar.contains(e.target) && !openBtn.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
});
</script>