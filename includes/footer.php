<?php if (isLoggedIn() && !isset($is_login_page)): ?>
    <a href="https://wa.me/6287871310560" class="chat-button">
        <i class="fas fa-comment-dots chat-icon"></i>
    </a>
<?php endif; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
/* Responsive chat button */
.chat-button {
    position: fixed;
    bottom: 80px;
    right: 30px;
    z-index: 9999;
    background:rgb(37, 211, 102);
    color: #fff;
    border-radius: 50%;
    width: 56px;
    height: 56px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    font-size: 2rem;
    transition: background 0.2s;
}
.chat-button:hover {
    background: #128c7e;
    color: #fff;
}
@media (max-width: 576px) {
    .chat-button {
        right: 15px;
        bottom: 70px;
        width: 48px;
        height: 48px;
        font-size: 1.5rem;
    }
    .footer-copyright {
        font-size: 13px;
        padding-bottom: 60px;
    }
}
</style>

<script>
$(document).ready(function() {
    $('#sidebarToggle').on('click', function() {
        $('.sidebar').toggleClass('show');
    });
    
    $(document).on('click', function(e) {
        if ($(window).width() < 768) {
            if (!$(e.target).closest('.sidebar').length && !$(e.target).closest('#sidebarToggle').length) {
                $('.sidebar').removeClass('show');
            }
        }
    });
});
</script>

<div class="footer-copyright text-center mt-4 mb-2">
    <p>Copyright &copy; <?php echo date('Y'); ?> Kelompok 6 PBW. All Rights Reserved.</p>
</div>