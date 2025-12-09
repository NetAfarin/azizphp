<?php //if(BASE_URL."/user/login2" != $url && BASE_URL."/user/register2" != $url && BASE_URL."/user/otp" != $url && BASE_URL."/user/add" != $url) : ?>

<!--<footer class="mt-5 text-center text-muted">-->
<!--    <hr>-->
<!--    <small>© --><?php //= date('Y') ?><!-- My Custom Framework</small>-->
<!--</footer>-->
<?php //endif;?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('js/select2.min.js') ?>"></script>
<script>
    const toastEl = document.getElementById('showToast');
    console.log("toast = " + toastEl)
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    }
</script>
</body>
</html>
