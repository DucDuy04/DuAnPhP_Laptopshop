<?php

/**
 * Table: orders
 * Relationships:
 *   - Order (N) -> (1) User
 *   - Order (1) -> (N) OrderDetail
 */

require_once __DIR__ . '/../core/Model.php';

class Order extends Model
{
    protected $table = 'orders';

    //Định nghĩa các trạng thái đơn hàng
    const STATUS_PENDING = 'PENDING';
    const STATUS_SHIPPING = 'SHIPPING';
    const STATUS_COMPLETE = 'COMPLETE';
    const STATUS_CANCELLED = 'CANCELLED';

    // Lấy orders của user với pagination
    public function findByUserId($userId, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage; // Tính toán offset

        // Đếm tổng số đơn hàng của user
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = :user_id";
        $total = $this->queryOne($countSql, ['user_id' => $userId])['total'];

        // lấy danh sách đơn hàng của user theo phân trang
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

    // Lấy đơn hàng theo ID kèm chi tiết đơn hàng
    public function findByIdWithDetails($orderId)
    {   // Lấy thông tin đơn hàng
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

   // Lấy tất cả đơn hàng kèm thông tin user với pagination
    public function findAllWithUser($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        // Đếm tổng số đơn hàng
        $countSql = "SELECT COUNT(*) as total FROM {$this->table}";
        $total = $this->queryOne($countSql)['total'];

        // Lấy dữ liệu kèm thông tin user
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

    // Tạo đơn hàng mới
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

    // Cập nhật trạng thái đơn hàng
    public function updateStatus($orderId, $status)
    {
        return $this->update($orderId, ['status' => $status]);
    }

   // Đếm số đơn hàng theo trạng thái
    public function countByStatus($status)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE status = :status";
        $result = $this->queryOne($sql, ['status' => $status]);
        return $result['count'];
    }

   // Tính tổng doanh thu từ các đơn hàng đã hoàn thành
    public function getTotalRevenue()
    {
        $sql = "SELECT SUM(total_price) as revenue FROM {$this->table} WHERE status = :status";
        $result = $this->queryOne($sql, ['status' => self::STATUS_COMPLETE]);
        return $result['revenue'] ?? 0;
    }
}
