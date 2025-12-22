<?php


require_once __DIR__ .  '/../layout/header.php';
require_once __DIR__ .  '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Product Detail</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/product') ?>">Products</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thông tin Product #<?= $product['id'] ?></h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 text-center mb-3">
                        <img src="<?= url('public/image/product/' . ($product['image'] ?? 'default.png')) ?>"
                            class="img-fluid rounded" style="max-width: 300px;">
                    </div>
                    <div class="col-md-8">
                        <table class="table table-borderless">
                            <tr>
                                <th width="25%">ID:</th>
                                <td><?= $product['id'] ?></td>
                            </tr>
                            <tr>
                                <th>Tên:</th>
                                <td><?= e($product['name']) ?></td>
                            </tr>
                            <tr>
                                <th>Giá:</th>
                                <td class="text-danger fw-bold"><?= formatMoney($product['price']) ?></td>
                            </tr>
                            <tr>
                                <th>Hãng: </th>
                                <td><?= e($product['factory'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th>Mục đích:</th>
                                <td><?= e($product['target'] ?? 'N/A') ?></td>
                            </tr>
                            <tr>
                                <th>Số lượng:</th>
                                <td><?= $product['quantity'] ?></td>
                            </tr>
                            <tr>
                                <th>Đã bán:</th>
                                <td><?= $product['sold'] ??  0 ?></td>
                            </tr>
                            <tr>
                                <th>Mô tả ngắn:</th>
                                <td><?= e($product['short_desc']) ?></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-12">
                        <h6>Mô tả chi tiết: </h6>
                        <p><?= nl2br(e($product['detail_desc'])) ?></p>
                    </div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <a href="<?= url('/admin/product/update/' . $product['id']) ?>" class="btn btn-warning">
                        <i class="fas fa-edit me-1"></i> Sửa
                    </a>
                    <a href="<?= url('/admin/product') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>