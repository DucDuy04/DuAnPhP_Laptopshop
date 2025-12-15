<?php

/**
 * Admin User List View
 * Tương đương admin/user/show.jsp
 */
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Manage Users</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item active">Users</li>
        </ol>

        <div class="d-flex justify-content-between mb-3">
            <h4>Danh sách Users</h4>
            <a href="<?= url('/admin/user/create') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Thêm User
            </a>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Email</th>
                            <th>Họ tên</th>
                            <th>Role</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (! empty($users)): ?>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td><?= $user['id'] ?></td>
                                    <td><?= e($user['email']) ?></td>
                                    <td><?= e($user['fullName']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $user['role_name'] === 'ADMIN' ? 'danger' : 'info' ?>">
                                            <?= e($user['role_name'] ??  'USER') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="<?= url('/admin/user/' . $user['id']) ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye">Xem</i>
                                        </a>
                                        <a href="<?= url('/admin/user/update/' . $user['id']) ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit">Sửa</i>
                                        </a>
                                        <a href="<?= url('/admin/user/delete/' . $user['id']) ?>" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash">Xóa</i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">Không có dữ liệu</td>
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
                                    <a class="page-link" href="<?= url('/admin/user?page=' . $i) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ .  '/../layout/footer.php'; ?>