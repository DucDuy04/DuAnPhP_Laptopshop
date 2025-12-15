<?php
/**
 * Admin Dashboard View
 * Tương đương admin/dashboard/show.jsp
 */
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Dashboard</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard</li>
        </ol>
        
        <!-- Stats Cards -->
        <div class="row">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small">Tổng Users</div>
                                <div class="fs-4 fw-bold"><?= $stats['totalUsers'] ??  0 ?></div>
                            </div>
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="<?= url('/admin/user') ?>">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small">Tổng Products</div>
                                <div class="fs-4 fw-bold"><?= $stats['totalProducts'] ?? 0 ?></div>
                            </div>
                            <i class="fas fa-laptop fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="<?= url('/admin/product') ?>">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small">Tổng Orders</div>
                                <div class="fs-4 fw-bold"><?= $stats['totalOrders'] ?? 0 ?></div>
                            </div>
                            <i class="fas fa-shopping-cart fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="<?= url('/admin/order') ?>">Xem chi tiết</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="card bg-danger text-white mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="small">Doanh thu</div>
                                <div class="fs-4 fw-bold"><?= formatMoney($stats['totalRevenue'] ?? 0) ?></div>
                            </div>
                            <i class="fas fa-dollar-sign fa-2x"></i>
                        </div>
                    </div>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="#">Xem báo cáo</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Order Stats -->
        <div class="row">
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-chart-pie me-1"></i>
                        Trạng thái đơn hàng
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <tr>
                                <td><span class="badge bg-warning">Chờ xử lý</span></td>
                                <td><?= $stats['pendingOrders'] ?? 0 ?> đơn</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-info">Đang giao</span></td>
                                <td><?= $stats['shippingOrders'] ?? 0 ?> đơn</td>
                            </tr>
                            <tr>
                                <td><span class="badge bg-success">Hoàn thành</span></td>
                                <td><?= $stats['completedOrders'] ?? 0 ?> đơn</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-6">
                <div class="card mb-4">
                    <div class="card-header">
                        <i class="fas fa-cogs me-1"></i>
                        Thao tác nhanh
                    </div>
                    <div class="card-body">
                        <a href="<?= url('/admin/user/create') ?>" class="btn btn-primary mb-2 me-2">
                            <i class="fas fa-user-plus me-1"></i> Thêm User
                        </a>
                        <a href="<?= url('/admin/product/create') ?>" class="btn btn-success mb-2 me-2">
                            <i class="fas fa-plus me-1"></i> Thêm Product
                        </a>
                        <a href="<?= url('/admin/order') ?>" class="btn btn-info mb-2">
                            <i class="fas fa-list me-1"></i> Xem Orders
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>