<?php


require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Create Product</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/product') ?>">Products</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Tạo Product mới</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= url('/admin/product/create') ?>" enctype="multipart/form-data">
                    <?= Csrf::field() ?> <!-- CSRF token để bảo vệ form -->

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên sản phẩm *</label>
                            <input type="text" name="name"
                                class="form-control <?= Session::hasError('name') ? 'is-invalid' : '' ?>"
                                value="<?= e(Session::getOldInput('name')) ?>">
                            <?php if ($error = Session::getError('name')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá *</label>
                            <input type="number" name="price"
                                class="form-control <?= Session::hasError('price') ? 'is-invalid' : '' ?>"
                                value="<?= e(Session::getOldInput('price')) ?>">
                            <?php if ($error = Session::getError('price')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn *</label>
                        <input type="text" name="shortDesc"
                            class="form-control <?= Session::hasError('shortDesc') ? 'is-invalid' : '' ?>"
                            value="<?= e(Session::getOldInput('shortDesc')) ?>">
                        <?php if ($error = Session::getError('shortDesc')): ?>
                            <div class="invalid-feedback"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết *</label>
                        <textarea name="detailDesc" rows="5"
                            class="form-control <?= Session::hasError('detailDesc') ? 'is-invalid' : '' ?>"><?= e(Session::getOldInput('detailDesc')) ?></textarea>
                        <?php if ($error = Session::getError('detailDesc')): ?>
                            <div class="invalid-feedback"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Số lượng *</label>
                            <input type="number" name="quantity"
                                class="form-control <?= Session::hasError('quantity') ? 'is-invalid' : '' ?>"
                                value="<?= e(Session::getOldInput('quantity', 1)) ?>" min="1">
                            <?php if ($error = Session::getError('quantity')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hãng sản xuất</label>
                            <select name="factory" class="form-select">
                                <option value="">-- Chọn hãng --</option>
                                <option value="APPLE">Apple</option>
                                <option value="ASUS">Asus</option>
                                <option value="DELL">Dell</option>
                                <option value="HP">HP</option>
                                <option value="LENOVO">Lenovo</option>
                                <option value="ACER">Acer</option>
                                <option value="MSI">MSI</option>
                                <option value="LG">LG</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mục đích sử dụng</label>
                            <select name="target" class="form-select">
                                <option value="">-- Chọn mục đích --</option>
                                <option value="gaming">Gaming</option>
                                <option value="van-phong">Văn phòng</option>
                                <option value="thiet-ke-do-hoa">Thiết kế đồ họa</option>
                                <option value="hoc-tap">Học tập</option>
                                <option value="mong-nhe">Mỏng nhẹ</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh *</label>
                        <input type="file" name="image"
                            class="form-control <?= Session::hasError('image') ? 'is-invalid' : '' ?>"
                            accept="image/*" onchange="previewImage(this)">
                        <?php if ($error = Session::getError('image')): ?>
                            <div class="invalid-feedback"><?= e($error) ?></div>
                        <?php endif; ?>
                        <img id="imagePreview" src="#" alt="Preview"
                            style="display:none; max-width:200px; margin-top:10px;">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Tạo Product
                        </button>
                        <a href="<?= url('/admin/product') ?>" class="btn btn-secondary">Hủy</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    function previewImage(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('imagePreview').src = e.target.result;
                document.getElementById('imagePreview').style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php
Session::clearValidation();
require_once __DIR__ . '/../layout/footer.php';
?>