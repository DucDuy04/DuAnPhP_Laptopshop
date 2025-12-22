<?php

/**
 * Xử lý static files và routing
 
 *  File tĩnh → server trả thẳng
 *  Request nghiệp vụ → đưa vào index.php (MVC)
 */

// Lấy URI từ request
//uri: /laptopshop-php/public/css/style.css
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Xử lý static files (CSS, JS, images)
$staticExtensions = ['css', 'js', 'png', 'jpg', 'jpeg', 'gif', 'ico', 'svg', 'woff', 'woff2', 'ttf', 'eot'];

//pathinfo: lấy thông tin đường dẫn
//pathinfo_extension: lấy đuôi file
$extension = pathinfo($uri, PATHINFO_EXTENSION);


if (in_array($extension, $staticExtensions)) {
    // Kiểm tra file trong thư mục public
    $publicFile = __DIR__ . '/public' . $uri;
    if (file_exists($publicFile)) {
        return false; //Không xử lí bằng PHP mà để server xử lí trực tiếp
    }

    // Kiểm tra file uploads
    $uploadFile = __DIR__ . $uri;
    if (file_exists($uploadFile)) {
        return false;
    }
}

// Xử lý uploads folder
if (strpos($uri, '/uploads/') === 0) //kiểm tra chuỗi có bắt đầu bằng /uploads/ không
{
    $filePath = __DIR__ . $uri; //lấy đường dẫn tuyệt đối của file
    if (file_exists($filePath)) {
        // Phục vụ file với đúng Content-Type
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp'
        ];
        //lấy đuôi file
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        //nếu đuôi file tồn tại trong mảng mimeTypes
        if (isset($mimeTypes[$ext])) {
            //Gửi header đúng định dạng
            header('Content-Type: ' . $mimeTypes[$ext]);
            //Đọc và xuất file
            readfile($filePath);
            exit;
        }
    }
}

// Xử lý public folder
if (strpos($uri, '/public/') === 0) {
    $filePath = __DIR__ . $uri;
    if (file_exists($filePath)) {
        return false;
    }
}

// Tất cả requests khác đi qua index.php
require_once __DIR__ . '/index.php';
