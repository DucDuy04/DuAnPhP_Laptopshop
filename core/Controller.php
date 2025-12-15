<?php

/**
 * Base Controller Class
 * Tương đương @Controller trong Spring MVC
 */

class Controller
{

    /**
     * Render view - tương đương return "viewName" trong Spring
     */
    protected function view($viewPath, $data = [])
    {
        // Extract data để dùng trong view
        extract($data);

        // Đường dẫn đầy đủ
        $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';

        if (file_exists($viewFile)) {
            require_once $viewFile;
        } else {
            die("View not found: {$viewPath}");
        }
    }

    /**
     * Redirect - tương đương return "redirect:/path"
     */
    protected function redirect($path)
    {
        header("Location: " . $path);
        exit;
    }

    /**
     * Return JSON - tương đương @ResponseBody
     */
    protected function json($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type:  application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Get POST data - tương đương @RequestParam
     */
    protected function input($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Get GET data - tương đương @RequestParam
     */
    protected function query($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Check request method
     */
    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Set flash message - tương đương RedirectAttributes
     */
    protected function setFlash($key, $message)
    {
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Get and clear flash message
     */
    protected function getFlash($key)
    {
        $message = $_SESSION['flash'][$key] ?? null;
        unset($_SESSION['flash'][$key]);
        return $message;
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf()
    {
        $token = $_POST['_csrf_token'] ?? '';
        if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
            $this->json(['error' => 'Invalid CSRF token'], 403);
        }
    }
}
