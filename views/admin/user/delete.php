<?php
/**
 * Admin Delete User View
 * Tương đương admin/user/delete.jsp
 */
require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Delete User</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/user') ?>">Users</a></li>
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
                            Bạn có chắc chắn muốn xóa user <strong><?= e($user['fullName']) ?></strong> (<?= e($user['email']) ?>)?
                            <br><br>
                            <strong>Lưu ý:</strong> Hành động này không thể hoàn tác! 
                        </div>
                        
                        <form method="POST" action="<?= url('/admin/user/delete/' . $user['id']) ?>">
                            <?= Csrf::field() ?>
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i> Xác nhận xóa
                                </button>
                                <a href="<?= url('/admin/user') ?>" class="btn btn-secondary">Hủy</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>