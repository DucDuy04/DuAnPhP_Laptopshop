<?php

/**
 * CSRF Protection
 * Tương đương CsrfFilter trong Spring Security
 */

class Csrf
{

    /**
     * Tạo CSRF token mới
     */
    public static function generateToken()
    {
        if (! isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Lấy token hiện tại
     */
    public static function getToken()
    {
        return $_SESSION['csrf_token'] ?? self::generateToken();
    }

    /**
     * Tạo hidden input field
     * Tương đương <input type="hidden" name="_csrf" value="${_csrf.token}"> trong JSP
     */
    public static function field()
    {
        $token = self::getToken();
        return '<input type="hidden" name="_csrf_token" value="' . $token . '">';
    }

    /**
     * Tạo meta tags cho AJAX
     * Tương đương <meta name="_csrf" content="${_csrf.token}"> trong JSP
     */
    public static function meta()
    {
        $token = self::getToken();
        return '<meta name="_csrf" content="' . $token .  '">' . "\n" .
            '<meta name="_csrf_header" content="X-CSRF-TOKEN">';
    }

    /**
     * Validate CSRF token
     * Tương đương CsrfFilter. doFilter()
     */
    public static function validate()
    {
        // Bỏ qua GET requests
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            return true;
        }

        // Lấy token từ POST hoặc Header (cho AJAX)
        $token = $_POST['_csrf_token']
            ?? $_SERVER['HTTP_X_CSRF_TOKEN']
            ?? '';

        // So sánh với session token
        if (! isset($_SESSION['csrf_token']) || ! hash_equals($_SESSION['csrf_token'], $token)) {
            return false;
        }

        return true;
    }

    /**
     * Validate và throw error nếu invalid
     */
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

    /**
     * Kiểm tra có phải AJAX request không
     */
    private static function isAjax()
    {
        return ! empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Refresh token (tạo token mới sau khi submit form)
     */
    public static function refresh()
    {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        return $_SESSION['csrf_token'];
    }
}
