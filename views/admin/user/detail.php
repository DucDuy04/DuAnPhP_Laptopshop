<?php


require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">User Detail</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/user') ?>">Users</a></li>
            <li class="breadcrumb-item active">Detail</li>
        </ol>

        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Thông tin User #<?= $user['id'] ?></h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center mb-3">
                                <?php if ($user['avatar']): ?>
                                    <img src="<?= url('public/image/avatar/' . $user['avatar']) ?>"
                                        class="img-fluid rounded-circle" style="max-width: 150px;">
                                <?php else: ?>
                                    <i class="fas fa-user-circle fa-5x text-muted"></i>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-8">
                                <table class="table table-borderless">
                                    <tr>
                                        <th width="30%">ID:</th>
                                        <td><?= $user['id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Email:</th>
                                        <td><?= e($user['email']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Họ tên:</th>
                                        <td><?= e($user['fullName']) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Điện thoại:</th>
                                        <td><?= e($user['phone'] ?? 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Địa chỉ:</th>
                                        <td><?= e($user['address'] ?? 'N/A') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Role:</th>
                                        <td>
                                            <span class="badge bg-<?= $user['role_name'] === 'ADMIN' ? 'danger' :  'info' ?>">
                                                <?= e($user['role_name'] ?? 'USER') ?>
                                            </span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="d-flex gap-2 mt-3">
                            <a href="<?= url('/admin/user/update/' . $user['id']) ?>" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Sửa
                            </a>
                            <a href="<?= url('/admin/user') ?>" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>