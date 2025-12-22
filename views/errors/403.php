<?php


?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Truy cập bị từ chối</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
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
        <div class="error-code">403</div>
        <div class="error-message">Bạn không có quyền truy cập trang này</div>
        <a href="<?= url('/') ?>" class="btn btn-light btn-home">
            <i class="fas fa-home me-2"></i>Về trang chủ
        </a>
    </div>
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js"></script>
</body>

</html>