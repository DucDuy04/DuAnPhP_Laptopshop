    <?php

    /**
     * Base Controller Class
     * Tương đương @Controller trong Spring MVC
     * Cung cấp các hàm dùng chung
     * Tránh lặp code

     */

    class Controller
    {

        /**
         * Render view - tương đương return "viewName" trong Spring
         */
        // Hàm hiển thị view với dữ liệu truyền vào

        protected function view($viewPath, $data = [])
        {
            // Extract data để dùng trong view
            // biến mảng thành các biến riêng lẻ
            //ví dụ $data = ['error' => 'Sai mật khẩu'];
            //trong view có thể dùng biến $error trực tiếp: echo $error;
            extract($data);

            // Đường dẫn đầy đủ
            $viewFile = __DIR__ . '/../views/' . $viewPath . '.php';
            //$viewPath = 'auth/login';
            //Đường dẫn thật: /views/auth/login.php

            // Kiểm tra file tồn tại
            if (file_exists($viewFile)) {
                // Bao gồm file view
                require_once $viewFile;
            } else {
                // View không tồn tại
                die("View not found: {$viewPath}");
            }
        }

        /**
         * Redirect - tương đương return "redirect:/path"
         */
        // Hàm chuyển hướng trang
        protected function redirect($path)
        {
            // Gửi header chuyển hướng
            header("Location: " . $path);
            exit;
        }

        /**
         * Return JSON - tương đương @ResponseBody
         */
        // Hàm trả về dữ liệu JSON
        protected function json($data, $statusCode = 200)
        {
            // Gửi header và mã trạng thái
            http_response_code($statusCode);
            // Gửi dữ liệu JSON
            header('Content-Type:  application/json; charset=utf-8');
            // JSON_UNESCAPED_UNICODE để không mã hóa Unicode
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
            exit;
        }

        /**
         * Get POST data - tương đương @RequestParam
         */
        // Hàm lấy dữ liệu từ phương thức POST
        // Nếu không truyền key, trả về toàn bộ mảng $_POST
        protected function input($key = null, $default = null)
        {
            // Nếu không truyền key, trả về toàn bộ mảng $_POST
            if ($key === null) {
                return $_POST;
            }
            // Trả về giá trị tương ứng key hoặc default nếu không tồn tại
            return $_POST[$key] ?? $default;
        }

        /**
         * Get GET data - tương đương @RequestParam
         */
        // Hàm lấy dữ liệu từ phương thức GET
        protected function query($key = null, $default = null)
        {
            // Nếu không truyền key, trả về toàn bộ mảng $_GET
            if ($key === null) {
                return $_GET;
            }
            // Trả về giá trị tương ứng key hoặc default nếu không tồn tại
            return $_GET[$key] ?? $default;
        }

        /**
         * Check request method
         */
        // Hàm kiểm tra phương thức request hiện tại
        protected function isPost()
        {
            return $_SERVER['REQUEST_METHOD'] === 'POST';
        }

        // Hàm kiểm tra phương thức request hiện tại
        protected function isGet()
        {
            return $_SERVER['REQUEST_METHOD'] === 'GET';
        }

        /**
         * Set flash message - tương đương RedirectAttributes
         */
        // Hàm thiết lập flash message
        // Flash message chỉ tồn tại trong một request
        // Sau khi lấy sẽ bị xóa
        // Ví dụ: setFlash('success', 'Đăng ký thành công');
        // Trong view: echo getFlash('success');
        protected function setFlash($key, $message)
        {
            $_SESSION['flash'][$key] = $message;
        }

        /**
         * Get and clear flash message
         */
        // Hàm lấy và xóa flash message
        // Ví dụ: echo getFlash('success');
        // Sau khi gọi hàm này, flash message sẽ bị xóa khỏi session
        protected function getFlash($key)
        {
            $message = $_SESSION['flash'][$key] ?? null;
            unset($_SESSION['flash'][$key]);
            return $message;
        }

        /**
         * Validate CSRF token
         */
        // Hàm kiểm tra CSRF token
        // Nếu không hợp lệ, trả về lỗi JSON 403
        protected function validateCsrf()
        {
            // Chỉ kiểm tra với POST request
            $token = $_POST['_csrf_token'] ?? '';
            // Nếu không tồn tại token trong session hoặc không khớp
            if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
                // Trả về lỗi JSON 403
                $this->json(['error' => 'Invalid CSRF token'], 403);
            }
        }
    }
