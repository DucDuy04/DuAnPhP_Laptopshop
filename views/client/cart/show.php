<?php

/**
 * Cart View
 * Tương đương client/cart/show.jsp
 */
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid py-5">
    <div class="container py-5">
        <h1 class="mb-4">Giỏ hàng của bạn</h1>

        <?php if ($isCartEmpty): ?>
            <div class="text-center py-5">
                <i class="fa fa-shopping-cart fa-5x text-muted mb-4"></i>
                <h4>Giỏ hàng trống</h4>
                <p class="text-muted">Bạn chưa có sản phẩm nào trong giỏ hàng.</p>
                <a href="<?= url('/products') ?>" class="btn btn-primary">Tiếp tục mua sắm</a>
            </div>
        <?php else:  ?>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">Sản phẩm</th>
                            <th scope="col">Tên</th>
                            <th scope="col">Đơn giá</th>
                            <th scope="col">Số lượng</th>
                            <th scope="col">Thành tiền</th>
                            <th scope="col">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($cartDetails as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?= url('/public/image/product/' . ($item['image'] ?? 'default.png')) ?>"
                                        class="img-fluid rounded" style="width: 80px; height: 80px; object-fit: cover;">
                                </td>
                                <td class="align-middle">
                                    <a href="<?= url('/product/' . $item['product_id']) ?>"><?= e($item['name']) ?></a>
                                </td>
                                <td class="align-middle"><?= formatMoney($item['price']) ?></td>
                                <td class="align-middle" style="width: 120px;">
                                    <input type="number" class="form-control" value="<?= $item['quantity'] ?>"
                                        min="1" readonly>
                                </td>
                                <td class="align-middle"><?= formatMoney($item['price'] * $item['quantity']) ?></td>
                                <td class="align-middle">
                                    <form action="<?= url('/delete-cart-product/' . $item['id']) ?>" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn xóa sản phẩm này? ')">
                                        <?= Csrf::field() ?>
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="row justify-content-end">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <h5>Tổng tiền:</h5>
                                <h5 class="text-primary"><?= formatMoney($totalPrice) ?></h5>
                            </div>
                            <div class="d-grid gap-2">
                                <a href="<?= url('/checkout') ?>" class="btn btn-primary">
                                    <i class="fa fa-credit-card me-2"></i>Thanh toán
                                </a>
                                <a href="<?= url('/products') ?>" class="btn btn-outline-secondary">
                                    Tiếp tục mua sắm
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>