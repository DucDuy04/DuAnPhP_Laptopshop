<?php



require_once __DIR__ . '/../layout/header.php';


$heroImages = $heroImages ?? [
    ['url' => 'public/image/product/hero-img-1.png', 'alt' => 'Laptop 1'],
    ['url' => 'public/image/product/1711078452562-dell-01.png', 'alt' => 'Laptop 2'],
    ['url' => 'public/image/product/hero-img-2.png', 'alt' => 'Laptop 3'],
    ['url' => 'public/image/product/1711078092373-asus-01.png', 'alt' => 'Laptop 4'],
    ['url' => 'public/image/product/1711079954090-apple-01.png', 'alt' => 'Laptop 5'],
];

$carouselId = 'heroCarousel';
?>

<!-- HERO (gộp carousel) -->
<section class="hero-header position-relative overflow-hidden">
    <div class="hero-bg position-absolute inset-0" aria-hidden="true" style="z-index:0; background: linear-gradient(120deg, rgba(10,132,255,0.08) 0%, rgba(112,207,255,0.06) 40%, rgba(255,255,255,0.02) 100%);"></div>

    <div class="container py-5" style="position:relative; z-index:2;">
        <div class="row align-items-center">
            <!-- Left text -->
            <div class="col-lg-6 text-lg-start text-center mb-4 mb-lg-0">
                <h5 class="text-warning fw-bold">100% Sản Phẩm Chính Hãng</h5>
                <h1 class="display-3 fw-bold" style="color:#0b50dc; line-height:0.95;">
                    Hàng cao cấp<br>Rẻ vô địch
                </h1>
                <p class="text-muted" style="max-width:560px;">
                    LaptopShop cung cấp laptop chính hãng, bảo hành đầy đủ và hỗ trợ kỹ thuật chuyên nghiệp.
                </p>
                <a href="<?= url('/products') ?>" class="btn btn-outline-light rounded-pill px-4 py-2" style="background: rgba(255,255,255,0.95); color:#0b50dc; font-weight:700;">Xem sản phẩm</a>
            </div>

            <!-- Right carousel -->
            <div class="col-lg-6">
                <div class="hero-card mx-auto shadow-sm rounded-3 overflow-hidden" style="max-width:620px; position:relative;">
                    <div id="<?= $carouselId ?>" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4500">
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            <?php foreach ($heroImages as $i => $img): ?>
                                <button type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-current="<?= $i === 0 ? 'true' : 'false' ?>" aria-label="Slide <?= $i + 1 ?>"></button>
                            <?php endforeach; ?>
                        </div>

                        <!-- Slides -->
                        <div class="carousel-inner">
                            <?php foreach ($heroImages as $i => $img):
                                $active = $i === 0 ? 'active' : '';
                                $imgUrl = htmlspecialchars($img['url'], ENT_QUOTES);
                                $imgAlt = htmlspecialchars($img['alt'] ?? "Slide " . ($i + 1), ENT_QUOTES);
                            ?>
                                <div class="carousel-item <?= $active ?>">
                                    <img src="<?= $imgUrl ?>" class="d-block w-100" alt="<?= $imgAlt ?>" style="height:380px; object-fit:cover;">
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="prev" aria-label="Previous">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#<?= $carouselId ?>" data-bs-slide="next" aria-label="Next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        </button>


                    </div>

                    <!-- Thumbnails row -->
                    <div class="d-flex justify-content-center gap-2 py-3 bg-white">
                        <?php foreach ($heroImages as $i => $img):
                            $thumbUrl = htmlspecialchars($img['url'], ENT_QUOTES);
                        ?>
                            <button type="button" class="btn p-0 border-0 hero-thumb" data-index="<?= $i ?>" aria-label="Go to slide <?= $i + 1 ?>" style="width:88px; height:56px; overflow:hidden; border-radius:8px; background:#fff;">
                                <img src="<?= $thumbUrl ?>" alt="" style="width:100%; height:100%; object-fit:cover; display:block; opacity:<?= $i === 0 ? '1' : '0.75' ?>;">
                            </button>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Products Section (unchanged) -->
