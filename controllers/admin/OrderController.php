<?php

/**
 * Admin Order Controller
 * Tương đương OrderController.java
 */

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Order.php';
require_once __DIR__ . '/../../models/OrderDetail.php';
require_once __DIR__ . '/../../includes/auth.php';

class OrderController extends Controller
{

    private $orderModel;
    private $orderDetailModel;

    public function __construct()
    {
        requireAdmin();
        $this->orderModel = new Order();
        $this->orderDetailModel = new OrderDetail();
    }

    /**
     * Danh sách orders
     * Tương đương @GetMapping("/admin/order")
     */
    public function index()
    {
        $page = (int)($this->query('page', 1));
        $result = $this->orderModel->findAllWithUser($page, 5);

        $this->view('admin/order/show', [
            'orders' => $result['data'],
            'currentPage' => $result['current_page'],
            'totalPages' => $result['total_pages'],
            'pageTitle' => 'Quản lý Đơn hàng - Admin'
        ]);
    }

    /**
     * Chi tiết order
     * Tương đương @GetMapping("/admin/order/{id}")
     */
    public function show($id)
    {
        $order = $this->orderModel->findByIdWithDetails($id);

        if (!$order) {
            $this->redirect('/admin/order');
            return;
        }

        $this->view('admin/order/detailorder', [
            'order' => $order,
            'orderdetails' => $order['order_details'],
            'pageTitle' => 'Chi tiết Đơn hàng - Admin'
        ]);
    }

    /**
     * Form cập nhật order
     * Tương đương @GetMapping("/admin/order/update/{id}")
     */
    public function edit($id)
    {
        $order = $this->orderModel->findById($id);

        if (!$order) {
            $this->redirect('/admin/order');
            return;
        }

        // Danh sách trạng thái
        $statuses = [
            Order::STATUS_PENDING => 'Chờ xử lý',
            Order::STATUS_SHIPPING => 'Đang giao hàng',
            Order::STATUS_COMPLETE => 'Hoàn thành',
            Order::STATUS_CANCELLED => 'Đã hủy'
        ];

        $this->view('admin/order/updateorder', [
            'order' => $order,
            'statuses' => $statuses,
            'pageTitle' => 'Cập nhật Đơn hàng - Admin'
        ]);
    }

    /**
     * Xử lý cập nhật order
     * Tương đương @PostMapping("/admin/order/update/{id}")
     */
    public function update($id)
    {
        $status = $this->input('status');

        $order = $this->orderModel->findById($id);
        if ($order) {
            $this->orderModel->updateStatus($id, $status);
            Session::flash('success', 'Cập nhật trạng thái đơn hàng thành công!');
        }

        $this->redirect('/admin/order');
    }

    /**
     * Form xác nhận xóa
     * Tương đương @GetMapping("/admin/order/delete/{id}")
     */
    public function confirmDelete($id)
    {
        $order = $this->orderModel->findById($id);

        if (!$order) {
            $this->redirect('/admin/order');
            return;
        }

        $this->view('admin/order/deleteOrder', [
            'order' => $order,
            'id' => $id,
            'pageTitle' => 'Xóa Đơn hàng - Admin'
        ]);
    }

    /**
     * Xử lý xóa order
     * Tương đương @PostMapping("/admin/order/delete/{id}")
     */
    public function delete($id)
    {
        // Xóa order details trước
        $this->orderDetailModel->deleteByOrderId($id);

        // Xóa order
        $this->orderModel->delete($id);

        Session::flash('success', 'Xóa đơn hàng thành công!');
        $this->redirect('/admin/order');
    }
}
