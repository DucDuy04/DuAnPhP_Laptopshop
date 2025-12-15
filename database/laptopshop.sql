<?php

/**
 * Import database từ Java project
 * Chạy 1 lần rồi xóa
 */

require_once __DIR__ . '/config/database.php';

try {
    $db = Database::getInstance()->getConnection();

    echo "<h2>Import Database từ Java Project</h2>";

    // Tắt foreign key check
    $db->exec("SET FOREIGN_KEY_CHECKS = 0");

    // ============ ROLES ============
    echo "<h3>1. Tạo bảng Roles</h3>";
    $db->exec("DROP TABLE IF EXISTS roles");
    $db->exec("
        CREATE TABLE roles (
            id BIGINT NOT NULL AUTO_INCREMENT,
            name VARCHAR(50) NOT NULL,
            description VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY uk_roles_name (name)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    $db->exec("INSERT INTO roles (name, description) VALUES ('ADMIN', 'Quản trị viên'), ('USER', 'Người dùng')");
    echo "<p style='color: green;'>✓ Đã tạo bảng roles</p>";

    // ============ USERS ============
    echo "<h3>2. Tạo bảng Users</h3>";
    $db->exec("DROP TABLE IF EXISTS users");
    $db->exec("
        CREATE TABLE users (
            id BIGINT NOT NULL AUTO_INCREMENT,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            fullName VARCHAR(255) NOT NULL,
            address VARCHAR(255) DEFAULT NULL,
            phone VARCHAR(20) DEFAULT NULL,
            avatar VARCHAR(255) DEFAULT NULL,
            role_id BIGINT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            UNIQUE KEY uk_users_email (email),
            CONSTRAINT fk_users_role FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");

    // Tạo admin (password: 123456)
    $adminPassword = password_hash('123456', PASSWORD_BCRYPT);

    // Dùng query trực tiếp thay vì prepare để tránh lỗi placeholder
    $db->exec("INSERT INTO users (email, password, fullName, role_id) VALUES ('admin@gmail.com', '$adminPassword', 'Administrator', 1)");
    $db->exec("INSERT INTO users (email, password, fullName, role_id) VALUES ('user@gmail.com', '$adminPassword', 'Nguyen Van A', 2)");

    echo "<p style='color:green;'>✓ Đã tạo bảng users với admin và user</p>";

    // ============ PRODUCTS ============
    echo "<h3>3. Tạo bảng Products</h3>";
    $db->exec("DROP TABLE IF EXISTS products");
    $db->exec("
        CREATE TABLE products (
            id BIGINT NOT NULL AUTO_INCREMENT,
            detail_desc MEDIUMTEXT NOT NULL,
            factory VARCHAR(255) DEFAULT NULL,
            image VARCHAR(255) DEFAULT NULL,
            name VARCHAR(255) NOT NULL,
            price DOUBLE NOT NULL,
            quantity BIGINT NOT NULL,
            short_desc VARCHAR(255) NOT NULL,
            sold BIGINT NOT NULL,
            target VARCHAR(255) DEFAULT NULL,
            PRIMARY KEY (id),
            CONSTRAINT products_chk_1 CHECK (quantity >= 1)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
    ");

    // Insert sample products from Java database
    $db->exec("
        INSERT INTO products (id, detail_desc, factory, image, name, price, quantity, short_desc, sold, target) VALUES
        (1, 'ASUS TUF Gaming F15 FX506HF HN017W là chiếc laptop gaming giá rẻ nhưng vô cùng mạnh mẽ. Không chỉ bộ vi xử lý Intel thế hệ thứ 11, card đồ họa RTX 20 series mà điểm mạnh còn đến từ việc trang bị sẵn 16GB RAM, cho bạn hiệu năng xuất sắc mà không cần nâng cấp máy.', 'ASUS', '1711078092373-asus-01.png', 'Laptop Asus TUF Gaming', 17490000, 100, ' Intel, Core i5, 11400H', 0, 'GAMING'),
        (2, 'Khám phá sức mạnh tối ưu từ Dell Inspiron 15 N3520, chiếc laptop có cấu hình cực mạnh với bộ vi xử lý Intel Core i5 1235U thế hệ mới và dung lượng RAM lên tới 16GB. Bạn có thể thoải mái xử lý nhiều tác vụ, nâng cao năng suất trong công việc mà không gặp bất kỳ trở ngại nào.', 'DELL', '1711078452562-dell-01.png', 'Laptop Dell Inspiron 15 ', 15490000, 200, 'i5 1235U/16GB/512GB/15.6\"FHD', 0, 'SINHVIEN-VANPHONG'),
        (3, ' Mới đây, Lenovo đã tung ra thị trường một sản phẩm gaming thế hệ mới với hiệu năng mạnh mẽ, thiết kế tối giản, lịch lãm phù hợp cho những game thủ thích sự đơn giản. Tản nhiệt mát mẻ với hệ thống quạt kép kiểm soát được nhiệt độ máy luôn mát mẻ khi chơi game.', 'LENOVO', '1711079073759-lenovo-01.png', 'Lenovo IdeaPad Gaming 3', 19500000, 150, ' i5-10300H, RAM 8G', 0, 'GAMING'),
        (4, 'Tận hưởng cảm giác mát lạnh sành điệu với thiết kế kim loại\r\nĐược thiết kế để đáp ứng những nhu cầu điện toán hàng ngày của bạn, dòng máy tính xách tay ASUS K Series sở hữu thiết kế tối giản, gọn nhẹ và cực mỏng với một lớp vỏ họa tiết vân kim loại phong cách. Hiệu năng của máy rất mạnh mẽ nhờ trang bị bộ vi xử lý Intel® Core™ i7 processor và đồ họa mới nhất. Bên cạnh đó, các công nghệ sáng tạo độc quyền của ASUS đưa thiết bị lên đẳng cấp mới, cho bạn một trải nghiệm người dùng trực quan và tính năng công thái học vượt trội.', 'ASUS', '1711079496409-asus-02.png', 'Asus K501UX', 11900000, 99, 'VGA NVIDIA GTX 950M- 4G', 0, 'THIET-KE-DO-HOA'),
        (5, 'Chiếc MacBook Air có hiệu năng đột phá nhất từ trước đến nay đã xuất hiện. Bộ vi xử lý Apple M1 hoàn toàn mới đưa sức mạnh của MacBook Air M1 13 inch 2020 vượt xa khỏi mong đợi người dùng, có thể chạy được những tác vụ nặng và thời lượng pin đáng kinh ngạc.', 'APPLE', '1711079954090-apple-01.png', 'MacBook Air 13', 17690000, 99, 'Apple M1 GPU 7 nhân', 0, 'GAMING'),
        (6, '14.0 Chính: inch, 2880 x 1800 Pixels, OLED, 90 Hz, OLED', 'LG', '1711080386941-lg-01.png', 'Laptop LG Gram Style', 31490000, 99, 'Intel Iris Plus Graphics', 0, 'DOANH-NHAN'),
        (7, 'Không chỉ khơi gợi cảm hứng qua việc cách tân thiết kế, MacBook Air M2 2022 còn chứa đựng nguồn sức mạnh lớn lao với chip M2 siêu mạnh, thời lượng pin chạm  ngưỡng 18 giờ, màn hình Liquid Retina tuyệt đẹp và hệ thống camera kết hợp cùng âm thanh tân tiến.', 'APPLE', '1711080787179-apple-02.png', 'MacBook Air 13 ', 24990000, 99, ' Apple M2 GPU 8 nhân', 0, 'MONG-NHE'),
        (8, 'Là chiếc laptop gaming thế hệ mới nhất thuộc dòng Nitro 5 luôn chiếm được rất nhiều cảm tình của game thủ trước đây, Acer Nitro Gaming AN515-58-769J nay còn ấn tượng hơn nữa với bộ vi xử lý Intel Core i7 12700H cực khủng và card đồ họa RTX 3050, sẵn sàng cùng bạn chinh phục những đỉnh cao.\r\n', 'ACER', '1711080948771-acer-01.png', 'Laptop Acer Nitro ', 23490000, 99, 'AN515-58-769J i7 12700H', 0, 'SINHVIEN-VANPHONG'),
        (9, '15.6 inch, FHD (1920 x 1080), IPS, 144 Hz, 250 nits, Acer ComfyView LED-backlit', 'ASUS', '1711081080930-asus-03.png', 'Laptop Acer Nitro V', 26999000, 99, ' NVIDIA GeForce RTX 4050', 0, 'MONG-NHE'),
        (10, 'Dell Inspiron N3520 là chiếc laptop lý tưởng cho công việc hàng ngày. Bộ vi xử lý Intel Core i5 thế hệ thứ 12 hiệu suất cao, màn hình lớn 15,6 inch Full HD 120Hz mượt mà, thiết kế bền bỉ sẽ giúp bạn giải quyết công việc nhanh chóng mọi lúc mọi nơi.', 'DELL', '1711081278418-dell-02.png', 'Laptop Dell Latitude 3420', 21399000, 99, ' Intel Iris Xe Graphics', 0, 'MONG-NHE')
    ");

    echo "<p style='color:green;'>✓ Đã tạo bảng products với 10 sản phẩm mẫu</p>";

    // ============ CARTS ============
    echo "<h3>4. Tạo bảng Carts</h3>";
    $db->exec("DROP TABLE IF EXISTS cart_detail");
    $db->exec("DROP TABLE IF EXISTS carts");
    $db->exec("
        CREATE TABLE carts (
            id BIGINT NOT NULL AUTO_INCREMENT,
            user_id BIGINT NOT NULL,
            sum INT DEFAULT 0,
            PRIMARY KEY (id),
            UNIQUE KEY uk_carts_user (user_id),
            CONSTRAINT fk_carts_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "<p style='color:green;'>✓ Đã tạo bảng carts</p>";

    // ============ CART_DETAIL ============
    echo "<h3>5. Tạo bảng Cart Detail</h3>";
    $db->exec("
        CREATE TABLE cart_detail (
            id BIGINT NOT NULL AUTO_INCREMENT,
            cart_id BIGINT NOT NULL,
            product_id BIGINT NOT NULL,
            quantity BIGINT DEFAULT 1,
            price DOUBLE NOT NULL,
            PRIMARY KEY (id),
            CONSTRAINT fk_cart_detail_cart FOREIGN KEY (cart_id) REFERENCES carts (id) ON DELETE CASCADE,
            CONSTRAINT fk_cart_detail_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "<p style='color:green;'>✓ Đã tạo bảng cart_detail</p>";

    // ============ ORDERS ============
    echo "<h3>6. Tạo bảng Orders</h3>";
    $db->exec("DROP TABLE IF EXISTS order_details");
    $db->exec("DROP TABLE IF EXISTS orders");
    $db->exec("
        CREATE TABLE orders (
            id BIGINT NOT NULL AUTO_INCREMENT,
            user_id BIGINT DEFAULT NULL,
            total_price DOUBLE NOT NULL,
            receiver_name VARCHAR(255) NOT NULL,
            receiver_phone VARCHAR(20) NOT NULL,
            receiver_address VARCHAR(500) NOT NULL,
            status VARCHAR(50) DEFAULT 'PENDING',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id),
            CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "<p style='color:green;'>✓ Đã tạo bảng orders</p>";

    // ============ ORDER_DETAILS ============
    echo "<h3>7. Tạo bảng Order Details</h3>";
    $db->exec("
        CREATE TABLE order_details (
            id BIGINT NOT NULL AUTO_INCREMENT,
            order_id BIGINT NOT NULL,
            product_id BIGINT NOT NULL,
            quantity BIGINT DEFAULT 1,
            price DOUBLE NOT NULL,
            PRIMARY KEY (id),
            CONSTRAINT fk_order_details_order FOREIGN KEY (order_id) REFERENCES orders (id) ON DELETE CASCADE,
            CONSTRAINT fk_order_details_product FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    echo "<p style='color:green;'>✓ Đã tạo bảng order_details</p>";

    // Bật lại foreign key check
    $db->exec("SET FOREIGN_KEY_CHECKS = 1");

    // ============ KIỂM TRA ============
    echo "<hr>";
    echo "<h3>Kết quả: </h3>";

    // Hiển thị products
    echo "<h4>Products (từ database Java):</h4>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Name</th><th>Price</th><th>Factory</th><th>Quantity</th></tr>";
    $products = $db->query("SELECT id, name, price, factory, quantity FROM products")->fetchAll();
    foreach ($products as $p) {
        echo "<tr>";
        echo "<td>{$p['id']}</td>";
        echo "<td>{$p['name']}</td>";
        echo "<td>" . number_format($p['price']) . " đ</td>";
        echo "<td>{$p['factory']}</td>";
        echo "<td>{$p['quantity']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    // Hiển thị users
    echo "<h4>Users: </h4>";
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>ID</th><th>Email</th><th>FullName</th><th>Role</th></tr>";
    $users = $db->query("SELECT u.id, u.email, u.fullName, r.name as role_name FROM users u LEFT JOIN roles r ON u.role_id = r.id")->fetchAll();
    foreach ($users as $u) {
        echo "<tr>";
        echo "<td>{$u['id']}</td>";
        echo "<td>{$u['email']}</td>";
        echo "<td>{$u['fullName']}</td>";
        echo "<td>{$u['role_name']}</td>";
        echo "</tr>";
    }
    echo "</table>";

    echo "<hr>";
    echo "<h3 style='color:green;'>✓ Import thành công!</h3>";
    echo "<p><strong>Tài khoản đăng nhập:</strong></p>";
    echo "<ul>";
    echo "<li>Admin: <strong>admin@gmail.com</strong> / <strong>123456</strong></li>";
    echo "<li>User: <strong>user@gmail.com</strong> / <strong>123456</strong></li>";
    echo "</ul>";
    echo "<p><a href='/login'>👉 Đăng nhập ngay</a></p>";
    echo "<hr>";
    echo "<p style='color:red;'><strong>⚠️ XÓA file import_java_db.php sau khi hoàn tất! </strong></p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Lỗi: " . $e->getMessage() . "</p>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
