<?php
/**
 * OrderDetail Model
 * Tương đương OrderDetail.java + OrderDetailRepository.java
 * 
 * Table: order_details
 * Relationships:
 *   - OrderDetail (N) -> (1) Order
 *   - OrderDetail (N) -> (1) Product
 */

require_once __DIR__ . '/../core/Model.php';

class OrderDetail extends Model
{
    protected $table = 'order_details';

    /**
     * Lấy chi tiết của một order
     */
    public function findByOrderId($orderId)
    {
        $sql = "SELECT od.*, p.name, p. image
                FROM {$this->table} od
                JOIN products p ON od.product_id = p.id
                WHERE od.order_id = :order_id";
        return $this->query($sql, ['order_id' => $orderId]);
    }

    /**
     * Tạo order detail
     */
    public function createOrderDetail($orderId, $productId, $quantity, $price)
    {
        return $this->create([
            'order_id' => $orderId,
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $price
        ]);
    }

    /**
     * Tạo nhiều order details cùng lúc (từ cart)
     */
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

    /**
     * Xóa tất cả details của order
     */
    public function deleteByOrderId($orderId)
    {
        $sql = "DELETE FROM {$this->table} WHERE order_id = :order_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['order_id' => $orderId]);
    }
}
