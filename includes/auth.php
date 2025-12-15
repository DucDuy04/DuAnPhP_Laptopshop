<?php

/**
 * Authentication System
 * Tương đương Spring Security + CustomUserDetailsService
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';

class Auth
{
    private static $user = null;
    private static $userModel = null;

    /**
     * Khởi tạo User Model
     */
    private static function getUserModel()
    {
        if (self::$userModel === null) {
            self::$userModel = new User();
        }
        return self::$userModel;
    }

    /**
     * Đăng nhập
     * Tương đương AuthenticationManager.authenticate() trong Spring Security
     */
    public static function login($email, $password)
    {
        $userModel = self::getUserModel();
        $user = $userModel->findByEmail($email);

        if (! $user) {
            return ['success' => false, 'message' => 'Email không tồn tại'];
        }

        // Verify password - tương đương BCryptPasswordEncoder. matches()
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Mật khẩu không đúng'];
        }

        // Lưu thông tin user vào session
        // Tương đương SecurityContextHolder.getContext().setAuthentication()
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['fullName'];
        $_SESSION['user_avatar'] = $user['avatar'];

        // Lấy role
        $userWithRole = $userModel->findByIdWithRole($user['id']);
        $_SESSION['user_role'] = $userWithRole['role_name'] ?? 'USER';

        // Regenerate session ID để tránh session fixation
        session_regenerate_id(true);

        return ['success' => true, 'user' => $user];
    }

    /**
     * Đăng xuất
     * Tương đương SecurityContextLogoutHandler
     */
    public static function logout()
    {
        // Xóa tất cả session data
        $_SESSION = [];

        // Xóa session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        // Hủy session
        session_destroy();

        // Bắt đầu session mới
        session_start();
        session_regenerate_id(true);
    }

    /**
     * Kiểm tra đã đăng nhập chưa
     * Tương đương SecurityContextHolder.getContext().getAuthentication().isAuthenticated()
     */
    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && ! empty($_SESSION['user_id']);
    }

    /**
     * Lấy thông tin user hiện tại
     * Tương đương @AuthenticationPrincipal hoặc SecurityContextHolder.getContext().getAuthentication().getPrincipal()
     */
    public static function user()
    {
        if (!self::isLoggedIn()) {
            return null;
        }

        if (self::$user === null) {
            $userModel = self::getUserModel();
            self::$user = $userModel->findByIdWithRole($_SESSION['user_id']);
        }

        return self::$user;
    }

    /**
     * Lấy user ID hiện tại
     */
    public static function id()
    {
        return $_SESSION['user_id'] ??  null;
    }

    /**
     * Lấy email hiện tại
     */
    public static function email()
    {
        return $_SESSION['user_email'] ?? null;
    }

    /**
     * Lấy tên user hiện tại
     */
    public static function name()
    {
        return $_SESSION['user_name'] ?? null;
    }

    /**
     * Lấy avatar hiện tại
     */
    public static function avatar()
    {
        return $_SESSION['user_avatar'] ?? null;
    }

    /**
     * Lấy role hiện tại
     */
    public static function role()
    {
        return $_SESSION['user_role'] ?? null;
    }

    /**
     * Kiểm tra role
     * Tương đương hasRole() trong Spring Security
     */
    public static function hasRole($role)
    {
        return self::isLoggedIn() && $_SESSION['user_role'] === $role;
    }

    /**
     * Kiểm tra có phải Admin không
     * Tương đương hasRole('ADMIN')
     */
    public static function isAdmin()
    {
        return self::hasRole('ADMIN');
    }

    /**
     * Kiểm tra có phải User thường không
     */
    public static function isUser()
    {
        return self::hasRole('USER');
    }

    /**
     * Yêu cầu đăng nhập - redirect nếu chưa đăng nhập
     * Tương đương . authenticated() trong SecurityFilterChain
     */
    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . url('/login'));
            exit;
        }
    }

    /**
     * Yêu cầu role cụ thể
     * Tương đương @PreAuthorize("hasRole('ADMIN')")
     */
    public static function requireRole($role)
    {
        self::requireLogin();

        if (!self::hasRole($role)) {
            header('Location: ' . url('/access-denied'));
            exit;
        }
    }

    /**
     * Yêu cầu Admin role
     * Tương đương @PreAuthorize("hasRole('ADMIN')")
     */
    public static function requireAdmin()
    {
        self::requireRole('ADMIN');
    }

    /**
     * Redirect nếu đã đăng nhập (dùng cho trang login/register)
     * Tương đương permitAll() nhưng redirect nếu đã login
     */
    public static function redirectIfLoggedIn($url = '/')
    {
        if (self::isLoggedIn()) {
            // Redirect theo role
            if (self::isAdmin()) {
                header('Location:  ' . url('/admin'));
            } else {
                header('Location: ' . url($url));
            }
            exit;
        }
    }

    /**
     * Cập nhật thông tin session khi user update profile
     */
    public static function refreshUser()
    {
        if (self::isLoggedIn()) {
            $userModel = self::getUserModel();
            $user = $userModel->findByIdWithRole($_SESSION['user_id']);

            if ($user) {
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['user_name'] = $user['fullName'];
                $_SESSION['user_avatar'] = $user['avatar'];
                $_SESSION['user_role'] = $user['role_name'] ?? 'USER';
                self::$user = $user;
            }
        }
    }

    /**
     * Lấy số lượng sản phẩm trong giỏ hàng
     */
    public static function getCartCount()
    {
        if (!self::isLoggedIn()) {
            return 0;
        }
        return $_SESSION['cart_count'] ?? 0;
    }

    /**
     * Cập nhật số lượng giỏ hàng trong session
     */
    public static function updateCartCount($count)
    {
        $_SESSION['cart_count'] = $count;
    }
}

// ==================== HELPER FUNCTIONS ====================
// Các hàm shortcut để dùng trong views

/**
 * Kiểm tra đã đăng nhập chưa
 */
function isLoggedIn()
{
    return Auth::isLoggedIn();
}

/**
 * Lấy user hiện tại
 */
function currentUser()
{
    return Auth::user();
}

/**
 * Kiểm tra role
 */
function hasRole($role)
{
    return Auth::hasRole($role);
}

/**
 * Kiểm tra Admin
 */
function isAdmin()
{
    return Auth::isAdmin();
}

/**
 * Yêu cầu đăng nhập
 */
function requireLogin()
{
    Auth::requireLogin();
}

/**
 * Yêu cầu Admin
 */
function requireAdmin()
{
    Auth::requireAdmin();
}
