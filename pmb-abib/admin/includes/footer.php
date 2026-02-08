    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Mobile sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileToggle = document.querySelector('.mobile-toggle');
            const sidebar = document.getElementById('sidebar');
            
            if (mobileToggle) {
                mobileToggle.addEventListener('click', function() {
                    if (window.innerWidth <= 992) {
                        if (sidebar.style.transform === 'translateX(0px)' || sidebar.style.transform === '') {
                            sidebar.style.transform = 'translateX(-100%)';
                        } else {
                            sidebar.style.transform = 'translateX(0px)';
                        }
                    }
                });
            }
        });
    </script>
</body>
</html>