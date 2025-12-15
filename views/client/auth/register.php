<?php
/**
 * Register View
 * Tương đương client/auth/register.jsp
 */
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - LaptopShop</title>
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
                        <div class="col-lg-7">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header">
                                    <h3 class="text-center font-weight-light my-4">Tạo tài khoản</h3>
                                </div>
                                <div class="card-body">
                                    <?php if ($error = Session:: flash('error')): ?>
                                    <div class="alert alert-danger"><?= e($error) ?></div>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="<?= url('/register') ?>">
                                        <?= Csrf:: field() ?>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control <?= Session::hasError('firstName') ? 'is-invalid' : '' ?>" 
                                                           type="text" name="firstName" id="inputFirstName" 
                                                           placeholder="First name"
                                                           value="<?= e(Session::getOldInput('firstName')) ?>">
                                                    <label for="inputFirstName">Họ</label>
                                                    <?php if ($error = Session::getError('firstName')): ?>
                                                    <div class="invalid-feedback"><?= e($error) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating">
                                                    <input class="form-control" type="text" name="lastName" 
                                                           id="inputLastName" placeholder="Last name"
                                                           value="<?= e(Session::getOldInput('lastName')) ?>">
                                                    <label for="inputLastName">Tên</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-floating mb-3">
                                            <input class="form-control <?= Session::hasError('email') ? 'is-invalid' : '' ?>" 
                                                   type="email" name="email" id="inputEmail" 
                                                   placeholder="name@example.com"
                                                   value="<?= e(Session::getOldInput('email')) ?>">
                                            <label for="inputEmail">Email</label>
                                            <?php if ($error = Session::getError('email')): ?>
                                            <div class="invalid-feedback"><?= e($error) ?></div>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control <?= Session::hasError('password') ? 'is-invalid' : '' ?>" 
                                                           type="password" name="password" id="inputPassword" 
                                                           placeholder="Password">
                                                    <label for="inputPassword">Mật khẩu</label>
                                                    <?php if ($error = Session::getError('password')): ?>
                                                    <div class="invalid-feedback"><?= e($error) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-floating mb-3 mb-md-0">
                                                    <input class="form-control <?= Session::hasError('confirmPassword') ? 'is-invalid' : '' ?>" 
                                                           type="password" name="confirmPassword" 
                                                           id="inputConfirmPassword" placeholder="Confirm Password">
                                                    <label for="inputConfirmPassword">Xác nhận mật khẩu</label>
                                                    <?php if ($error = Session::getError('confirmPassword')): ?>
                                                    <div class="invalid-feedback"><?= e($error) ?></div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 mb-0">
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary btn-block">Đăng ký</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center py-3">
                                    <div class="small">
                                        <a href="<?= url('/login') ?>">Đã có tài khoản?  Đăng nhập ngay! </a>
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