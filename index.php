<?php

/**
 * Application Entry Point
 * Tương đương LaptopshopApplication. java + DispatcherServlet
 * file điều khiển tất cả request đến ứng dụng
 */

// Load configurations
require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';

// Load core classes
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';

// Load includes
require_once __DIR__ . '/includes/helpers.php';
//quản lý session
require_once __DIR__ . '/includes/session.php';
//quản lý csrf
require_once __DIR__ . '/includes/csrf.php';
//Kiểm tra xác thực
require_once __DIR__ . '/includes/auth.php';
//Kiểm soát truy cập
require_once __DIR__ . '/includes/middleware.php';

// Start session
Session::start();

// Generate CSRF token
Csrf::generateToken();

// Run middleware (authentication & authorization checks)
Middleware::handle();

// Initialize router
$router = new Router();

// Load routes
require_once __DIR__ . '/config/routers.php';

// Dispatch the request
$router->dispatch();
