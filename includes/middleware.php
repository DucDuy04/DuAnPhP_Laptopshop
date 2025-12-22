<?php

/**
điều kiện, kiểm soát quyền truy cập ở mỗi request.
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/csrf.php';

class Middleware
{

    // Danh sách các route public (không cần đăng nhập)
    private static $publicRoutes = [
        '/',
        '/login',
        '/register',
        '/products',
        '/product/*',
        '/access-denied',
    ];

    // Danh sách các route chỉ dành cho Admin
    private static $adminRoutes = [
        '/admin',
        '/admin/*',
    ];

    // Danh sách các route yêu cầu đăng nhập
    private static $authRoutes = [
        '/cart',
        '/checkout',
        '/place-order',
        '/order-history',
        '/thanks',
    ];


    // Hàm xử lý middleware
    public static function handle()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Loại bỏ base path nếu có
        $basePath = '/laptopshop-php';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath)) ?: '/';
        }

        // 1. CSRF Protection cho các POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::validateOrFail();
        }

        // 2. Check Admin routes (user phải là admin)
        if (self::matchRoute($uri, self::$adminRoutes)) {
            Auth::requireAdmin();
            return;
        }

        // 3. Check Auth routes (user phải đăng nhập)
        if (self::matchRoute($uri, self::$authRoutes)) {
            Auth::requireLogin();
            return;
        }
    }
    // Hàm kiểm tra route có khớp với danh sách patterns không
    private static function matchRoute($uri, $patterns)
    {
        foreach ($patterns as $pattern) {
            // Convert pattern to regex
            $regex = str_replace('/', '\/', $pattern);
            $regex = str_replace('*', '.*', $regex);
            $regex = '/^' . $regex . '$/';

            if (preg_match($regex, $uri)) {
                return true;
            }
        }
        return false;
    }


    public static function isPublicRoute($uri)
    {
        return self::matchRoute($uri, self::$publicRoutes);
    }


    public static function isAdminRoute($uri)
    {
        return self::matchRoute($uri, self::$adminRoutes);
    }
}
