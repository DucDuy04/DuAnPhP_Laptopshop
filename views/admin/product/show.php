<?php


require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Manage Products</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Products</li>
        </ol>

        <div class="d-flex justify-content-between mb-3">
            <h4>Danh sách Products</h4>
            <a href="<?= url('/admin/product/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Thêm Product
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Hình</th>
                            <th>Tên</th>
                            <th>Giá</th>
                            <th>Hãng</th>
                            <th>Số lượng</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($products)): ?>
                            <?php foreach ($products as $product): ?>
                                <tr>
                                    <td><?= $product['id'] ?></td>
                                    <td>
                                        <img src="<?= url('public/image/product/' . ($product['image'] ??  'default.png')) ?>"
                                            style="width: 60px; height:60px; object-fit:cover; border-radius:5px;">
                                    </td>
                                    <td><?= e($product['name']) ?></td>
                                    <td><?= formatMoney($product['price']) ?></td>
                                    <td><?= e($product['factory'] ?? 'N/A') ?></td>
                                    <td><?= $product['quantity'] ?></td>
                                    <td>
                                        <a href="<?= url('/admin/product/' . $product['id']) ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= url('/admin/product/update/' . $product['id']) ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= url('/admin/product/delete/' . $product['id']) ?>" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center">Không có dữ liệu</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= url('/admin/product?page=' . $i) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>