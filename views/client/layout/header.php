<?php

/**
 * Client Header Layout - full-bleed header
 * Thay thế file cũ bằng file này để header chiếm trọn chiều rộng.
 * Không thay đổi logic backend (Auth/CSRF...) — chỉ thay giao diện.
 */
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= e($pageTitle ?? 'LaptopShop') ?></title>

    <!-- Fonts / Icons / Bootstrap -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Inline CSS (you can move to public/css/style.css) -->
    <style>
        :root {
            --primary-color: #7ac943;
            --badge-color: #ffb000;
            --header-bg: #fff;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Open Sans", sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            transition: padding-top .16s ease;
        }

        /* ========== FULL-BLEED HEADER ========== */
        header#siteHeader {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
            background: transparent;
            /* background applied by inner bars so header container itself is transparent */
        }

        /* topbar full width background */
        .site-topbar {
            width: 100%;
            background: linear-gradient(90deg, #6792e8ff 0%, #0646c5ff 100%);
        }

        .site-topbar .container {
            padding-top: 6px;
            padding-bottom: 6px;
        }

        /* main nav full width background + subtle shadow gradient under */
        .site-nav-wrap {
            width: 100%;
            background: var(--header-bg);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
            position: relative;
            /* pseudo gradient shadow under nav for the colored sweep effect */
        }

        .site-nav-wrap::after {
            content: "";
            position: absolute;
            left: 0;
            right: 0;
            bottom: -10px;
            height: 18px;
            background: linear-gradient(to right, rgba(219, 160, 203, 0.12), rgba(219, 160, 203, 0.02));
            filter: blur(6px);
            pointer-events: none;
        }

        /* content inside nav remains constrained */
        .navbar {
            padding: 18px 0;
            background: transparent;
            /* background provided by .site-nav-wrap */
        }

        .navbar-brand h1 {
            font-family: "Raleway", sans-serif;
            font-weight: 700;
            font-size: 40px;
            color: #0646c5ff;
            margin: 0;
        }

        /* centered nav links (container keeps content centered) */
        .navbar-nav {
            gap: 28px;
            align-items: center;
        }

        .navbar .nav-link {
            color: #6b6b6b;
            font-weight: 600;
            font-size: 16px;
            padding: .4rem .8rem;
        }

        .navbar .nav-link.active {
            color: #0b50dcff;
        }

        /* right icons */
        .nav-icons {
            display: flex;
            gap: 18px;
            align-items: center;
        }

        .nav-icons .icon {
            color: #0b50dcff;
            font-size: 26px;
        }

        .cart-badge {
            position: absolute;
            top: -6px;
            left: 16px;
            background: var(--badge-color);
            color: #fff;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 12px;
            box-shadow: 0 0 0 3px rgba(122, 201, 67, 0.06);
        }

        /* responsive adjustments */
        @media (max-width:991.98px) {
            .navbar-brand h1 {
                font-size: 32px;
            }

            .navbar-nav {
                gap: 14px;
            }

            .nav-icons .icon {
                font-size: 22px;
            }
        }

        .site-topbar .top-links a {
            text-decoration: none !important;
            /* bỏ underline */
            border: none !important;
            padding: 0;
            color: inherit;
            /* giữ màu hiện tại */
        }
    </style>

    <?= function_exists('Csrf::meta') ? Csrf::meta() : '' ?>
</head>

<body>
    <!-- Full-bleed header -->
    <header id="siteHeader">

        <!-- Topbar (full width) -->
        <div class="site-topbar">
            <div class="container d-none d-lg-flex justify-content-between text-white">
                <div class="top-info">
                    <small class="me-3"><i class="fas fa-map-marker-alt me-2"></i>Thành phố Huế, Việt Nam</small>
                    <small><i class="fas fa-envelope me-2"></i>laptopshop@gmail.com</small>
                </div>
                <div class="top-links">
                    <?php if (isLoggedIn()): ?>
                        <small>Xin chào, <strong><?= e(Auth::name()) ?></strong></small>
                    <?php else: ?>
                        <a href="<?= url('/login') ?>" class="text-white me-3 border-0">Đăng nhập</a>
                        <a href="<?= url('/register') ?>" class="text-white">Đăng ký</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Nav wrap (full width background + shadow) -->
        <div class="site-nav-wrap">
            <div class="container">
                <nav class="navbar navbar-expand-xl align-items-center">
                    <!-- Brand (left) -->
                    <a class="navbar-brand" href="<?= url('/') ?>">
                        <h1>Laptopshop</h1>
                    </a>

                    <!-- Toggler for small screens -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#siteNavCollapse" aria-controls="siteNavCollapse" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="fa fa-bars" style="color: var(--primary-color)"></span>
                    </button>

                    <!-- center links - keep inside container so they are centered on page -->
                    <div class="collapse navbar-collapse justify-content-center" id="siteNavCollapse">
                        <ul class="navbar-nav">
                            <li class="nav-item"><a class="nav-link <?= ($_SERVER['REQUEST_URI'] === '/' ? 'active' : '') ?>" href="<?= url('/') ?>">Trang chủ</a></li>
                            <li class="nav-item"><a class="nav-link <?= strpos($_SERVER['REQUEST_URI'], '/products') !== false ? 'active' : '' ?>" href="<?= url('/products') ?>">Sản phẩm</a></li>
                        </ul>
                    </div>

                    <!-- right icons -->
                    <div class="nav-icons ms-auto">
                        <div style="position:relative;">
                            <a href="<?= url('/cart') ?>" aria-label="Giỏ hàng" class="text-decoration-none">
                                <i class="fa fa-shopping-bag icon" aria-hidden="true"></i>
                            </a>
                            <?php
                            $cartCount = 0;
                            if (function_exists('Auth::getCartCount')) $cartCount = Auth::getCartCount();
                            else $cartCount = intval($_SESSION['cart_sum'] ?? $_SESSION['cart_count'] ?? 0);
                            ?>
                            <?php if ($cartCount > 0): ?>
                                <span class="cart-badge" aria-hidden="true"><?= $cartCount ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="dropdown">
                            <a class="dropdown-toggle text-decoration-none" href="#" id="userMenu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fa fa-user icon" aria-hidden="true"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                <?php if (isLoggedIn()): ?>
                                    <li class="px-3 py-2 border-bottom">
                                        <div class="d-flex align-items-center">
                                            <img src="<?= (!empty($_SESSION['user_avatar']) && file_exists(__DIR__ . '/../../uploads/avatars/' . $_SESSION['user_avatar'])) ? url('/uploads/avatars/' . $_SESSION['user_avatar']) : asset('images/default-avatar.png') ?>" alt="avatar" style="width:44px;height:44px;border-radius:50%;object-fit:cover;margin-right:10px;">
                                            <div><strong><?= e(Auth::name()) ?></strong>
                                                <div class="small text-muted"><?= e(Auth::email()) ?></div>
                                            </div>
                                        </div>
                                    </li>
                                    <?php if (isAdmin()): ?><li><a class="dropdown-item" href="<?= url('/admin') ?>">Quản trị</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li><?php endif; ?>
                                    <li><a class="dropdown-item" href="<?= url('/order-history') ?>">Lịch sử mua hàng</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <form action="<?= url('/logout') ?>" method="POST" class="px-0">
                                            <?= Csrf::field() ?>
                                            <button type="submit" class="dropdown-item text-danger"><i class="fas fa-sign-out-alt me-2"></i>Đăng xuất</button>
                                        </form>
                                    </li>
                                <?php else: ?>
                                    <li><a class="dropdown-item" href="<?= url('/login') ?>">Đăng nhập</a></li>
                                    <li><a class="dropdown-item" href="<?= url('/register') ?>">Đăng ký</a></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
        </div>

    </header>

    <!-- Small script to set body padding-top so content not covered by fixed header -->
    <script>
        (function() {
            function adjustPadding() {
                var header = document.getElementById('siteHeader');
                if (!header) return;
                var totalHeight = header.offsetHeight;
                // small safety margin
                document.body.style.paddingTop = (totalHeight + 6) + 'px';
            }
            window.addEventListener('load', adjustPadding);
            window.addEventListener('resize', adjustPadding);
            // also run immediately in case header already rendered
            adjustPadding();
        })();
    </script>

    <!-- rest of page... -->
    <div id="mainWrapper">