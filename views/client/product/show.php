<?php


require_once __DIR__ . '/../layout/header.php';
?>

<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <h1 class="mb-4">Sản phẩm</h1>
        <div class="row g-4">
            <!-- Sidebar Filter -->

            <div class="col-lg-3">
                <div class="row g-4">

                    <!-- Search -->
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <h4>Tìm kiếm</h4>
                            <form action="<?= url('/products') ?>" method="GET">
                                <div class="input-group">
                                    <input type="text" name="keyword" class="form-control"
                                        placeholder="Tìm kiếm..."
                                        value="<?= e($filters['keyword'] ?? '') ?>">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Factory Filter -->
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <h4>Hãng sản xuất</h4>
                            <div class="row">
                                <?php
                                $factoryList = ['APPLE', 'ASUS', 'DELL', 'HP', 'LENOVO', 'ACER', 'MSI', 'LG'];
                                foreach ($factoryList as $factory):
                                    $isActive = ($filters['factory'] ?? '') == $factory;
                                ?>
                                    <div class="col-6 mb-2">
                                        <a href="<?= url('/products?factory=' . $factory) ?>"
                                            class="text-decoration-none d-flex align-items-center <?= $isActive ? 'fw-bold text-primary' : 'text-dark' ?>">
                                            <i class="<?= $isActive ? 'fas fa-check-square text-primary' : 'far fa-square text-muted' ?> me-2"></i>
                                            <?= $factory ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Sort Filter -->
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <h4>Sắp xếp</h4>
                            <div class="row">
                                <?php
                                $sortOptions = [
                                    'price_asc' => 'Giá tăng dần',
                                    'price_desc' => 'Giá giảm dần',
                                    'name_asc' => 'Tên A-Z',
                                    'bestseller' => 'Bán chạy'
                                ];
                                foreach ($sortOptions as $value => $label):
                                    $isActive = ($filters['sort'] ?? '') == $value;
                                ?>
                                    <div class="col-6 mb-2">
                                        <a href="<?= url('/products?sort=' . $value) ?>"
                                            class="text-decoration-none d-flex align-items-center <?= $isActive ? 'fw-bold text-primary' : 'text-dark' ?>">
                                            <i class="<?= $isActive ? 'fas fa-check-square text-primary' : 'far fa-square text-muted' ?> me-2"></i>
                                            <?= $label ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <h4>Mức giá</h4>
                            <div class="row">
                                <?php
                                $priceOptions = [
                                    ['max' => 10000000, 'label' => 'Dưới 10 triệu'],
                                    ['min' => 10000000, 'max' => 20000000, 'label' => '10 - 20 triệu'],
                                    ['min' => 20000000, 'max' => 30000000, 'label' => '20 - 30 triệu'],
                                    ['min' => 30000000, 'label' => 'Trên 30 triệu']
                                ];
                                foreach ($priceOptions as $price):
                                    $params = [];
                                    if (isset($price['min'])) $params[] = 'min_price=' . $price['min'];
                                    if (isset($price['max'])) $params[] = 'max_price=' . $price['max'];
                                    $url = '/products?' . implode('&', $params);

                                    // Check if active
                                    $isActive = false;
                                    if (isset($price['min']) && isset($price['max'])) {
                                        $isActive = ($filters['min_price'] ?? '') == $price['min'] && ($filters['max_price'] ?? '') == $price['max'];
                                    } elseif (isset($price['min'])) {
                                        $isActive = ($filters['min_price'] ?? '') == $price['min'] && empty($filters['max_price']);
                                    } elseif (isset($price['max'])) {
                                        $isActive = ($filters['max_price'] ?? '') == $price['max'] && empty($filters['min_price']);
                                    }
                                ?>
                                    <div class="col-6 mb-2">
                                        <a href="<?= url($url) ?>"
                                            class="text-decoration-none d-flex align-items-center <?= $isActive ? 'fw-bold text-primary' : 'text-dark' ?>">
                                            <i class="<?= $isActive ? 'fas fa-check-square text-primary' : 'far fa-square text-muted' ?> me-2"></i>
                                            <?= $price['label'] ?>
                                        </a>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Clear Filter -->
                    <div class="col-lg-12">
                        <a href="<?= url('/products') ?>" class="btn btn-outline-danger w-100">
                            <i class="fa fa-times me-2"></i>Xóa bộ lọc
                        </a>
                    </div>

                </div>
            </div>

            <!-- Products Grid -->
            <div class="col-lg-9">
                <div class="row g-4 justify-content-center">
                    <!-- Sort -->
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <p class="mb-0">Hiển thị <?= count($products) ?> / <?= $total ?> sản phẩm</p>

                        </div>
                    </div>

                    <!-- Product Items -->
                    <?php if (! empty($products)): ?>
                        <?php foreach ($products as $product): ?>
                            <div class="col-md-6 col-lg-4">
                                <div class="rounded position-relative fruite-item border border-secondary">
                                    <div class="fruite-img">
                                        <a href="<?= url('/product/' . $product['id']) ?>">
                                            <img src="<?= url('/public/image/product/' . ($product['image'] ?? 'default.png')) ?>"
                                                class="img-fluid w-100 rounded-top"
                                                alt="<?= e($product['name']) ?>"
                                                style="height: 200px; object-fit: cover;">
                                        </a>
                                    </div>
                                    <div class="p-4">
                                        <h6>
                                            <a href="<?= url('/product/' . $product['id']) ?>" class="text-dark">
                                                <?= e($product['name']) ?>
                                            </a>
                                        </h6>
                                        <p class="text-muted small mb-2"><?= e($product['shortDesc'] ?? '') ?></p>
                                        <p class="text-dark fs-5 fw-bold mb-2">
                                            <?= formatMoney($product['price']) ?>
                                        </p>
                                        <form action="<?= url('/add-product-to-cart/' . $product['id']) ?>" method="POST">
                                            <?= Csrf::field() ?>
                                            <button type="submit" class="btn border border-secondary rounded-pill px-3 text-primary">
                                                <i class="fa fa-shopping-bag me-2 text-primary"></i> Thêm vào giỏ
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else:  ?>
                        <div class="col-12 text-center">
                            <p class="text-muted">Không tìm thấy sản phẩm nào.</p>
                        </div>
                    <?php endif; ?>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="col-12">
                            <nav aria-label="Page navigation">
                                <ul class="pagination justify-content-center">
                                    <?php if ($currentPage > 1): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= url('/products?page=' . ($currentPage - 1)) ?>">Trước</a>
                                        </li>
                                    <?php endif; ?>

                                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                                            <a class="page-link" href="<?= url('/products?page=' . $i) ?>"><?= $i ?></a>
                                        </li>
                                    <?php endfor; ?>

                                    <?php if ($currentPage < $totalPages): ?>
                                        <li class="page-item">
                                            <a class="page-link" href="<?= url('/products?page=' . ($currentPage + 1)) ?>">Sau</a>
                                        </li>
                                    <?php endif; ?>
                                </ul>
                            </nav>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../layout/feature.php'; ?>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>