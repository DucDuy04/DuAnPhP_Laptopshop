<?php

/**
 * Base Model Class
 * Tương đương JpaRepository trong Spring Data JPA
 * Cấu hình kết nối DB và các hàm CRUD chung
 */

require_once __DIR__ . '/../config/database.php';

abstract class Model
{
    protected $db; // PDO instance
    protected $table; // Table name
    protected $primaryKey = 'id'; // Primary key column

    public function __construct() // Khởi tạo kết nối DB
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Tương đương findAll() trong JpaRepository
     */
    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table}"; // Lấy tất cả bản ghi từ bảng
        $stmt = $this->db->query($sql); // Thực thi truy vấn
        return $stmt->fetchAll(); // Trả về tất cả kết quả
    }

    /**
     * Tương đương findById() trong JpaRepository
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id"; // Lấy bản ghi theo ID
        $stmt = $this->db->prepare($sql); // Chuẩn bị truy vấn
        $stmt->execute(['id' => $id]); // Thực thi với tham số ID
        return $stmt->fetch(); // Trả về kết quả
    }

    /**
     * Tương đương findAll(Pageable) trong JpaRepository
     */
    public function findAllPaginated($page = 1, $perPage = 10) // Lấy bản ghi phân trang
    {
        $offset = ($page - 1) * $perPage; // Tính toán offset

        // Get total count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}"; // Đếm tổng số bản ghi
        $countStmt = $this->db->query($countSql); // Thực thi truy vấn đếm
        $total = $countStmt->fetch()['total']; // Lấy tổng số bản ghi

        // Get paginated data
        $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset"; // Lấy bản ghi với giới hạn và offset
        $stmt = $this->db->prepare($sql); // Chuẩn bị truy vấn
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT); // Gán giá trị limit
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT); // Gán giá trị offset
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    /**
     * Tương đương save() trong JpaRepository (INSERT)
     */
    public function create(array $data)
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return $this->db->lastInsertId();
    }

    /**
     * Tương đương save() trong JpaRepository (UPDATE)
     */
    public function update($id, array $data)
    {
        $setClause = [];
        foreach (array_keys($data) as $column) {
            $setClause[] = "{$column} = :{$column}";
        }
        $setClause = implode(', ', $setClause);

        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        $data['id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Tương đương deleteById() trong JpaRepository
     */
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Tương đương existsById() trong JpaRepository
     */
    public function exists($id)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch()['count'] > 0;
    }

    /**
     * Tương đương count() trong JpaRepository
     */
    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['count'];
    }

    /**
     * Custom query - cho các trường hợp phức tạp
     */
    protected function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Custom query trả về 1 record
     */
    protected function queryOne($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
}
