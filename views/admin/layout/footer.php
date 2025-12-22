<?php


?>
</main>
<footer class="py-4 bg-light mt-auto">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between small">
            <div class="text-muted">Copyright &copy; LaptopShop <?= date('Y') ?></div>
        </div>
    </div>
</footer>
</div><!-- End layoutSidenav_content -->
</div><!-- End layoutSidenav -->

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

<!-- SB Admin JS -->
<script src="<?= asset('js/scripts.js') ?>"></script>

<?php
// Hiển thị flash messages (success / error) cho khu admin
$flashSuccess = Session::flash('success');
$flashError = Session::flash('error');
if ($flashSuccess || $flashError): ?>
    <div id="admin-flash" style="position:fixed;top:76px;right:20px;z-index:1080;max-width:40%;width:auto;">
        <?php if ($flashSuccess): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= e($flashSuccess) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>

        <?php if ($flashError): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= e($flashError) ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php endif; ?>
    </div>
<?php endif; ?>

</body>

</html>