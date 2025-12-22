<?php

/**
 * 
 * Table: carts
 * Relationships: 
 *   - Cart (1) -> (1) User
 *   - Cart (1) -> (N) CartDetail
 */

require_once __DIR__ . '/../core/Model.php';

class Cart extends Model
{
    protected $table = 'carts';

    // Lấy giỏ hàng theo user_id
    public function findByUserId($userId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id LIMIT 1";
        return $this->queryOne($sql, ['user_id' => $userId]);
    }

    // Lấy giỏ hàng của user, nếu chưa có thì tạo mới
    public function getOrCreateCart($userId)
    {
        //Kiểm tra user da có cart chưa
        $cart = $this->findByUserId($userId);

        if (!$cart) {
            $cartId = $this->create([
                'user_id' => $userId,
                'sum' => 0
            ]);
            $cart = $this->findById($cartId);
        }

        return $cart;
    }


    // Cập nhật tổng số lượng trong giỏ hàng
    public function updateSum($cartId)
    {
        $sql = "UPDATE {$this->table} 
                SET sum = (SELECT COALESCE(SUM(quantity), 0) FROM cart_detail WHERE cart_id = :cart_id)
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(['cart_id' => $cartId, 'id' => $cartId]);
    }

    // Lấy giỏ hàng cùng với chi tiết sản phẩm
    public function getCartWithDetails($userId)
    {
        $cart = $this->findByUserId($userId);

        if (!$cart) {
            return null;
        }

        $sql = "SELECT cd.*, p.name, p.image, p.price as product_price, p.quantity as stock
                FROM cart_detail cd
                JOIN products p ON cd.product_id = p.id
                WHERE cd.cart_id = :cart_id";

        $cart['items'] = $this->query($sql, ['cart_id' => $cart['id']]); //gán giá trị $cart['id'] vào :cart_id
        //Nếu $cart['id'] = 5
        //Khi thực thi SQL → cd.cart_id = 5

        // Tính tổng tiền
        $cart['total_price'] = array_reduce($cart['items'], function ($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        return $cart;
    }

    // Xóa giỏ hàng
    public function clearCart($cartId)
    {
        $sql = "DELETE FROM cart_detail WHERE cart_id = :cart_id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['cart_id' => $cartId]);

        // Reset sum
        $this->update($cartId, ['sum' => 0]);
    }
}
