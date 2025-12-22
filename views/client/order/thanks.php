<?php


require_once __DIR__ . '/../layout/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center py-5 px-4">
                    <!-- Success Icon -->
                    <div class="mb-4">
                        <div class="success-checkmark">
                            <i class="fas fa-check-circle text-success" style="font-size: 80px;"></i>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-success mb-3">Đặt hàng thành công! </h1>

                    <!-- Message -->
                    <p class="text-muted lead mb-4">
                        Cảm ơn bạn đã mua hàng tại <strong class="text-primary">LaptopShop</strong>!
                    </p>

                    <!-- Order Info Box -->
                    <div class="bg-light rounded p-4 mb-4">
                        <div class="row text-start">
                            <div class="col-12 mb-3">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <strong>Thông tin đơn hàng</strong>
                            </div>
                            <div class="col-12">
                                <ul class="list-unstyled mb-0">
                                    <li class="mb-2">
                                        <i class="fas fa-envelope text-muted me-2"></i>
                                        Email xác nhận đã được gửi đến địa chỉ email của bạn.
                                    </li>
                                    <li class="mb-2">
                                        <i class="fas fa-phone text-muted me-2"></i>
                                        Chúng tôi sẽ liên hệ với bạn sớm nhất để xác nhận đơn hàng.
                                    </li>
                                    <li class="mb-0">
                                        <i class="fas fa-truck text-muted me-2"></i>
                                        Thời gian giao hàng dự kiến: 2-5 ngày làm việc.
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="<?= url('/order-history') ?>" class="btn btn-outline-primary px-4 py-2">
                            <i class="fas fa-history me-2"></i>Xem đơn hàng
                        </a>
                        <a href="<?= url('/products') ?>" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-shopping-bag me-2"></i>Tiếp tục mua sắm
                        </a>
                    </div>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="text-center mt-4">
                <p class="text-muted small mb-2">
                    Bạn cần hỗ trợ? Liên hệ với chúng tôi:
                </p>
                <p class="mb-0">
                    <a href="tel:0123456789" class="text-decoration-none me-3">
                        <i class="fas fa-phone text-primary me-1"></i>0123 456 789
                    </a>
                    <a href="mailto:laptopshop@gmail.com" class="text-decoration-none">
                        <i class="fas fa-envelope text-primary me-1"></i>laptopshop@gmail.com
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for animation -->
<style>
    .success-checkmark {
        animation: scaleIn 0.5s ease-in-out;
    }

    @keyframes scaleIn {
        0% {
            transform: scale(0);
            opacity: 0;
        }

        50% {
            transform: scale(1.2);
        }

        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .card {
        border-radius: 15px;
    }

    .bg-light {
        border-radius: 10px;
    }
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>