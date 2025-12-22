<?php

/**
Thực hiện các thao tác với Order và OrderDetail:
Xem danh sách đơn hàng
Xem chi tiết một đơn hàng
Cập nhật trạng thái
Xác nhận xóa và xóa đơn hàng
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

    //Danh sách đơn hàng
    public function index()
    {
        $page = (int)($this->query('page', 1)); //Lấy tham số page từ URL, mặc định là 1
        $result = $this->orderModel->findAllWithUser($page, 5); //Lấy danh sách đơn hàng kèm thông tin user, phân trang 5 đơn mỗi trang

        $this->view('admin/order/show', [
            'orders' => $result['data'],
            'currentPage' => $result['current_page'],
            'totalPages' => $result['total_pages'],
            'pageTitle' => 'Quản lý Đơn hàng - Admin'
        ]);
    }


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


    public function update($id)
    {
        $status = $this->input('status'); //Lấy trạng thái mới từ form

        $order = $this->orderModel->findById($id); //Kiểm tra đơn hàng có tồn tại không
        if ($order) {
            $this->orderModel->updateStatus($id, $status);
            Session::flash('success', 'Cập nhật trạng thái đơn hàng thành công!'); //Hiển thị thông báo thành công
        }

        $this->redirect('/admin/order');
    }


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
