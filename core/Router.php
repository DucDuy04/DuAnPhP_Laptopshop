<?php

/**
 * Lớp điều hướng, ánh xạ các route (đường dẫn) vào đúng controller và phương thức xử lý.
 Router có nhiệm vụ:

Nhận request từ trình duyệt
Xác định:
        HTTP method (GET / POST)
        URL
Tìm route tương ứng
Gọi Controller@method
Truyền tham số từ URL vào method
 */

class Router
{
    private $routes = [
        'GET' => [],
        'POST' => []
    ];

    //Khi viết: $router->get('/login', 'AuthController@showLogin');
    // Route lưu: $this->routes['GET']['/login'] = 'AuthController@showLogin';
    public function get($path, $handler)
    {
        $this->routes['GET'][$path] = $handler;
    }


    public function post($path, $handler)
    {
        $this->routes['POST'][$path] = $handler;
    }

    //Xử lý request hiện tại
    // Tìm route phù hợp và gọi controller tương ứng
    // Nếu không tìm thấy route, trả về lỗi 404

    public function dispatch()
    {

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Chuẩn hóa URI
        $uri = rtrim($uri, '/') ?:  '/';
        // Ví dụ: /products/123/ -> /products/123

        // Tìm route phù hợp
        // Duyệt qua các route cùng method
        foreach ($this->routes[$method] ??  [] as $pattern => $handler) { //pattern là đường dẫn đã đăng ký, handler là controller@method
            $regex = $this->convertToRegex($pattern); //chuyển đường dẫn thành regex để so sánh

            // So sánh URI với regex
            if (preg_match($regex, $uri, $matches)) {
                // Lấy các tham số từ URI
                // Loại bỏ phần tử đầu tiên (toàn bộ chuỗi khớp)
                //Chỉ lấy các tham số trong dấu {}
                //Ví dụ: /products/123 -> $matches = [123]
                //Đây là tham số truyền vào method của controller
                array_shift($matches);

                // Tách controller và method từ handler
                // Ví dụ: 'ProductController@show' -> ['ProductController', 'show']
                list($controllerName, $methodName) = explode('@', $handler);


                // Xử lý admin controllers
                if (strpos($controllerName, 'admin/') === 0) {
                    // Loại bỏ 'admin/' khỏi tên controller
                    // Ví dụ: 'admin/ProductController' -> 'ProductController'
                    // Load file controller từ thư mục admin
                    // Ví dụ: core/../controllers/admin/ProductController.php

                    $controllerFile = __DIR__ . '/../controllers/' . $controllerName . '.php';
                    $controllerName = basename($controllerName);
                } else { // Xử lý normal controllers
                    // Load file controller từ thư mục controllers
                    $controllerFile = __DIR__ . '/../controllers/' . $controllerName .  '.php';
                }

                if (! file_exists($controllerFile)) { //
                    // Nếu file controller không tồn tại, trả về lỗi 404
                    //Gọi hàm notFound với thông báo lỗi
                    $this->notFound("Controller not found: " . $controllerFile);
                }

                require_once $controllerFile; //nạp file controller


                $controller = new $controllerName(); //tạo đối tượng controller

                if (! method_exists($controller, $methodName)) {
                    $this->notFound("Method not found:  " . $methodName);
                }

                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        $this->notFound();
    }
    //Hàm dispatch() chịu trách nhiệm điều phối request, xác định route phù hợp dựa trên HTTP method và URI, 
    //sau đó gọi controller và method tương ứng kèm theo tham số động.


    // Chuyển đổi đường dẫn có tham số thành biểu thức chính quy
    // Ví dụ: /products/{id} -> /^\/products\/([0-9]+)$/
    //Router cần chuyển route sang regex để có thể so khớp các URL động và trích xuất tham số từ đường dẫn,
    // vì so sánh chuỗi thông thường không xử lý được các route có biến
    private function convertToRegex($pattern)
    {
        $pattern = str_replace('/', '\/', $pattern);
        $pattern = preg_replace('/\{([a-zA-Z]+)\}/', '([0-9]+)', $pattern);
        $pattern = preg_replace('/\{([a-zA-Z]+):any\}/', '([^\/]+)', $pattern);
        return '/^' . $pattern . '$/';
    }

    private function notFound($message = '')
    {
        http_response_code(404);
        if (APP_DEBUG && $message) {
            echo "<h1>404 Not Found</h1><p>$message</p>";
            echo "<p>URI: " . $_SERVER['REQUEST_URI'] . "</p>";
        } else {
            require_once __DIR__ . '/../views/errors/404.php';
        }
        exit;
    }
}
