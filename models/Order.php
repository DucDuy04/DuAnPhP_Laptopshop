<?php

/**
 * Order Model
 * Tương đương Order.java + OrderRepository.java
 * 
 * Table: orders
 * Relationships:
 *   - Order (N) -> (1) User
 *   - Order (1) -> (N) OrderDetail
 */

require_once __DIR__ . '/../core/Model.php';

class Order extends Model
{
    protected $table = 'orders';

    // Order statuses
    const STATUS_PENDING = 'PENDING';
    const STATUS_SHIPPING = 'SHIPPING';
    const STATUS_COMPLETE = 'COMPLETE';
    const STATUS_CANCELLED = 'CANCELLED';

    /**
     * Lấy orders của user
     */
    public function findByUserId($userId, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        // Count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = :user_id";
        $total = $this->queryOne($countSql, ['user_id' => $userId])['total'];

        // Get data
        $sql = "SELECT * FROM {$this->table} 
                WHERE user_id = :user_id 
                ORDER BY id DESC 
                LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);

        return [
            'data' => $stmt->fetchAll(),
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    /**
     * Lấy order với chi tiết
     * Tương đương order.getOrderDetails() trong Java
     */
    public function findByIdWithDetails($orderId)
    {
        $order = $this->findById($orderId);

        if (! $order) {
            return null;
        }

        $sql = "SELECT od.*, p. name, p.image 
                FROM order_details od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = :order_id";

        $order['order_details'] = $this->query($sql, ['order_id' => $orderId]);

        return $order;
    }

    /**
     * Lấy tất cả orders với pagination (Admin)
     */
    public function findAllWithUser($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        // Count
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $total = $this->queryOne($countSql)['total'];

        // Get data with user info
        $sql = "SELECT o.*, u.fullName as user_name, u.email as user_email
                FROM {$this->table} o
                LEFT JOIN users u ON o.user_id = u.id
                ORDER BY o.id DESC
                LIMIT {$perPage} OFFSET {$offset}";

        $stmt = $this->db->prepare($sql);
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
     * Tạo đơn hàng mới
     */
    public function createOrder($userId, $receiverInfo, $totalPrice)
    {
        return $this->create([
            'user_id' => $userId,
            'receiver_name' => $receiverInfo['name'],
            'receiver_phone' => $receiverInfo['phone'],
            'receiver_address' => $receiverInfo['address'],
            'total_price' => $totalPrice,
            'status' => self::STATUS_PENDING
        ]);
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus($orderId, $status)
    {
        return $this->update($orderId, ['status' => $status]);
    }

    /**
     * Đếm orders theo status
     */
    public function countByStatus($status)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = :status";
        $result = $this->queryOne($sql, ['status' => $status]);
        return $result['count'];
    }

    /**
     * Tính tổng doanh thu
     */
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(total_price) as revenue FROM {$this->table} WHERE status = :status";
        $result = $this->queryOne($sql, ['status' => self::STATUS_COMPLETE]);
        return $result['revenue'] ?? 0;
    }
}
