<?php


require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../validators/UserValidator.php';
require_once __DIR__ . '/../includes/auth.php';

class AuthController extends Controller
{

    private $userModel;
    private $roleModel;
    private $validator;

    public function __construct()
    {
        $this->userModel = new User();
        $this->roleModel = new Role();
        $this->validator = new UserValidator();
    }


    public function showLogin()
    {
        // Redirect nếu đã đăng nhập
        Auth::redirectIfLoggedIn();

        $this->view('client/auth/login', [
            'pageTitle' => 'Đăng nhập - LaptopShop'
        ]);
    }


    public function login()
    {
        Auth::redirectIfLoggedIn();

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validate
        if (! $this->validator->validateLogin($_POST)) {
            Session::setErrors($this->validator->getErrors());
            Session::setOldInput(['email' => $email]);
            $this->redirect('/login');
            return;
        }


        $result = Auth::login($email, $password);

        if ($result['success']) {
            Session::clearValidation();

            // Redirect theo role
            if (Auth::isAdmin()) {
                $this->redirect('/admin');
            } else {
                // Redirect về trang trước đó nếu có
                $redirectUrl = Session::get('redirect_url', '/');
                Session::remove('redirect_url');
                $this->redirect($redirectUrl);
            }
        } else {
            Session::setErrors(['login' => $result['message']]);
            Session::setOldInput(['email' => $email]);
            $this->redirect('/login');
        }
    }


    public function showRegister()
    {
        Auth::redirectIfLoggedIn();

        $this->view('client/auth/register', [
            'pageTitle' => 'Đăng ký - LaptopShop'
        ]);
    }


    public function register()
    {
        Auth::redirectIfLoggedIn();

        // Validate
        if (!$this->validator->validateRegister($_POST)) {
            Session::setErrors($this->validator->getErrors());
            Session::setOldInput($_POST);
            $this->redirect('/register');
            return;
        }

        // Lấy role USER
        $userRole = $this->roleModel->findByName('USER');

        // Tạo user mới
        $userId = $this->userModel->createUser([
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'fullName' => trim($_POST['firstName'] . ' ' . ($_POST['lastName'] ?? '')),
            'role_id' => $userRole['id']
        ]);

        if ($userId) {
            Session::clearValidation();
            Session::flash('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
            $this->redirect('/login');
        } else {
            Session::flash('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
            $this->redirect('/register');
        }
    }


    public function logout()
    {
        Auth::logout();
        $this->redirect('/login');
    }


    public function accessDenied()
    {
        $this->view('client/auth/access-denied', [
            'pageTitle' => 'Access Denied'
        ]);
    }
}
