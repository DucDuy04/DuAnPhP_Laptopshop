<?php


?>
</div><!-- End main content wrapper -->

<!-- Footer Start -->
<div class="container-fluid bg-dark text-white-50 footer pt-5 mt-5">
    <div class="container py-5">
        <div class="row g-5">
            <div class="col-lg-3 col-md-6">
                <h3 class="text-primary mb-4">LaptopShop</h3>
                <p>Cung cấp laptop chính hãng với giá tốt nhất thị trường.</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="text-primary mb-4">Liên hệ</h5>
                <p><i class="fa fa-map-marker-alt me-3"></i>Thành phố Huế, Việt Nam</p>
                <p><i class="fa fa-phone-alt me-3"></i>+84 38.255.7317</p>
                <p><i class="fa fa-envelope me-3"></i>laptopshop@gmail.com</p>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="text-primary mb-4">Hỗ trợ</h5>
                <a class="btn btn-link" href="#">Chính sách đổi trả</a>
                <a class="btn btn-link" href="#">Chính sách bảo hành</a>
                <a class="btn btn-link" href="#">Hướng dẫn mua hàng</a>
            </div>
            <div class="col-lg-3 col-md-6">
                <h5 class="text-primary mb-4">Theo dõi</h5>
                <div class="d-flex align-items-center mb-3">
                    <a href="https://www.facebook.com/ducduy.0406"
                        class="btn btn-outline-light btn-square me-2"
                        target="_blank">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.facebook.com/ducduy.0406"
                        class="text-white-50 text-decoration-none"
                        target="_blank">
                        facebook.com/ducduy.0406
                    </a>
                </div>

                <div class="d-flex align-items-center mb-3">
                    <a href="#" class="btn btn-outline-light btn-square me-3" target="_blank">
                        <i class="fab fa-youtube"></i>
                    </a>
                    <a href="#" class="text-white-50 text-decoration-none" target="_blank">
                        youtube.com/channel
                    </a>
                </div>


            </div>
        </div>
    </div> <!-- Back to Top -->
    <a href="#" class="btn btn-primary border-3 border-primary rounded-circle back-to-top">
        <i class="fa fa-arrow-up"></i>
    </a>
</div>
<!-- Footer End -->



<!-- JavaScript Libraries -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>

<!-- Flash Messages -->
<?php if ($success = Session::flash('success')): ?>
    <script>
        $.toast({
            heading: 'Thành công',
            text: '<?= e($success) ?>',
            showHideTransition: 'slide',
            icon: 'success',
            position: 'top-right'
        });
    </script>
<?php endif; ?>

<?php if ($error = Session::flash('error')): ?>
    <script>
        $.toast({
            heading: 'Lỗi',
            text: '<?= e($error) ?>',
            showHideTransition: 'slide',
            icon: 'error',
            position: 'top-right'
        });
    </script>
<?php endif; ?>
</body>

</html>