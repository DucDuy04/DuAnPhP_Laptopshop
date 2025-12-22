<?php


require_once __DIR__ . '/../layout/header.php';
require_once __DIR__ . '/../layout/sidebar.php';
?>

<main>
    <div class="container-fluid px-4">
        <h1 class="mt-4">Update Product</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item"><a href="<?= url('/admin') ?>">Dashboard</a></li>
            <li class="breadcrumb-item"><a href="<?= url('/admin/product') ?>">Products</a></li>
            <li class="breadcrumb-item active">Update</li>
        </ol>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Cập nhật Product #<?= $product['id'] ?></h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= url('/admin/product/update/' .  $product['id']) ?>" enctype="multipart/form-data">
                    <?= Csrf::field() ?>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên sản phẩm *</label>
                            <input type="text" name="name"
                                class="form-control <?= Session::hasError('name') ? 'is-invalid' : '' ?>"
                                value="<?= e(Session::getOldInput('name', $product['name'])) ?>">
                            <?php if ($error = Session::getError('name')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giá *</label>
                            <input type="number" name="price"
                                class="form-control <?= Session::hasError('price') ? 'is-invalid' : '' ?>"
                                value="<?= e(Session::getOldInput('price', $product['price'])) ?>">
                            <?php if ($error = Session::getError('price')): ?>
                                <div class="invalid-feedback"><?= e($error) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả ngắn *</label>
                        <input type="text" name="shortDesc"
                            class="form-control <?= Session::hasError('shortDesc') ? 'is-invalid' : '' ?>"
                            value="<?= e(Session::getOldInput('shortDesc', $product['short_desc'])) ?>">
                        <?php if ($error = Session::getError('shortDesc')): ?>
                            <div class="invalid-feedback"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mô tả chi tiết *</label>
                        <textarea name="detailDesc" rows="5"
                            class="form-control <?= Session::hasError('detailDesc') ? 'is-invalid' : '' ?>"><?= e(Session::getOldInput('detailDesc', $product['detail_desc'])) ?></textarea>
                        <?php if ($error = Session::getError('detailDesc')): ?>
                            <div class="invalid-feedback"><?= e($error) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Số lượng *</label>
                            <input type="number" name="quantity"
                                class="form-control"
                                value="<?= e(Session::getOldInput('quantity', $product['quantity'])) ?>" min="1">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Hãng sản xuất</label>
                            <select name="factory" class="form-select">
                                <option value="">-- Chọn hãng --</option>
                                <?php
                                $factories = ['APPLE', 'ASUS', 'DELL', 'HP', 'LENOVO', 'ACER', 'MSI', 'LG'];
                                foreach ($factories as $f):
                                ?>
                                    <option value="<?= $f ?>" <?= $product['factory'] == $f ? 'selected' : '' ?>><?= $f ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label class="form-label">Mục đích sử dụng</label>
                            <select name="target" class="form-select">
                                <option value="">-- Chọn mục đích --</option>
                                <?php
                                $targets = ['gaming' => 'Gaming', 'van-phong' => 'Văn phòng', 'thiet-ke-do-hoa' => 'Thiết kế đồ họa', 'hoc-tap' => 'Học tập', 'mong-nhe' => 'Mỏng nhẹ'];
                                foreach ($targets as $key => $val):
                                ?>
                                    <option value="<?= $key ?>" <?= $product['target'] == $key ? 'selected' : '' ?>><?= $val ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hình ảnh (để trống nếu không đổi)</label>
                        <input type="file" name="image" class="form-control" accept="image/*" onchange="previewImage(this)">
                        <img id="imagePreview" src="<?= url('public/image/product/' . ($product['image'] ?? 'default.png')) ?>"
                            alt="Preview" style="max-width:200px; margin-top:10px;">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Cập nhật
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
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

<?php
Session::clearValidation();
require_once __DIR__ . '/../layout/footer.php';
?>