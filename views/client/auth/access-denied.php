<?php


?>
<! DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Access Denied</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: Arial, sans-serif;
                background-color: #e8f5e9;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                padding: 20px;
            }

            .container {
                background: white;
                border: 3px solid #0646c5ff;
                border-radius: 10px;
                width: 500px;
                padding: 40px;
                text-align: center;
                box-shadow: 0 4px 6px rgba(76, 175, 80, 0.2);
            }

            .icon {
                width: 80px;
                height: 80px;
                background-color: #f44336;
                border-radius: 50%;
                margin: 0 auto 20px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .icon svg {
                width: 40px;
                height: 40px;
                fill: white;
            }

            h1 {
                color: #f44336;
                font-size: 28px;
                margin-bottom: 10px;
            }

            .error-code {
                color: #f44336;
                font-size: 16px;
                font-weight: bold;
                margin-bottom: 20px;
            }

            .message {
                color: #555;
                font-size: 15px;
                line-height: 1.6;
                margin-bottom: 30px;
            }

            .btn {
                background-color: #0646c5ff;
                color: white;
                padding: 12px 30px;
                border: none;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                text-decoration: none;
                display: inline-block;
                margin: 5px;
            }

            .btn:hover {
                background-color: #0338a1ff;
                color: white;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                </svg>
            </div>
            <h1>Access Denied</h1>
            <p class="error-code">Lỗi 403</p>
            <p class="message">
                Bạn không có quyền truy cập vào trang này. <br>
                Vui lòng liên hệ quản trị viên nếu bạn cho rằng đây là lỗi.
            </p>
            <a href="<?= url('/') ?>" class="btn">Về trang chủ</a>
            <!-- <a href="<?= url('/login') ?>" class="btn">Đăng nhập</a> -->
        </div>
    </body>

    </html>