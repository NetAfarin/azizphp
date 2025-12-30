<?php //if(BASE_URL."/user/login2" != $url && BASE_URL."/user/register2" != $url && BASE_URL."/user/otp" != $url && BASE_URL."/user/add" != $url) : ?>

<!--<footer class="mt-5 text-center text-muted">-->
<!--    <hr>-->
<!--    <small>© --><?php //= date('Y') ?><!-- My Custom Framework</small>-->
<!--</footer>-->
<?php //endif;?>
<!-- MDB CSS -->
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.3.0/mdb.umd.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/clockpicker/0.0.7/bootstrap-clockpicker.min.js"></script>
<script src="<?= asset('js/select2.min.js') ?>"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.3.0/mdb.umd.min.js"></script>
<script>
    window.addEventListener('load', function () {
        document.documentElement.style.visibility = 'visible';
    });
    const toastEl = document.getElementById('showToast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    }
    $(document).ready(function() {
        const $toggleButton = $('#darkModeToggle');
        const $body = $('body');
        const savedTheme = localStorage.getItem('theme') || 'light';
        $body.attr('data-theme', savedTheme);
        if (savedTheme === 'dark') {
            $toggleButton.html('<i class="fas fa-sun"></i>');
        } else {
            $toggleButton.html('<i class="fas fa-moon"></i>');
        }
        $toggleButton.on('click', function() {
            const currentTheme = $body.attr('data-theme');
            const newTheme = currentTheme === 'light' ? 'dark' : 'light';
            $body.attr('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            if (newTheme === 'dark') {
                $toggleButton.html('<i class="fas fa-sun"></i>');
            } else {
                $toggleButton.html('<i class="fas fa-moon"></i>');
            }
            $body.addClass('theme-changing');
            setTimeout(() => {
                $body.removeClass('theme-changing');
            }, 300);
        });
    });
</script>
</body>
</html>
