<?php

/**
 * Checkout Controller
 * Tương đương ItemController.java - phần checkout
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Cart.php';
require_once __DIR__ . '/../models/CartDetail.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/OrderDetail.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../includes/auth.php';

class CheckoutController extends Controller
{

    private $cartModel;
    private $cartDetailModel;
    private $orderModel;
    private $orderDetailModel;
    private $productModel;

    public function __construct()
    {
        $this->cartModel = new Cart();
        $this->cartDetailModel = new CartDetail();
        $this->orderModel = new Order();
        $this->orderDetailModel = new OrderDetail();
        $this->productModel = new Product();
    }

    /**
     * Trang checkout
     * Tương đương @GetMapping("/checkout")
     */
    public function index()
    {
        requireLogin();

        $userId = Auth::id();
        $cart = $this->cartModel->getCartWithDetails($userId);

        // Kiểm tra giỏ hàng rỗng
        if (! $cart || empty($cart['items'])) {
            Session::flash('error', 'Giỏ hàng của bạn đang trống');
            $this->redirect('/cart');
            return;
        }

        $this->view('client/checkout/show', [
            'cartDetails' => $cart['items'],
            'totalPrice' => $cart['total_price'],
            'user' => Auth::user(),
            'pageTitle' => 'Thanh toán - LaptopShop'
        ]);
    }

    /**
     * Đặt hàng
     * Tương đương @PostMapping("/place-order")
     */
    public function placeOrder()
    {
        requireLogin();

        $userId = Auth::id();

        // Lấy thông tin người nhận
        $receiverName = trim($this->input('receiverName'));
        $receiverPhone = trim($this->input('receiverPhone'));
        $receiverAddress = trim($this->input('receiverAddress'));

        // Validate
        $errors = [];
        if (empty($receiverName)) {
            $errors['receiverName'] = 'Vui lòng nhập tên người nhận';
        }
        if (empty($receiverPhone)) {
            $errors['receiverPhone'] = 'Vui lòng nhập số điện thoại';
        } elseif (!preg_match('/^[0-9]{10,11}$/', $receiverPhone)) {
            $errors['receiverPhone'] = 'Số điện thoại không hợp lệ';
        }
        if (empty($receiverAddress)) {
            $errors['receiverAddress'] = 'Vui lòng nhập địa chỉ';
        }

        if (! empty($errors)) {
            Session::setErrors($errors);
            // Preserve submitted values so the form can be repopulated after redirect
            Session::setOldInput([
                'receiverName' => $receiverName,
                'receiverPhone' => $receiverPhone,
                'receiverAddress' => $receiverAddress,
                'note' => $this->input('note')
            ]);
            $this->redirect('/checkout');
            return;
        }

        // Lấy giỏ hàng
        $cart = $this->cartModel->getCartWithDetails($userId);
        if (!$cart || empty($cart['items'])) {
            Session::flash('error', 'Giỏ hàng của bạn đang trống');
            $this->redirect('/cart');
            return;
        }

        // Tạo đơn hàng
        $orderId = $this->orderModel->createOrder($userId, [
            'name' => $receiverName,
            'phone' => $receiverPhone,
            'address' => $receiverAddress
        ], $cart['total_price']);

        // Tạo order details
        foreach ($cart['items'] as $item) {
            $this->orderDetailModel->createOrderDetail(
                $orderId,
                $item['product_id'],
                $item['quantity'],
                $item['price']
            );

            // Cập nhật số lượng sản phẩm
            $this->productModel->updateSold($item['product_id'], $item['quantity']);
        }

        // Xóa giỏ hàng
        $this->cartModel->clearCart($cart['id']);
        Auth::updateCartCount(0);

        Session::clearValidation();
        $this->redirect('/thanks');
    }

    /**
     * Trang cảm ơn
     * Tương đương @GetMapping("/thanks")
     */
    public function thanks()
    {
        requireLogin();

        $this->view('client/order/thanks', [
            'pageTitle' => 'Đặt hàng thành công - LaptopShop'
        ]);
    }

    /**
     * Lịch sử đơn hàng
     * Tương đương @GetMapping("/order-history")
     */
    public function orderHistory()
    {
        requireLogin();

        $userId = Auth::id();
        $page = (int)($this->query('page', 1));

        $result = $this->orderModel->findByUserId($userId, $page, 10);

        $this->view('client/order/history', [
            'orders' => $result['data'],
            'currentPage' => $result['current_page'],
            'totalPages' => $result['total_pages'],
            'pageTitle' => 'Lịch sử đơn hàng - LaptopShop'
        ]);
    }

    /**
     * Hiển thị chi tiết đơn hàng cho client
     * GET /order/{id}
     */
    public function showOrder($orderId)
    {
        requireLogin();

        $userId = Auth::id();
        $order = $this->orderModel->findByIdWithDetails($orderId);

        if (! $order) {
            http_response_code(404);
            $this->view('errors/404', []);
            return;
        }

        // Kiểm tra quyền: chỉ owner mới xem được
        if ($order['user_id'] != $userId) {
            http_response_code(403);
            $this->view('errors/403', []);
            return;
        }

        $this->view('client/order/detail', [
            'order' => $order,
            'pageTitle' => 'Chi tiết đơn hàng #' . $orderId
        ]);
    }
}
