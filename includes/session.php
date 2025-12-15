<?php

/**
 * Session Management
 * Tương đương Spring Session
 */

class Session
{

    /**
     * Khởi tạo session với các cấu hình bảo mật
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Cấu hình session
            ini_set('session.cookie_httponly', 1);
            ini_set('session.use_only_cookies', 1);
            ini_set('session.cookie_secure', isset($_SERVER['HTTPS'])); // HTTPS only
            ini_set('session. cookie_samesite', 'Lax');
            ini_set('session.gc_maxlifetime', SESSION_LIFETIME);

            session_name(SESSION_NAME);
            session_start();

            // Kiểm tra session timeout
            self::checkTimeout();

            // Regenerate session ID định kỳ
            self::regenerateIfNeeded();
        }
    }

    /**
     * Kiểm tra session timeout
     */
    private static function checkTimeout()
    {
        if (isset($_SESSION['last_activity'])) {
            $inactive = time() - $_SESSION['last_activity'];
            if ($inactive > SESSION_LIFETIME) {
                self::destroy();
                session_start();
            }
        }
        $_SESSION['last_activity'] = time();
    }

    /**
     * Regenerate session ID định kỳ (mỗi 30 phút)
     */
    private static function regenerateIfNeeded()
    {
        if (! isset($_SESSION['created'])) {
            $_SESSION['created'] = time();
        } elseif (time() - $_SESSION['created'] > 1800) {
            session_regenerate_id(true);
            $_SESSION['created'] = time();
        }
    }

    /**
     * Lấy giá trị từ session
     */
    public static function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set giá trị vào session
     */
    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Kiểm tra key có tồn tại không
     */
    public static function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Xóa một key khỏi session
     */
    public static function remove($key)
    {
        unset($_SESSION[$key]);
    }

    /**
     * Flash message - lưu message và tự động xóa sau khi đọc
     * Tương đương RedirectAttributes. addFlashAttribute() trong Spring
     */
    public static function flash($key, $message = null)
    {
        if ($message === null) {
            // Get and remove
            $value = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $value;
        } else {
            // Set
            $_SESSION['flash'][$key] = $message;
        }
    }

    /**
     * Kiểm tra có flash message không
     */
    public static function hasFlash($key)
    {
        return isset($_SESSION['flash'][$key]);
    }

    /**
     * Lưu old input (để repopulate form sau khi validation fail)
     * Tương đương BindingResult trong Spring
     */
    public static function setOldInput($data)
    {
        $_SESSION['old_input'] = $data;
    }

    /**
     * Lấy old input
     */
    public static function getOldInput($key = null, $default = '')
    {
        if ($key === null) {
            return $_SESSION['old_input'] ?? [];
        }
        return $_SESSION['old_input'][$key] ?? $default;
    }

    /**
     * Xóa old input
     */
    public static function clearOldInput()
    {
        unset($_SESSION['old_input']);
    }

    /**
     * Set validation errors
     */
    public static function setErrors($errors)
    {
        $_SESSION['errors'] = $errors;
    }

    /**
     * Get validation error
     */
    public static function getError($key)
    {
        return $_SESSION['errors'][$key] ?? null;
    }

    /**
     * Get all errors
     */
    public static function getErrors()
    {
        return $_SESSION['errors'] ?? [];
    }

    /**
     * Check if has error
     */
    public static function hasError($key)
    {
        return isset($_SESSION['errors'][$key]);
    }

    /**
     * Check if has any errors
     */
    public static function hasErrors()
    {
        return !empty($_SESSION['errors']);
    }

    /**
     * Clear all errors
     */
    public static function clearErrors()
    {
        unset($_SESSION['errors']);
    }

    /**
     * Clear validation data (errors + old input)
     */
    public static function clearValidation()
    {
        self::clearErrors();
        self::clearOldInput();
    }

    /**
     * Hủy session hoàn toàn
     */
    public static function destroy()
    {
        $_SESSION = [];

        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }

        session_destroy();
    }

    /**
     * Lấy tất cả session data (debug)
     */
    public static function all()
    {
        return $_SESSION;
    }
}
