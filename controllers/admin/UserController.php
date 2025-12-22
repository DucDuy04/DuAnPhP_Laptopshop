<?php


require_once __DIR__ . '/../../core/Controller.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/Role.php';
require_once __DIR__ . '/../../validators/UserValidator.php';
require_once __DIR__ . '/../../services/UploadService.php';
require_once __DIR__ . '/../../includes/auth.php';

class UserController extends Controller
{

    private $userModel;
    private $roleModel;
    private $validator;
    private $uploadService;

    public function __construct()
    {
        requireAdmin();
        $this->userModel = new User();
        $this->roleModel = new Role();
        $this->validator = new UserValidator();
        $this->uploadService = new UploadService();
    }

    //Danh sách user
    public function index()
    {
        $page = (int)($this->query('page', 1));
        $result = $this->userModel->findAllWithRole($page, 5);

        $this->view('admin/user/show', [
            'users' => $result['data'],
            'currentPage' => $result['current_page'],
            'totalPages' => $result['total_pages'],
            'pageTitle' => 'Quản lý Users - Admin'
        ]);
    }

    public function show($id)
    {
        $user = $this->userModel->findByIdWithRole($id);

        if (!$user) {
            $this->redirect('/admin/user');
            return;
        }

        $this->view('admin/user/detail', [
            'user' => $user,
            'pageTitle' => 'Chi tiết User - Admin'
        ]);
    }

    public function create()
    {
        $roles = $this->roleModel->findAll();

        $this->view('admin/user/create', [
            'roles' => $roles,
            'pageTitle' => 'Tạo User - Admin'
        ]);
    }

    // Xử lý tạo user
    public function store()
    {
        // Validate
        if (!$this->validator->validateCreate($_POST)) {
            Session::setErrors($this->validator->getErrors());
            Session::setOldInput($_POST);
            $this->redirect('/admin/user/create');
            return;
        }

        // Upload avatar
        $avatar = null;
        if (! empty($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatar = $this->uploadService->handleSaveUploadFile($_FILES['avatar'], 'avatars');
        }

        // Tạo user
        $this->userModel->createUser([
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'fullName' => trim($_POST['fullName']),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ??  ''),
            'avatar' => $avatar,
            'role_id' => $_POST['role_id']
        ]);

        Session::clearValidation();
        Session::flash('success', 'Tạo user thành công!');
        $this->redirect('/admin/user');
    }

    // Form cập nhật user
    public function edit($id)
    {
        $user = $this->userModel->findByIdWithRole($id);
        $roles = $this->roleModel->findAll();

        if (!$user) {
            $this->redirect('/admin/user');
            return;
        }

        $this->view('admin/user/update', [
            'user' => $user,
            'roles' => $roles,
            'pageTitle' => 'Cập nhật User - Admin'
        ]);
    }

    // Xử lý cập nhật user
    public function update($id)
    {
        // Validate
        if (!$this->validator->validateUpdate($_POST, $id)) {
            Session::setErrors($this->validator->getErrors());
            Session::setOldInput($_POST);
            $this->redirect('/admin/user/update/' . $id);
            return;
        }

        $user = $this->userModel->findById($id);
        if (!$user) {
            $this->redirect('/admin/user');
            return;
        }

        // Chuẩn bị data update
        $data = [
            'email' => trim($_POST['email']),
            'fullName' => trim($_POST['fullName']),
            'phone' => trim($_POST['phone'] ?? ''),
            'address' => trim($_POST['address'] ?? ''),
            'role_id' => $_POST['role_id']
        ];

        // Password (nếu có)
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        // Upload avatar mới
        if (!empty($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatar = $this->uploadService->handleSaveUploadFile($_FILES['avatar'], 'avatars');
            if ($avatar) {
                // Xóa avatar cũ
                if ($user['avatar']) {
                    $this->uploadService->deleteFile($user['avatar'], 'avatars');
                }
                $data['avatar'] = $avatar;
            }
        }

        $this->userModel->updateUser($id, $data);

        Session::clearValidation();
        Session::flash('success', 'Cập nhật user thành công!');
        $this->redirect('/admin/user');
    }


    public function confirmDelete($id)
    {
        $user = $this->userModel->findById($id);

        if (!$user) {
            $this->redirect('/admin/user');
            return;
        }

        $this->view('admin/user/delete', [
            'user' => $user,
            'pageTitle' => 'Xóa User - Admin'
        ]);
    }

    // Xử lý xóa user
    public function delete($id)
    {
        $user = $this->userModel->findById($id);

        if ($user) {
            // Xóa avatar
            if ($user['avatar']) {
                $this->uploadService->deleteFile($user['avatar'], 'avatars');
            }
            $this->userModel->delete($id);
            Session::flash('success', 'Xóa user thành công!');
        }

        $this->redirect('/admin/user');
    }
}
