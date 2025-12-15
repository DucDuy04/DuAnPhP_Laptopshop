<?php

/**
 * Admin Order List View
 * Tương đương admin/order/show.jsp
 */
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Manage Orders</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Orders</li>
        </ol>

        <div class="card mb-4">
            <div class="card-header">
                <i class="fas fa-table me-1"></i>
                Danh sách đơn hàng
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Khách hàng</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($orders)): ?>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td>#<?= $order['id'] ?></td>
                                    <td>
                                        <strong><?= e($order['user_name'] ?? 'N/A') ?></strong><br>
                                        <small class="text-muted"><?= e($order['user_email'] ?? '') ?></small>
                                    </td>
                                    <td class="text-danger fw-bold"><?= formatMoney($order['total_price']) ?></td>
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
                                        <span class="badge bg-<?= $statusClass[$status] ??  'secondary' ?>">
                                            <?= $statusText[$status] ??  $status ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= url('/admin/order/' . $order['id']) ?>" class="btn btn-info btn-sm" title="Xem chi tiết">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= url('/admin/order/update/' . $order['id']) ?>" class="btn btn-warning btn-sm" title="Cập nhật">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?= url('/admin/order/delete/' . $order['id']) ?>" class="btn btn-danger btn-sm" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else:  ?>
                            <tr>
                                <td colspan="5" class="text-center">Không có đơn hàng nào</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                    <nav>
                        <ul class="pagination justify-content-center">
                            <?php if ($currentPage > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= url('/admin/order? page=' . ($currentPage - 1)) ?>">
                                        <i class="fas fa-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                    <a class="page-link" href="<?= url('/admin/order?page=' . $i) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($currentPage < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="<?= url('/admin/order?page=' . ($currentPage + 1)) ?>">
                                        <i class="fas fa-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>