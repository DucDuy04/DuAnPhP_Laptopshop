<?php

/**
 * Middleware System
 * Tương đương SecurityFilterChain trong Spring Security
 */

require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/csrf.php';

class Middleware
{

    /**
     * Danh sách các route public (không cần đăng nhập)
     * Tương đương . permitAll() trong Spring Security
     */
    private static $publicRoutes = [
        '/',
        '/login',
        '/register',
        '/products',
        '/product/*',
        '/access-denied',
    ];

    /**
     * Danh sách các route chỉ dành cho Admin
     * Tương đương . hasRole("ADMIN")
     */
    private static $adminRoutes = [
        '/admin',
        '/admin/*',
    ];

    /**
     * Danh sách các route chỉ dành cho User đã đăng nhập
     */
    private static $authRoutes = [
        '/cart',
        '/checkout',
        '/place-order',
        '/order-history',
        '/thanks',
    ];

    /**
     * Chạy middleware
     * Gọi hàm này ở đầu index.php
     */
    public static function handle()
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Remove base path
        $basePath = '/laptopshop-php';
        if (strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath)) ?: '/';
        }

        // 1. CSRF Validation cho POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            Csrf::validateOrFail();
        }

        // 2. Check Admin routes
        if (self::matchRoute($uri, self::$adminRoutes)) {
            Auth::requireAdmin();
            return;
        }

        // 3. Check Auth routes (user phải đăng nhập)
        if (self::matchRoute($uri, self::$authRoutes)) {
            Auth::requireLogin();
            return;
        }

        // 4. Public routes - không cần xử lý gì
    }

    /**
     * Kiểm tra URI có match với pattern không
     */
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

    /**
     * Kiểm tra route có public không
     */
    public static function isPublicRoute($uri)
    {
        return self::matchRoute($uri, self::$publicRoutes);
    }

    /**
     * Kiểm tra route có phải admin route không
     */
    public static function isAdminRoute($uri)
    {
        return self::matchRoute($uri, self::$adminRoutes);
    }
}
