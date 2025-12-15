<?php

/**
File cấu hình toàn cục cho ứng dụng
Chứa các hằng số và thiết lập chung được sử dụng trong toàn bộ ứng dụng
 */

// Base URL - RỖNG khi dùng PHP built-in server
define('BASE_URL', '');

// App settings
// Tên ứng dụng và chế độ debug
define('APP_NAME', 'LaptopShop');
define('APP_DEBUG', true);

// Upload settings
// Giới hạn kích thước file upload và đường dẫn lưu trữ
define('UPLOAD_MAX_SIZE', 50 * 1024 * 1024);
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// Session settings
// Thời gian sống của session và tên session
define('SESSION_LIFETIME', 30 * 60);
define('SESSION_NAME', 'LAPTOPSHOP_SESSION');

// Pagination
// Số mục hiển thị trên mỗi trang
define('ITEMS_PER_PAGE', 10);

// Roles
// Các vai trò người dùng trong hệ thống
define('ROLE_ADMIN', 'ADMIN');
define('ROLE_USER', 'USER');

// Error reporting
// Cấu hình hiển thị lỗi dựa trên chế độ debug
//Bật tắt báo lỗi
//Ví dụ:Lỗi:Warning: mysqli_connect(): Access denied for user 'root'
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set('Asia/Ho_Chi_Minh');
