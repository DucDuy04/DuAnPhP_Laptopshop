<?php


?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - LaptopShop</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v6.3.0/css/all.css">
    <link href="<?= asset('css/styles.css') ?>" rel="stylesheet">
</head>

<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Đăng nhập</h3>
                                </div>
                                <div class="card-body">
                                    <?php if ($error = Session::getError('login')): ?>
                                        <div class="alert alert-danger"><?= e($error) ?></div>
                                    <?php endif; ?>

                                    <?php if ($success = Session::flash('success')): ?>
                                        <div class="alert alert-success"><?= e($success) ?></div>
                                    <?php endif; ?>

                                    <form method="POST" action="<?= url('/login') ?>">
                                        <?= Csrf::field() ?>

                                        <div class="form-floating mb-3">
                                            <input class="form-control <?= Session::hasError('email') ? 'is-invalid' : '' ?>"
                                                type="email" name="email" id="inputEmail" placeholder="Email"
                                                value="<?= e(Session::getOldInput('email')) ?>">
                                            <label for="inputEmail">Email</label>
                                            <?php if ($error = Session::getError('email')): ?>
                                                <div class="invalid-feedback"><?= e($error) ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="form-floating mb-3">
                                            <input class="form-control <?= Session::hasError('password') ? 'is-invalid' :  '' ?>"
                                                type="password" name="password" id="inputPassword" placeholder="Password">
                                            <label for="inputPassword">Mật khẩu</label>
                                            <?php if ($error = Session::getError('password')): ?>
                                                <div class="invalid-feedback"><?= e($error) ?></div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="<?= url('/') ?>">Về trang chủ</a>
                                            <button type="submit" class="btn btn-primary">Đăng nhập</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small">
                                        <a href="<?= url('/register') ?>">Chưa có tài khoản? Đăng ký ngay!</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min. js"></script>
</body>

</html>
<?php Session::clearValidation(); ?>