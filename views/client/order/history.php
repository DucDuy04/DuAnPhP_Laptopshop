<?php

/**
 * Order History View
 * Tương đương client/cart/order-history.jsp
 */
require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid py-5">
    <div class="container py-5">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4">
                    <i class="fas fa-history me-2 text-primary"></i>
                    Lịch sử đơn hàng
                </h1>
            </div>
        </div>

        <?php if (empty($orders)): ?>
            <!-- Empty State -->
            <div class="row">
                <div class="col-12">
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-bag fa-5x text-muted mb-4"></i>
                        <h4 class="text-muted">Chưa có đơn hàng nào</h4>
                        <p class="text-muted mb-4">Bạn chưa thực hiện đơn hàng nào.</p>
                        <a href="<?= url('/products') ?>" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-shopping-cart me-2"></i>Mua sắm ngay
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <!-- Orders List -->
            <div class="row">
                <div class="col-12">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" class="text-center" style="width: 100px;">Mã đơn</th>
                                    <th scope="col">Ngày đặt</th>
                                    <th scope="col">Người nhận</th>
                                    <th scope="col" class="text-end">Tổng tiền</th>
                                    <th scope="col" class="text-center">Trạng thái</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td class="text-center">
                                            <a href="<?= url('/order/' . $order['id']) ?>" title="Xem chi tiết đơn #<?= $order['id'] ?>">
                                                <strong>#<?= $order['id'] ?></strong>
                                            </a>
                                        </td>
                                        <td>
                                            <i class="far fa-calendar-alt me-2 text-muted"></i>
                                            <?= formatDate($order['created_at'] ?? date('Y-m-d H:i:s')) ?>
                                        </td>
                                        <td>
                                            <div>
                                                <strong><?= e($order['receiver_name'] ?? 'N/A') ?></strong>
                                            </div>
                                            <small class="text-muted">
                                                <i class="fas fa-phone me-1"></i>
                                                <?= e($order['receiver_phone'] ?? '') ?>
                                            </small>
                                        </td>
                                        <td class="text-end">
                                            <strong class="text-danger fs-5">
                                                <?= formatMoney($order['total_price']) ?>
                                            </strong>
                                        </td>
                                        <td class="text-center">
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
                                            $statusIcon = [
                                                'PENDING' => 'clock',
                                                'SHIPPING' => 'truck',
                                                'COMPLETE' => 'check-circle',
                                                'CANCELLED' => 'times-circle'
                                            ];
                                            $status = $order['status'] ?? 'PENDING';
                                            ?>
                                            <span class="badge bg-<?= $statusClass[$status] ?? 'secondary' ?> px-3 py-2">
                                                <i class="fas fa-<?= $statusIcon[$status] ?? 'question' ?> me-1"></i>
                                                <?= $statusText[$status] ?? $status ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($totalPages) && $totalPages > 1): ?>
                        <nav aria-label="Page navigation" class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($currentPage > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('/order-history?page=' . ($currentPage - 1)) ?>">
                                            <i class="fas fa-chevron-left"></i> Trước
                                        </a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                        <a class="page-link" href="<?= url('/order-history?page=' . $i) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($currentPage < $totalPages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="<?= url('/order-history?page=' . ($currentPage + 1)) ?>">
                                            Sau <i class="fas fa-chevron-right"></i>
                                        </a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Summary -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body">
                            <h6 class="text-muted mb-2">Tổng số đơn hàng</h6>
                            <h3 class="mb-0 text-primary"><?= count($orders) ?> đơn</h3>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 bg-light">
                        <div class="card-body text-end">
                            <a href="<?= url('/products') ?>" class="btn btn-outline-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>