<div class="container-fluid fruite py-5">
    <div class="container py-5">
        <div class="tab-class text-center">
            <div class="row g-4">
                <div class="col-lg-12">
                    <h1 class="mb-4">Sản phẩm nổi bật</h1>
                </div>
            </div>

            <div class="row g-4 justify-content-center">
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col-md-6 col-lg-4 col-xl-3">
                            <div class="rounded position-relative fruite-item border border-secondary">
                                <div class="fruite-img">
                                    <a href="<?= url('/product/' . $product['id']) ?>">
                                        <?php
                                        // Kiểm tra image có tồn tại không
                                        $imagePath = __DIR__ . '/../../../public/image/product/' . ($product['image'] ?? '');
                                        $imageUrl = (!empty($product['image']) && file_exists($imagePath))
                                            ? url('/public/image/product/' . $product['image'])
                                            : 'https://via.placeholder.com/300x200?text=No+Image';
                                        ?>
                                        <img src="<?= $imageUrl ?>"
                                            class="img-fluid w-100 rounded-top"
                                            alt="<?= e($product['name']) ?>"
                                            style="height:  200px; object-fit:  cover;">
                                    </a>
                                </div>
                                <div class="p-4">
                                    <h6>
                                        <a href="<?= url('/product/' . $product['id']) ?>" class="text-dark">
                                            <?= e($product['name']) ?>
                                        </a>
                                    </h6>
                                    <!-- Sử dụng short_desc thay vì shortDesc -->
                                    <p class="text-muted small mb-2"><?= e($product['short_desc'] ?? '') ?></p>
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
                <?php else: ?>
                    <div class="col-12">
                        <p class="text-muted">Chưa có sản phẩm nào. </p>
                    </div>
                <?php endif; ?>
            </div>

            <div class="row mt-5">
                <div class="col-12 text-center">
                    <a href="<?= url('/products') ?>" class="btn btn-primary px-5 py-3 rounded-pill">
                        Xem tất cả sản phẩm
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/feature.php'; ?>
<?php require_once __DIR__ . '/../layout/footer.php'; ?>

<!-- Scoped styles for hero (kept in-page to avoid touching global CSS) -->
<style>
    /* HERO: blue tone gradient background and styles (scoped) */
    .hero-bg {
        position: absolute;
        inset: 0;
        z-index: 0;
    }

    .hero-header .carousel-indicators [data-bs-target] {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background-color: rgba(255, 255, 255, 0.6);
    }

    .hero-header .carousel-indicators .active {
        background-color: #0b50dc;
    }

    .hero-card .carousel-control-prev,
    position::after absolute .hero-card .carousel-control-next {
        top: 55%;
        /* chỉnh giá trị này: 50% = chính giữa, 55% = hơi xuống dưới */
        transform: translateY(-50%);
        /* dịch để nút thực sự nằm giữa theo chiều dọc */
        width: 48px;
        height: 48px;
        border-radius: 50%;
        border: none;
        background: rgba(11, 80, 220, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0b50dc;
        z-index: 30;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
    }

    .hero-thumb {
        transition: transform .12s ease, opacity .12s ease;
        border: 0;
        background: transparent;
    }

    .hero-thumb img {
        transition: transform .18s ease;
    }

    .hero-thumb:hover img {
        transform: scale(1.04);
        opacity: 1;
    }
</style>


<script>
    (function() {
        window.addEventListener('load', function() {
            if (typeof bootstrap === 'undefined') return;

            var carouselEl = document.getElementById('<?= $carouselId ?>');
            if (!carouselEl) return;

            var carousel = bootstrap.Carousel.getOrCreateInstance(carouselEl, {
                interval: 4500,
                ride: 'carousel',
                pause: 'hover',
                touch: true
            });

            var thumbs = document.querySelectorAll('.hero-thumb');
            thumbs.forEach(function(btn) {
                btn.addEventListener('click', function() {
                    var idx = parseInt(btn.getAttribute('data-index'), 10);
                    if (isNaN(idx)) return;
                    carousel.to(idx);
                    thumbs.forEach(function(b) {
                        b.querySelector('img').style.opacity = '0.75';
                    });
                    btn.querySelector('img').style.opacity = '1';
                });
            });

            carouselEl.addEventListener('slid.bs.carousel', function(e) {
                var items = carouselEl.querySelectorAll('.carousel-item');
                var activeIdx = Array.prototype.indexOf.call(items, carouselEl.querySelector('.carousel-item.active'));
                thumbs.forEach(function(btn) {
                    var i = parseInt(btn.getAttribute('data-index'), 10);
                    btn.querySelector('img').style.opacity = (i === activeIdx ? '1' : '0.75');
                });
            });
        });
    })();
</script>