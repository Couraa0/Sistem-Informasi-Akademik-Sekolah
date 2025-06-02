<footer class="footer mt-5 p-3 text-center">
    <p class="mb-0 small"></p>
        <div class="footer-copyright" style="text-align: center">
            <p>Copyright &copy; <?php echo date('Y'); ?> Kelompok 6 PBW. All Rights Reserved.</p>
                </div>
</footer>
    </div> 
    
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('active');
            document.querySelector('.content-wrapper').classList.toggle('active');
        });
    </script>
</body>
</html>