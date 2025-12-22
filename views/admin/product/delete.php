<?php


require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Delete Product</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/product') ?>">Products</a></li>
            <li class="breadcrumb-item active">Delete</li>
        </ol>

        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0"><i class="fas fa-exclamation-triangle me-2"></i>Xác nhận xóa</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="<?= url('public/image/product/' . ($product['image'] ?? 'default.png')) ?>"
                                style="max-width: 150px; border-radius: 10px;">
                        </div>

                        <div class="alert alert-danger">
                            Bạn có chắc chắn muốn xóa sản phẩm: <br>
                            <strong><?= e($product['name']) ?></strong><br>
                            Giá: <strong><?= formatMoney($product['price']) ?></strong>
                            <br><br>
                            <strong>Lưu ý:</strong> Hành động này không thể hoàn tác!
                        </div>

                        <form method="POST" action="<?= url('/admin/product/delete') ?>">
                            <?= Csrf::field() ?>
                            <input type="hidden" name="id" value="<?= $product['id'] ?>">

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i> Xác nhận xóa
                                </button>
                                <a href="<?= url('/admin/product') ?>" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>