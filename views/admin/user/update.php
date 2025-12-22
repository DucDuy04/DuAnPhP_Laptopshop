<?php


require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Update User</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/user') ?>">Users</a></li>
            <li class="breadcrumb-item active">Update</li>
        </ol>

        <div class="row">
            <div class="col-md-8 col-12 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Cập nhật User #<?= $user['id'] ?></h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="<?= url('/admin/user/update/' . $user['id']) ?>" enctype="multipart/form-data">
                            <?= Csrf::field() ?>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email *</label>
                                    <input type="email" name="email"
                                        class="form-control <?= Session::hasError('email') ? 'is-invalid' : '' ?>"
                                        value="<?= e(Session::getOldInput('email', $user['email'])) ?>">
                                    <?php if ($error = Session::getError('email')): ?>
                                        <div class="invalid-feedback"><?= e($error) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Password (để trống nếu không đổi)</label>
                                    <input type="password" name="password"
                                        class="form-control <?= Session::hasError('password') ? 'is-invalid' : '' ?>">
                                    <?php if ($error = Session::getError('password')): ?>
                                        <div class="invalid-feedback"><?= e($error) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Họ tên *</label>
                                    <input type="text" name="fullName"
                                        class="form-control <?= Session::hasError('fullName') ? 'is-invalid' : '' ?>"
                                        value="<?= e(Session::getOldInput('fullName', $user['fullName'])) ?>">
                                    <?php if ($error = Session::getError('fullName')): ?>
                                        <div class="invalid-feedback"><?= e($error) ?></div>
                                    <?php endif; ?>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="text" name="phone" class="form-control"
                                        value="<?= e(Session::getOldInput('phone', $user['phone'])) ?>">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Địa chỉ</label>
                                <textarea name="address" class="form-control" rows="2"><?= e(Session::getOldInput('address', $user['address'])) ?></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Role *</label>
                                    <select name="role_id" class="form-select">
                                        <?php foreach ($roles as $role): ?>
                                            <option value="<?= $role['id'] ?>"
                                                <?= $user['role_id'] == $role['id'] ? 'selected' : '' ?>>
                                                <?= e($role['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Avatar</label>
                                    <input type="file" name="avatar" class="form-control" accept="image/*"
                                        onchange="previewImage(this)">
                                    <?php if ($user['avatar']): ?>
                                        <img id="avatarPreview" src="<?= url('public/image/avatar/' . $user['avatar']) ?>"
                                            alt="Preview" style="max-width:150px; margin-top:10px;">
                                    <?php else: ?>
                                        <img id="avatarPreview" src="#" alt="Preview"
                                            style="display:none; max-width:150px; margin-top:10px;">
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Cập nhật
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

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatarPreview').src = e.target.result;
                document.getElementById('avatarPreview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php
Session::clearValidation();
require_once __DIR__ .  '/../layout/footer.php';
?>