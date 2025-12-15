<?php

/**
 * Admin Order Detail View
 * Tương đương admin/order/detailorder.jsp
 */
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Order Detail</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/order') ?>">Orders</a></li>
            <li class="breadcrumb-item active">Detail #<?= $order['id'] ?></li>
        </ol>

        <!-- Order Info -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Mã đơn hàng: </th>
                                <td><strong>#<?= $order['id'] ?></strong></td>
                            </tr>
                            <tr>
                                <th>Trạng thái:</th>
                                <td>
                                    <?php
                                    $statusClass = [
                                        'PENDING' => 'warning',
                                        'SHIPPING' => 'info',
                                        'COMPLETE' => 'success',
                                        'CANCELLED' => 'danger'
                                    ];
                                    $statusText = [
                                        'PENDING' => 'Chờ xử lý',
                                        'SHIPPING' => 'Đang giao',
                                        'COMPLETE' => 'Hoàn thành',
                                        'CANCELLED' => 'Đã hủy'
                                    ];
                                    $status = $order['status'] ?? 'PENDING';
                                    ?>
                                    <span class="badge bg-<?= $statusClass[$status] ?? 'secondary' ?> fs-6">
                                        <?= $statusText[$status] ??  $status ?>
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th>Tổng tiền:</th>
                                <td class="text-danger fw-bold fs-5"><?= formatMoney($order['total_price']) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-shipping-fast me-2"></i>Thông tin giao hàng</h5>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless">
                            <tr>
                                <th width="40%">Người nhận:</th>
                                <td><?= e($order['receiver_name']) ?></td>
                            </tr>
                            <tr>
                                <th>Số điện thoại:</th>
                                <td><?= e($order['receiver_phone']) ?></td>
                            </tr>
                            <tr>
                                <th>Địa chỉ: </th>
                                <td><?= e($order['receiver_address']) ?></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-list me-2"></i>Chi tiết sản phẩm</h5>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Tên</th>
                            <th>Đơn giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($orderdetails as $detail): ?>
                            <tr>
                                <td>
                                    <img src="<?= url('public/image/product/' . ($detail['image'] ?? 'default.png')) ?>"
                                        alt="" style="width: 70px; height: 70px; object-fit: cover; border-radius: 5px;">
                                </td>
                                <td class="align-middle">
                                    <a href="<?= url('/product/' . $detail['product_id']) ?>">
                                        <?= e($detail['name']) ?>
                                    </a>
                                </td>
                                <td class="align-middle"><?= formatMoney($detail['price']) ?></td>
                                <td class="align-middle text-center"><?= $detail['quantity'] ?></td>
                                <td class="align-middle text-danger fw-bold">
                                    <?= formatMoney($detail['price'] * $detail['quantity']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="4" class="text-end fw-bold">Tổng cộng:</td>
                            <td class="text-danger fw-bold fs-5"><?= formatMoney($order['total_price']) ?></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="<?= url('/admin/order/update/' . $order['id']) ?>" class="btn btn-warning">
                <i class="fas fa-edit me-1"></i> Cập nhật trạng thái
            </a>
            <a href="<?= url('/admin/order') ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Quay lại
            </a>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>