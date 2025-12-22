<?php

/**
 * file điều khiển tất cả request đến ứng dụng
 */


require_once __DIR__ . '/config/app.php';
require_once __DIR__ . '/config/database.php';


require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Router.php';


require_once __DIR__ . '/includes/helpers.php';

require_once __DIR__ . '/includes/session.php';

require_once __DIR__ . '/includes/csrf.php';

require_once __DIR__ . '/includes/auth.php';

require_once __DIR__ . '/includes/middleware.php';


Session::start();


Csrf::generateToken();

// Xử lý middleware
Middleware::handle();



$router = new Router();

// Load routes
require_once __DIR__ . '/config/routers.php';

// Dispatch the request
$router->dispatch();
