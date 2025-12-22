<?php

/**
 * Cấu hình kết nối DB và các hàm CRUD chung
 */

require_once __DIR__ . '/../config/database.php';

abstract class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    //Lấy tất cả bản ghi trong bảng
    public function findAll()
    {
        $sql = "SELECT * FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    // Lấy bản ghi theo ID
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    // Lấy danh sách bản ghi theo từng trang
    // Trả về thông tin phân trang (tổng bản ghi, tổng số trang…)
    public function findAllPaginated($page = 1, $perPage = 10) // Lấy bản ghi phân trang
    {
        $offset = ($page - 1) * $perPage; // Tính toán offset

        // Đếm tổng số bản ghi
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $countStmt = $this->db->query($countSql);
        $total = $countStmt->fetch()['total'];

        // Lấy dữ liệu phân trang
        $sql = "SELECT * FROM {$this->table} LIMIT :limit OFFSET :offset"; // Lấy bản ghi với giới hạn và offset
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT); // Gán giá trị limit
        //Bắt buộc phải dùng bindValue với PDO::PARAM_INT để tránh lỗi(Mysql chỉ chấp nhận số nguyên cho LIMIT và OFFSET)
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT); // Gán giá trị offset -> bỏ qua các bản ghi trước đó
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage)
        ];
    }


    // Hàm tạo bản ghi mới vào database
    //trả về ID của bản ghi mới tạo
    //Ví dụThêm sản phẩm,Thêm user,Thêm đơn hàng
    public function create(array $data) //data là mảng key-value tương ứng cột và giá trị
    {
        //Implode: nối các phần tử trong mảng thành chuỗi, ngăn cách bởi ', '
        $columns = implode(', ', array_keys($data));

        //Thêm dấu : vào trước mỗi key để chỉ chỗ cần truyền giá trị
        //Dùng cho phần: VALUES (:name, :price, :quantity)
        $placeholders = ':' . implode(', :', array_keys($data));

        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return $this->db->lastInsertId();
    }

    // Hàm cập nhật bản ghi trong database theo id
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


    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['id' => $id]);
    }


    public function exists($id)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch()['count'] > 0;
    }


    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $stmt = $this->db->query($sql);
        return $stmt->fetch()['count'];
    }

    //Tạo query cho các trường hợp phức tạp 
    protected function query($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }


    protected function queryOne($sql, $params = [])
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch();
    }
}
