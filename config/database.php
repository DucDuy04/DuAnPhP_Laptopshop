<?php

/**
Cấu hình kết nối database tới Mysql
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'laptopshopphp');
define('DB_USER', 'root');
define('DB_PASS', '0406');
define('DB_CHARSET', 'utf8mb4');

class Database
{
    private static $instance = null;
    private $connection;

    private function __construct()
    {
        try {
            // Tạo DSN
            //dsn="mysql:host=localhost;dbname=laptopshopphp;charset=utf8mb4"
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME .  ";charset=" . DB_CHARSET;


            // Tùy chọn kết nối
            $options = [
                // Thiết lập lỗi ở chế độ ngoại lệ
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                // Thiết lập kiểu lấy dữ liệu mặc định là mảng kết hợp
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                // Tắt chế độ giả lập prepared statements
                //Các câu lệnh được thực thi bởi MySql server thay vì PHP
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    // Lấy instance của Database (Singleton)
    public static function getInstance()
    {
        if (self::$instance === null) {
            // Tạo instance mới nếu chưa tồn tại
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        return $this->connection;
    }

    //Đảm bảo chỉ có một instance của Database
    // Ngăn chặn clone
    private function __clone() {}
    // Ngăn chặn unserialize
    public function __wakeup() {}
}
