<?php

/**
 * Table: order_details
 * Relationships:
 *   - OrderDetail (N) -> (1) Order
 *   - OrderDetail (N) -> (1) Product
 */

require_once __DIR__ . '/../core/Model.php';

class OrderDetail extends Model
{
    protected $table = 'order_details';

    // Lấy tất cả chi tiết đơn hàng theo order_id
    public function findByOrderId($orderId)
    {
        $sql = "SELECT od.*, p.name, p. image
                FROM {$this->table} od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = :order_id";
        return $this->query($sql, ['order_id' => $orderId]);
    }

    // Tạo chi tiết đơn hàng mới
    public function createOrderDetail($orderId, $productId, $quantity, $price)
    {
        return $this->create([
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price
        ]);
    }

    // Tạo nhiều chi tiết đơn hàng từ giỏ hàng
    public function createFromCart($orderId, $cartItems)
    {
        foreach ($cartItems as $item) {
            $this->createOrderDetail(
                $orderId,
                $item['product_id'],
                $item['quantity'],
                $item['price']
            );
        }
    }

    // Xóa chi tiết đơn hàng theo order_id
    public function deleteByOrderId($orderId)
    {
        $sql = "DELETE FROM {$this->table} WHERE order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['order_id' => $orderId]);
    }
}
