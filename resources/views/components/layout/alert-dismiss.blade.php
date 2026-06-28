<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.auto-dismiss-alert').forEach(function (alert) {
            setTimeout(function () {
                alert.classList.add('gh-alert-fade-out');
                setTimeout(function () {
                    alert.remove();
                }, 500);
            }, 8000);
        });
    });
</script>
