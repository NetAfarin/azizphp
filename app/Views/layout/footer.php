<?php //if(BASE_URL."/user/login2" != $url && BASE_URL."/user/register2" != $url && BASE_URL."/user/otp" != $url && BASE_URL."/user/add" != $url) : ?>

<!--<footer class="mt-5 text-center text-muted">-->
<!--    <hr>-->
<!--    <small>© --><?php //= date('Y') ?><!-- My Custom Framework</small>-->
<!--</footer>-->
<?php //endif;?>
<!-- MDB CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.3.0/mdb.min.css" rel="stylesheet"/>
<!--<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.3.0/mdb.umd.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?= asset('js/select2.min.js') ?>"></script>


<script>
    // Toast
    const toastEl = document.getElementById('showToast');
    if (toastEl) {
        const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
        toast.show();
    }
</script>
<script
        type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/9.3.0/mdb.umd.min.js"
></script>
</body>
</html>
