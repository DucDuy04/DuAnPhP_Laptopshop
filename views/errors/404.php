<?php

/**
 * 404 Not Found Page
 */
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Không tìm thấy trang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #0646c5ff 0%, #5e8ae3ff 100%);
        }

        .error-container {
            text-align: center;
            color: white;
        }

        .error-code {
            font-size: 150px;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.3);
        }

        .error-message {
            font-size: 24px;
            margin-bottom: 30px;
        }

        .btn-home {
            padding: 15px 40px;
            font-size: 18px;
            border-radius: 50px;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-code">404</div>
        <div class="error-message">Oops! Trang bạn tìm không tồn tại</div>
        <a href="<?= url('/') ?>" class="btn btn-light btn-home">
            <i class="fas fa-home me-2"></i>Về trang chủ
        </a>
    </div>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
</body>

</html>