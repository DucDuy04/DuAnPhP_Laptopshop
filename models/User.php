<?php

/**
 * Table:  users
 * Relationships: 
 *   - User (N) -> (1) Role
 *   - User (1) -> (N) Order
 *   - User (1) -> (1) Cart
 */

require_once __DIR__ . '/../core/Model.php';

class User extends Model
{
    protected $table = 'users';

    // TÌm user theo email
    public function findByEmail($email)
    {
        $sql = "SELECT * FROM {$this->table} WHERE email = :email LIMIT 1";
        return $this->queryOne($sql, ['email' => $email]);
    }

   // Kiểm tra sự tồn tại của email
    public function existsByEmail($email)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE email = :email";
        $result = $this->queryOne($sql, ['email' => $email]);
        return $result['count'] > 0;
    }

    // Tìm user đầu tiên theo email
    public function findFirstByEmail($email)
    {
        return $this->findByEmail($email);
    }

    // Lấy user cùng với role
    public function findByIdWithRole($id)
    {
        $sql = "SELECT u.*, r.name as role_name, r.description as role_description 
                FROM {$this->table} u 
                LEFT JOIN roles r ON u.role_id = r.id 
                WHERE u.id = :id";
        return $this->queryOne($sql, ['id' => $id]);
    }

    // Lấy tất cả users cùng với role, phân trang
    public function findAllWithRole($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        // Count total
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $total = $this->queryOne($countSql)['total'];

        // Get data with role
        $sql = "SELECT u.*, r.name as role_name 
                FROM {$this->table} u 
                LEFT JOIN roles r ON u.role_id = r.id 
                ORDER BY u.id DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    // Tạo user mới với password được hash
    public function createUser($data)
    {
        // Hash password trước khi lưu
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        return $this->create($data);
    }

    // Cập nhật user với password được hash nếu có thay đổi
    public function updateUser($id, $data)
    {
        // Hash password nếu có thay đổi
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        } else {
            unset($data['password']); // Không update password nếu rỗng
        }
        return $this->update($id, $data);
    }

    // Xác thực password
    public function verifyPassword($password, $hashedPassword)
    {
        return password_verify($password, $hashedPassword);
    }

    // Lấy cart của user
    public function getCart($userId)
    {
        $sql = "SELECT * FROM carts WHERE user_id = :user_id LIMIT 1";
        return $this->queryOne($sql, ['user_id' => $userId]);
    }

    // Lấy orders của user
    public function getOrders($userId)
    {
        $sql = "SELECT * FROM orders WHERE user_id = :user_id ORDER BY id DESC";
        return $this->query($sql, ['user_id' => $userId]);
    }
}
