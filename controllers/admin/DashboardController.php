<?php

/**
Kiểm tra quyền admin (requireAdmin())
Lấy dữ liệu thống kê (Users, Products, Orders)
Truyền dữ liệu ra view hiển thị dashboard
 * 
 */

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../includes/auth.php';

class DashboardController extends Controller
{

    private $userModel;
    private $productModel;
    private $orderModel;

    //Dendency Injection
    public function __construct()
    {
        requireAdmin(); //Kiểm tra quyền admin
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }

   
    public function index()
    {
        // Thống kê
        $stats = [
            'totalUsers' => $this->userModel->count(), 
            'totalProducts' => $this->productModel->count(), 
            'totalOrders' => $this->orderModel->count(), 
            'totalRevenue' => $this->orderModel->getTotalRevenue(),
            'pendingOrders' => $this->orderModel->countByStatus(Order::STATUS_PENDING), //đếm đơn hàng đang chờ xử lý
            'shippingOrders' => $this->orderModel->countByStatus(Order::STATUS_SHIPPING),
            'completedOrders' => $this->orderModel->countByStatus(Order::STATUS_COMPLETE),
        ];

        $this->view('admin/dashboard/index', [ //truyền dữ liệu ra view
            'stats' => $stats,
            'pageTitle' => 'Dashboard - Admin' 
        ]);
    }
}
