<?php

require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">Chi tiết đơn hàng <small class="text-muted">#<?= e($order['id']) ?></small></h1>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Người nhận</h6>
                        <p class="mb-1"><strong><?= e($order['receiver_name']) ?></strong></p>
                        <p class="mb-1"><?= e($order['receiver_phone']) ?></p>
                        <p class="mb-0"><?= e($order['receiver_address']) ?></p>
                    </div>
                    <div class="col-md-6 text-end">
                        <h6>Thông tin đơn hàng</h6>
                        <p class="mb-1">Ngày: <?= formatDate($order['created_at'] ?? '') ?></p>
                        <p class="mb-1">Trạng thái: <span class="badge bg-secondary"><?= e($order['status']) ?></span></p>
                        <p class="mb-0">Tổng tiền: <strong class="text-danger"><?= formatMoney($order['total_price']) ?></strong></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th class="text-center">Đơn giá</th>
                        <th class="text-center">Số lượng</th>
                        <th class="text-end">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($order['order_details'] as $item): ?>
                        <tr>
                            <td style="width:100px;">
                                <img src="<?= url('/public/image/product/' . ($item['image'] ?? 'default.png')) ?>" class="img-fluid rounded" style="width:80px;height:80px;object-fit:cover;">
                            </td>
                            <td>
                                <a href="<?= url('/product/' . $item['product_id']) ?>"><?= e($item['name']) ?></a>
                            </td>
                            <td class="text-center"><?= formatMoney($item['price']) ?></td>
                            <td class="text-center"><?= (int)$item['quantity'] ?></td>
                            <td class="text-end"><strong><?= formatMoney($item['price'] * $item['quantity']) ?></strong></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-4 text-end">
            <a href="<?= url('/order-history') ?>" class="btn btn-outline-secondary">Quay lại lịch sử đơn hàng</a>
            <a href="<?= url('/products') ?>" class="btn btn-primary">Mua tiếp</a>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>