<?php

/**
 * Admin Delete Order View
 * Tương đương admin/order/deleteOrder.jsp
 */
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Delete Order</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/order') ?>">Orders</a></li>
            <li class="breadcrumb-item active">Delete</li>
        </ol>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa</h5>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-danger">
                            <p>Bạn có chắc chắn muốn xóa đơn hàng <strong>#<?= $id ?></strong>?</p>
                            <p class="mb-0"><strong>Lưu ý:</strong> Hành động này không thể hoàn tác và sẽ xóa toàn bộ chi tiết đơn hàng! </p>
                        </div>

                        <form method="POST" action="<?= url('/admin/order/delete/' . $id) ?>">
                            <?= Csrf::field() ?>
                            <input type="hidden" name="id" value="<?= $id ?>">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i> Xác nhận xóa
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