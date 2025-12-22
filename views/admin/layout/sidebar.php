<?php


$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
?>
<div id="layoutSidenav">
    <div id="layoutSidenav_nav">
        <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
            <div class="sb-sidenav-menu">
                <div class="nav">
                    <div class="sb-sidenav-menu-heading">Features</div>

                    <a class="nav-link <?= $currentUri == '/admin' ? 'active' : '' ?>" href="<?= url('/admin') ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Dashboard
                    </a>

                    <a class="nav-link <?= strpos($currentUri, '/admin/user') !== false ? 'active' : '' ?>" href="<?= url('/admin/user') ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        User
                    </a>

                    <a class="nav-link <?= strpos($currentUri, '/admin/product') !== false ? 'active' : '' ?>" href="<?= url('/admin/product') ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Product
                    </a>

                    <a class="nav-link <?= strpos($currentUri, '/admin/order') !== false ? 'active' : '' ?>" href="<?= url('/admin/order') ?>">
                        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                        Order
                    </a>

                </div>
            </div>
            <div class="sb-sidenav-footer">
                <div class="small">Logged in as: </div>
                <?= e(Auth::name() ?? 'Admin') ?>
            </div>
        </nav>
    </div>
    <div id="layoutSidenav_content">
        <main>