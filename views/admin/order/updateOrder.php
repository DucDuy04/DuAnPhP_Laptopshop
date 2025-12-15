<?php
/**
 * Admin Update Order View
 * Tương đương admin/order/updateorder.jsp
 */
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Update Order</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/order') ?>">Orders</a></li>
            <li class="breadcrumb-item active">Update #<?= $order['id'] ?></li>
        </ol>
        
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-edit me-2"></i>Cập nhật trạng thái đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-4">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Mã đơn hàng: </th>
                                    <td><strong>#<?= $order['id'] ?></strong></td>
                                </tr>
                                <tr>
                                    <th>Tổng tiền:</th>
                                    <td class="text-danger fw-bold"><?= formatMoney($order['total_price']) ?></td>
                                </tr>
                                <tr>
                                    <th>Người nhận:</th>
                                    <td><?= e($order['receiver_name']) ?></td>
                                </tr>
                                <tr>
                                    <th>Điện thoại:</th>
                                    <td><?= e($order['receiver_phone']) ?></td>
                                </tr>
                            </table>
                        </div>
                        
                        <form method="POST" action="<?= url('/admin/order/update/' . $order['id']) ?>">
                            <?= Csrf::field() ?>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">Trạng thái đơn hàng</label>
                                <select name="status" class="form-select form-select-lg">
                                    <?php foreach ($statuses as $value => $label): ?>
                                    <option value="<?= $value ?>" <?= $order['status'] == $value ? 'selected' : '' ?>>
                                        <?= $label ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Cập nhật
                                </button>
                                <a href="<?= url('/admin/order') ?>" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>