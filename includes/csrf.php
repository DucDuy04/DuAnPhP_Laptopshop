<?php

/**
Tạo và kiểm tra CSRF token, chống tấn công giả mạo request ( Cross-Site Request Forgery - CSRF)
 */

class Csrf
{

    public static function generateToken()
    {
        if (! isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }


    public static function getToken()
    {
        return $_SESSION['csrf_token'] ?? self::generateToken();
    }

    // Tạo hidden input field cho form
    public static function field()
    {
        $token = self::getToken();
        return '<input type="hidden" name="_csrf_token" value="' . $token . '">';
    }

    // Tạo thẻ meta cho CSRF token (dùng cho AJAX)
    public static function meta()
    {
        $token = self::getToken();
        return '<meta name="_csrf" content="' . $token .  '">' . "\n" .
            '<meta name="_csrf_header" content="X-CSRF-TOKEN">';
    }

    // Kiểm tra CSRF token
    public static function validate()
    {
        // Bỏ qua GET requests
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }
        // Lấy token từ POST hoặc Header
        $token = $_POST['_csrf_token']
            ?? $_SERVER['HTTP_X_CSRF_TOKEN']
            ?? '';

        // So sánh với session token
        if (! isset($_SESSION['csrf_token']) || ! hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }

        return true;
    }

    // Kiểm tra CSRF token, nếu không hợp lệ trả về lỗi 403 và dừng chương trình
    public static function validateOrFail()
    {
        if (!self::validate()) {
            http_response_code(403);
            if (self::isAjax()) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'CSRF token mismatch']);
            } else {
                echo '403 Forbidden - CSRF token mismatch';
            }
            exit;
        }
    }

    // Kiểm tra xem request có phải là AJAX không(AJAX: Asynchronous JavaScript And XML)
    private static function isAjax()
    {
        return ! empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    // Làm mới CSRF token
    public static function refresh()
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
}
