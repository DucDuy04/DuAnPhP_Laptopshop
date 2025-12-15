<?php

/**
 * Cart Controller
 * Tương đương ItemController.java - phần cart
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/CartDetail.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../includes/auth.php';

class CartController extends Controller
{

    private $cartModel;
    private $cartDetailModel;
    private $productModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
        $this->cartDetailModel = new CartDetail();
        $this->productModel = new Product();
    }

    /**
     * Hiển thị giỏ hàng
     * Tương đương @GetMapping("/cart")
     */
    public function index()
    {
        requireLogin();

        $userId = Auth::id();
        $cart = $this->cartModel->getCartWithDetails($userId);

        $this->view('client/cart/show', [
            'cart' => $cart,
            'cartDetails' => $cart['items'] ?? [],
            'totalPrice' => $cart['total_price'] ?? 0,
            'isCartEmpty' => empty($cart['items']),
            'pageTitle' => 'Giỏ hàng - LaptopShop'
        ]);
    }

    /**
     * Thêm sản phẩm vào giỏ hàng
     * Tương đương @PostMapping("/add-product-to-cart/{id}")
     */
    public function addToCart($productId)
    {
        requireLogin();

        $userId = Auth::id();
        $quantity = (int)($this->input('quantity', 1));

        // Lấy thông tin sản phẩm
        $product = $this->productModel->findById($productId);
        if (!$product) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Sản phẩm không tồn tại'], 404);
            }
            $this->redirect('/products');
            return;
        }

        // Kiểm tra còn hàng không
        if ($product['quantity'] < $quantity) {
            if ($this->isAjax()) {
                $this->json(['success' => false, 'message' => 'Sản phẩm không đủ số lượng']);
            }
            Session::flash('error', 'Sản phẩm không đủ số lượng');
            $this->redirect('/product/' . $productId);
            return;
        }

        // Lấy hoặc tạo cart
        $cart = $this->cartModel->getOrCreateCart($userId);

        // Thêm vào cart
        $this->cartDetailModel->addToCart($cart['id'], $productId, $product['price'], $quantity);

        // Cập nhật sum trong cart
        $this->cartModel->updateSum($cart['id']);

        // Cập nhật cart count trong session
        $cartCount = $this->cartDetailModel->countByCartId($cart['id']);
        Auth::updateCartCount($cartCount);

        if ($this->isAjax()) {
            $this->json([
                'success' => true,
                'message' => 'Đã thêm vào giỏ hàng',
                'cartCount' => $cartCount
            ]);
        }

        Session::flash('success', 'Đã thêm sản phẩm vào giỏ hàng');
        $this->redirect('/cart');
    }

    /**
     * Xóa sản phẩm khỏi giỏ hàng
     * Tương đương @PostMapping("/remove-cart-item/{id}")
     */
    public function removeFromCart($cartDetailId)
    {
        requireLogin();

        $this->cartDetailModel->delete($cartDetailId);

        // Cập nhật sum
        $userId = Auth::id();
        $cart = $this->cartModel->findByUserId($userId);
        if ($cart) {
            $this->cartModel->updateSum($cart['id']);
            $cartCount = $this->cartDetailModel->countByCartId($cart['id']);
            Auth::updateCartCount($cartCount);
        }

        if ($this->isAjax()) {
            $this->json(['success' => true, 'message' => 'Đã xóa sản phẩm']);
        }

        $this->redirect('/cart');
    }

    /**
     * Cập nhật số lượng trong giỏ hàng
     * Tương đương @PostMapping("/confirm-checkout")
     */
    public function update()
    {
        requireLogin();

        $cartDetails = $this->input('cartDetails', []);

        foreach ($cartDetails as $item) {
            if (isset($item['id']) && isset($item['quantity'])) {
                $this->cartDetailModel->updateQuantity($item['id'], (int)$item['quantity']);
            }
        }

        // Cập nhật sum
        $userId = Auth::id();
        $cart = $this->cartModel->findByUserId($userId);
        if ($cart) {
            $this->cartModel->updateSum($cart['id']);
        }

        $this->redirect('/checkout');
    }

    /**
     * Kiểm tra có phải AJAX request không
     */
    private function isAjax()
    {
        return ! empty($_SERVER['HTTP_X_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
