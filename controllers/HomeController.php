<?php


require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/Product.php';

class HomeController extends Controller
{

    private $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
    }


    public function index()
    {
        // Lấy 10 sản phẩm mới nhất
        $result = $this->productModel->findAllPaginated(1, 10);

        $this->view('client/homepage/show', [
            'products' => $result['data'],
            'pageTitle' => 'Trang chủ - LaptopShop'
        ]);
    }
}
