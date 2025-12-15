<?php

/**
 * Admin Product Controller
 * Tương đương ProductController.java (admin)
 */

require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../validators/ProductValidator.php';
require_once __DIR__ . '/../../services/UploadService.php';
require_once __DIR__ . '/../../includes/auth.php';

class ProductController extends Controller
{

    private $productModel;
    private $validator;
    private $uploadService;

    public function __construct()
    {
        requireAdmin();
        $this->productModel = new Product();
        $this->validator = new ProductValidator();
        $this->uploadService = new UploadService();
    }

    /**
     * Danh sách products
     * Tương đương @GetMapping("/admin/product")
     */
    public function index()
    {
        $page = (int)($this->query('page', 1));
        $result = $this->productModel->findAllPaginated($page, 5);

        $this->view('admin/product/show', [
            'products' => $result['data'],
            'currentPage' => $result['current_page'],
            'totalPages' => $result['total_pages'],
            'pageTitle' => 'Quản lý Sản phẩm - Admin'
        ]);
    }

    /**
     * Chi tiết product
     * Tương đương @GetMapping("/admin/product/{id}")
     */
    public function show($id)
    {
        $product = $this->productModel->findById($id);

        if (!$product) {
            $this->redirect('/admin/product');
            return;
        }

        $this->view('admin/product/detail', [
            'product' => $product,
            'pageTitle' => 'Chi tiết Sản phẩm - Admin'
        ]);
    }

    /**
     * Form tạo product
     * Tương đương @GetMapping("/admin/product/create")
     */
    public function create()
    {
        $this->view('admin/product/create', [
            'pageTitle' => 'Tạo Sản phẩm - Admin'
        ]);
    }

    /**
     * Xử lý tạo product
     * Tương đương @PostMapping("/admin/product/create")
     */
    public function store()
    {
        // Validate
        if (!$this->validator->validate($_POST)) {
            Session::setErrors($this->validator->getErrors());
            Session::setOldInput($_POST);
            $this->redirect('/admin/product/create');
            return;
        }

        // Upload image
        $image = null;
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadService->handleSaveUploadFile($_FILES['image'], 'products');
        }

        // Tạo product
        $this->productModel->create([
            'name' => trim($_POST['name']),
            'price' => (float)$_POST['price'],
            'detail_desc' => $_POST['detailDesc'],
            'short_desc' => trim($_POST['shortDesc']),
            'quantity' => (int)$_POST['quantity'],
            'sold' => 0,
            'factory' => $_POST['factory'] ?? null,
            'target' => $_POST['target'] ?? null,
            'image' => $image
        ]);

        Session::clearValidation();
        Session::flash('success', 'Tạo sản phẩm thành công!');
        $this->redirect('/admin/product');
    }

    /**
     * Form cập nhật product
     * Tương đương @GetMapping("/admin/product/update/{id}")
     */
    public function edit($id)
    {
        $product = $this->productModel->findById($id);

        if (!$product) {
            $this->redirect('/admin/product');
            return;
        }

        $this->view('admin/product/update', [
            'product' => $product,
            'pageTitle' => 'Cập nhật Sản phẩm - Admin'
        ]);
    }

    /**
     * Xử lý cập nhật product
     * Tương đương @PostMapping("/admin/product/update/{id}")
     */
    public function update($id)
    {
        // Validate
        if (!$this->validator->validate($_POST, true)) {
            Session::setErrors($this->validator->getErrors());
            Session::setOldInput($_POST);
            $this->redirect('/admin/product/update/' . $id);
            return;
        }

        $product = $this->productModel->findById($id);
        if (!$product) {
            $this->redirect('/admin/product');
            return;
        }

        // Chuẩn bị data update
        $data = [
            'name' => trim($_POST['name']),
            'price' => (float)$_POST['price'],
            'detail_desc' => $_POST['detailDesc'],
            'short_desc' => trim($_POST['shortDesc']),
            'quantity' => (int)$_POST['quantity'],
            'factory' => $_POST['factory'] ?? null,
            'target' => $_POST['target'] ?? null
        ];

        // Upload image mới
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $image = $this->uploadService->handleSaveUploadFile($_FILES['image'], 'products');
            if ($image) {
                // Xóa image cũ
                if ($product['image']) {
                    $this->uploadService->deleteFile($product['image'], 'products');
                }
                $data['image'] = $image;
            }
        }

        $this->productModel->update($id, $data);

        Session::clearValidation();
        Session::flash('success', 'Cập nhật sản phẩm thành công!');
        $this->redirect('/admin/product');
    }

    /**
     * Form xác nhận xóa
     * Tương đương @GetMapping("/admin/product/delete/{id}")
     */
    public function confirmDelete($id)
    {
        $product = $this->productModel->findById($id);

        if (!$product) {
            $this->redirect('/admin/product');
            return;
        }

        $this->view('admin/product/delete', [
            'product' => $product,
            'pageTitle' => 'Xóa Sản phẩm - Admin'
        ]);
    }

    /**
     * Xử lý xóa product
     * Tương đương @PostMapping("/admin/product/delete")
     */
    public function delete()
    {
        $id = $this->input('id');
        $product = $this->productModel->findById($id);

        if ($product) {
            // Xóa image
            if ($product['image']) {
                $this->uploadService->deleteFile($product['image'], 'products');
            }
            $this->productModel->delete($id);
            Session::flash('success', 'Xóa sản phẩm thành công!');
        }

        $this->redirect('/admin/product');
    }
}
