<?php

/**
Quản lý xác thực, check người dùng đăng nhập/đăng xuất...
 */

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';

class Auth
{
    private static $user = null;
    private static $userModel = null;

    // Lấy instance của User model
    private static function getUserModel()
    {
        if (self::$userModel === null) {
            self::$userModel = new User();
        }
        return self::$userModel;
    }


    public static function login($email, $password)
    {

        $userModel = self::getUserModel();
        $user = $userModel->findByEmail($email);

        if (! $user) {
            return ['success' => false, 'message' => 'Email không tồn tại'];
        }

        // Kiểm tra mật khẩu
        if (!password_verify($password, $user['password'])) {
            return ['success' => false, 'message' => 'Mật khẩu không đúng'];
        }

        // Lưu thông tin user vào session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['fullName'];
        $_SESSION['user_avatar'] = $user['avatar'];

        // Lấy role
        $userWithRole = $userModel->findByIdWithRole($user['id']);
        $_SESSION['user_role'] = $userWithRole['role_name'] ?? 'USER';

        // Regenerate session ID để chống session fixation
        session_regenerate_id(true);

        return ['success' => true, 'user' => $user];
    }


    public static function logout()
    {
        // Xóa tất cả session data
        $_SESSION = [];

        // Xóa session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        session_destroy();

        session_start();
        session_regenerate_id(true);
    }


    public static function isLoggedIn()
    {
        return isset($_SESSION['user_id']) && ! empty($_SESSION['user_id']);
    }

    // Lấy user hiện tại
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


    public static function id()
    {
        return $_SESSION['user_id'] ??  null;
    }


    public static function email()
    {
        return $_SESSION['user_email'] ?? null;
    }


    public static function name()
    {
        return $_SESSION['user_name'] ?? null;
    }


    public static function avatar()
    {
        return $_SESSION['user_avatar'] ?? null;
    }


    public static function role()
    {
        return $_SESSION['user_role'] ?? null;
    }


    public static function hasRole($role)
    {
        return self::isLoggedIn() && $_SESSION['user_role'] === $role;
    }


    public static function isAdmin()
    {
        return self::hasRole('ADMIN');
    }


    public static function isUser()
    {
        return self::hasRole('USER');
    }


    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {
            $_SESSION['redirect_url'] = $_SERVER['REQUEST_URI'];
            header('Location: ' . url('/login'));
            exit;
        }
    }


    public static function requireRole($role)
    {
        self::requireLogin();

        if (!self::hasRole($role)) {
            header('Location: ' . url('/access-denied'));
            exit;
        }
    }


    public static function requireAdmin()
    {
        self::requireRole('ADMIN');
    }


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


    public static function getCartCount()
    {
        if (!self::isLoggedIn()) {
            return 0;
        }
        return $_SESSION['cart_count'] ?? 0;
    }


    public static function updateCartCount($count)
    {
        $_SESSION['cart_count'] = $count;
    }
}


// Các hàm để dùng trong views


function isLoggedIn()
{
    return Auth::isLoggedIn();
}


function currentUser()
{
    return Auth::user();
}


function hasRole($role)
{
    return Auth::hasRole($role);
}


function isAdmin()
{
    return Auth::isAdmin();
}


function requireLogin()
{
    Auth::requireLogin();
}


function requireAdmin()
{
    Auth::requireAdmin();
}
