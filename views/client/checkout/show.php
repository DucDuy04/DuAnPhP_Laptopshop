<?php

/**
 * Checkout View
 * Tương đương client/cart/checkout.jsp
 */
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4">Thanh toán</h1>

        <div class="row">
            <!-- Thông tin người nhận -->
            <div class="col-lg-7">
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin người nhận</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= url('/place-order') ?>">
                            <?= Csrf::field() ?>

                            <?php
                            $errors = Session::getErrors();
                            $old = Session::getOldInput();
                            if (!empty($errors) && is_array($errors)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($errors as $err): ?>
                                            <li><?= e($err) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Họ tên *</label>
                                <input type="text" name="receiverName" class="form-control"
                                    value="<?= e($old['receiverName'] ?? $user['fullName'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Địa chỉ nhận hàng *</label>
                                <textarea name="receiverAddress" class="form-control" rows="3" required><?= e($old['receiverAddress'] ?? $user['address'] ?? '') ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Số điện thoại *</label>
                                <input type="tel" name="receiverPhone" class="form-control"
                                    value="<?= e($old['receiverPhone'] ?? $user['phone'] ?? '') ?>" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ghi chú</label>
                                <textarea name="note" class="form-control" rows="2" placeholder="Ghi chú về đơn hàng..."><?= e($old['note'] ?? '') ?></textarea>
                            </div>

                            <?php Session::clearValidation(); ?>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fa fa-check-circle me-2"></i>Đặt hàng
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Thông tin đơn hàng -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Đơn hàng của bạn</h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($cartDetails as $item): ?>
                            <div class="d-flex mb-3 pb-3 border-bottom">
                                <img src="<?= url('/public/image/product/' . ($item['image'] ?? 'default.png')) ?>"
                                    class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                <div class="ms-3 flex-grow-1">
                                    <h6 class="mb-1"><?= e($item['name']) ?></h6>
                                    <p class="mb-0 text-muted small">
                                        <?= formatMoney($item['price']) ?> x <?= $item['quantity'] ?>
                                    </p>
                                </div>
                                <div class="text-end">
                                    <strong><?= formatMoney($item['price'] * $item['quantity']) ?></strong>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <h5 class="mb-0">Tổng cộng:</h5>
                            <h4 class="mb-0 text-primary"><?= formatMoney($totalPrice) ?></h4>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="<?= url('/cart') ?>" class="btn btn-outline-secondary w-100">
                        <i class="fa fa-arrow-left me-2"></i>Quay lại giỏ hàng
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>