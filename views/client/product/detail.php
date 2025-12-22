<?php


require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid py-5 mt-5">
    <div class="container py-5">
        <div class="row g-4 mb-5">
            <!-- Breadcrumb -->
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= url('/') ?>">Trang chủ</a></li>
                        <li class="breadcrumb-item"><a href="<?= url('/products') ?>">Sản phẩm</a></li>
                        <li class="breadcrumb-item active"><?= e($product['name']) ?></li>
                    </ol>
                </nav>
            </div>

            <!-- Product Image -->
            <div class="col-lg-6">
                <div class="border rounded p-3">
                    <?php
                    $imagePath = __DIR__ . '/../../../public/image/product/' . ($product['image'] ?? '');
                    $imageUrl = (!empty($product['image']) && file_exists($imagePath))
                        ? url('/public/image/product/' . $product['image'])
                        : 'https://via.placeholder.com/500x400?text=No+Image';
                    ?>
                    <img src="<?= $imageUrl ?>"
                        class="img-fluid rounded" alt="<?= e($product['name']) ?>">
                </div>
            </div>

            <!-- Product Info -->
            <div class="col-lg-6">
                <h2 class="fw-bold mb-3"><?= e($product['name']) ?></h2>

                <!-- Sử dụng short_desc -->
                <p class="mb-3"><?= e($product['short_desc'] ?? '') ?></p>

                <h3 class="fw-bold text-primary mb-4"><?= formatMoney($product['price']) ?></h3>

                <div class="mb-3">
                    <span class="badge bg-secondary me-2">Hãng: <?= e($product['factory'] ?? 'N/A') ?></span>
                    <span class="badge bg-info">Còn: <?= $product['quantity'] ?> sản phẩm</span>
                    <span class="badge bg-success">Đã bán: <?= $product['sold'] ?? 0 ?></span>
                </div>

                <?php if ($product['quantity'] > 0): ?>
                    <form action="<?= url('/add-product-to-cart/' . $product['id']) ?>" method="POST" class="mb-4">
                        <?= Csrf::field() ?>
                        <div class="input-group mb-3" style="width: 200px;">
                            <span class="input-group-text">Số lượng</span>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" max="<?= $product['quantity'] ?>">
                        </div>
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fa fa-shopping-bag me-2"></i>Thêm vào giỏ hàng
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle me-2"></i>Sản phẩm tạm hết hàng
                    </div>
                <?php endif; ?>
            </div>

            <!-- Product Description -->
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Mô tả chi tiết</h5>
                    </div>
                    <div class="card-body">
                        <!-- Sử dụng detail_desc -->
                        <?= nl2br(e($product['detail_desc'] ?? '')) ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Products -->
        <?php if (!empty($relatedProducts)): ?>
            <div class="row">
                <div class="col-12">
                    <h3 class="mb-4">Sản phẩm liên quan</h3>
                </div>
                <?php foreach ($relatedProducts as $related): ?>
                    <div class="col-md-6 col-lg-3">
                        <div class="rounded position-relative fruite-item border border-secondary">
                            <div class="fruite-img">
                                <a href="<?= url('/product/' . $related['id']) ?>">
                                    <?php
                                    $relatedImagePath = __DIR__ . '/../../../public/image/product/' . ($related['image'] ?? '');
                                    $relatedImageUrl = (!empty($related['image']) && file_exists($relatedImagePath))
                                        ? url('/public/image/product/' . $related['image'])
                                        : 'https://via.placeholder.com/300x150?text=No+Image';
                                    ?>
                                    <img src="<?= $relatedImageUrl ?>"
                                        class="img-fluid w-100 rounded-top" style="height: 150px; object-fit: cover;">
                                </a>
                            </div>
                            <div class="p-3">
                                <h6><a href="<?= url('/product/' . $related['id']) ?>" class="text-dark"><?= e($related['name']) ?></a></h6>
                                <p class="text-dark fw-bold mb-0"><?= formatMoney($related['price']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/../layout/feature.php'; ?>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>