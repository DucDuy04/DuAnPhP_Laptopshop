<?php

/**
 * Admin Dashboard Controller
 * Tương đương DashboardController.java
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

    public function __construct()
    {
        requireAdmin();
        $this->userModel = new User();
        $this->productModel = new Product();
        $this->orderModel = new Order();
    }

    /**
     * Admin Dashboard
     * Tương đương @GetMapping("/admin")
     */
    public function index()
    {
        // Thống kê
        $stats = [
            'totalUsers' => $this->userModel->count(),
            'totalProducts' => $this->productModel->count(),
            'totalOrders' => $this->orderModel->count(),
            'totalRevenue' => $this->orderModel->getTotalRevenue(),
            'pendingOrders' => $this->orderModel->countByStatus(Order::STATUS_PENDING),
            'shippingOrders' => $this->orderModel->countByStatus(Order::STATUS_SHIPPING),
            'completedOrders' => $this->orderModel->countByStatus(Order::STATUS_COMPLETE),
        ];

        $this->view('admin/dashboard/index', [
            'stats' => $stats,
            'pageTitle' => 'Dashboard - Admin'
        ]);
    }
}
