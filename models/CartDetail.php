<?php
/**
 * CartDetail Model
 * Tương đương CartDetail.java + CartDetailRepository.java
 * 
 * Table: cart_detail
 * Relationships:
 *   - CartDetail (N) -> (1) Cart
 *   - CartDetail (N) -> (1) Product
 */

require_once __DIR__ . '/../core/Model.php';

class CartDetail extends Model
{
    protected $table = 'cart_detail';

    /**
     * Tìm item trong cart theo cart_id và product_id
     */
    public function findByCartAndProduct($cartId, $productId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE cart_id = :cart_id AND product_id = :product_id 
                LIMIT 1";
        return $this->queryOne($sql, [
            'cart_id' => $cartId,
            'product_id' => $productId
        ]);
    }

    /**
     * Lấy tất cả items trong cart
     */
    public function findByCartId($cartId)
    {
        $sql = "SELECT cd.*, p.name, p. image, p.price as product_price
                FROM {$this->table} cd
                JOIN products p ON cd.product_id = p.id
                WHERE cd.cart_id = :cart_id";
        return $this->query($sql, ['cart_id' => $cartId]);
    }

    /**
     * Thêm sản phẩm vào cart
     */
    public function addToCart($cartId, $productId, $price, $quantity = 1)
    {
        // Check if product already in cart
        $existing = $this->findByCartAndProduct($cartId, $productId);

        if ($existing) {
            // Update quantity
            $newQty = $existing['quantity'] + $quantity;
            return $this->update($existing['id'], ['quantity' => $newQty]);
        } else {
            // Add new item
            return $this->create([
                'cart_id' => $cartId,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity
            ]);
        }
    }

    /**
     * Cập nhật số lượng
     */
    public function updateQuantity($id, $quantity)
    {
        if ($quantity <= 0) {
            return $this->delete($id);
        }
        return $this->update($id, ['quantity' => $quantity]);
    }

    /**
     * Xóa item khỏi cart
     */
    public function removeFromCart($cartId, $productId)
    {
        $sql = "DELETE FROM {$this->table} WHERE cart_id = :cart_id AND product_id = :product_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'cart_id' => $cartId,
            'product_id' => $productId
        ]);
    }

    /**
     * Xóa tất cả items trong cart
     */
    public function clearByCartId($cartId)
    {
        $sql = "DELETE FROM {$this->table} WHERE cart_id = :cart_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['cart_id' => $cartId]);
    }

    /**
     * Đếm số lượng items trong cart
     */
    public function countByCartId($cartId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} WHERE cart_id = :cart_id";
        $result = $this->queryOne($sql, ['cart_id' => $cartId]);
        return $result['count'];
    }
}
