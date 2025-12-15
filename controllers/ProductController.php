<?php

/**
 * Product Controller (Client)
 * Tương đương ItemController.java - phần product
 */

require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';

class ProductController extends Controller
{

    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }

    /**
     * Danh sách sản phẩm với filter
     * Tương đương @GetMapping("/products")
     */
    public function index()
    {
        $page = (int)($this->query('page', 1));
        $perPage = 6; // Hiển thị 6 sản phẩm mỗi trang

        // Lấy filters từ query string
        $filters = [
            'factory' => $this->query('factory'),
            'target' => $this->query('target'),
            'min_price' => $this->query('min_price'),
            'max_price' => $this->query('max_price'),
            'sort' => $this->query('sort'),
            'keyword' => $this->query('keyword')
        ];

        // Lọc bỏ các giá trị rỗng
        $filters = array_filter($filters);

        // Lấy sản phẩm
        $result = $this->productModel->findWithFilters($filters, $page, $perPage);

        // Lấy danh sách factories và targets cho sidebar
        $factories = $this->productModel->getFactories();
        $targets = $this->productModel->getTargets();

        $this->view('client/product/show', [
            'products' => $result['data'],
            'currentPage' => $result['current_page'],
            'totalPages' => $result['total_pages'],
            'total' => $result['total'],
            'factories' => $factories,
            'targets' => $targets,
            'filters' => $filters,
            'pageTitle' => 'Sản phẩm - LaptopShop'
        ]);
    }

    /**
     * Chi tiết sản phẩm
     * Tương đương @GetMapping("/product/{id}")
     */
    public function show($id)
    {
        $product = $this->productModel->findById($id);

        if (!$product) {
            $this->view('errors/404');
            return;
        }

        // Lấy sản phẩm liên quan (cùng factory)
        $relatedProducts = $this->productModel->findByFactory($product['factory']);
        // Loại bỏ sản phẩm hiện tại
        $relatedProducts = array_filter($relatedProducts, fn($p) => $p['id'] != $id);
        $relatedProducts = array_slice($relatedProducts, 0, 4);

        $this->view('client/product/detail', [
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'pageTitle' => $product['name'] . ' - LaptopShop'
        ]);
    }
}
