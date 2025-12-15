<?php

/**
 * User Validator
 * Tương đương @Valid + các validation annotations trong Java
 */

require_once __DIR__ . '/../models/User.php';

class UserValidator
{

    private $errors = [];
    private $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Validate dữ liệu đăng ký
     * Tương đương @Valid RegisterDTO + RegisterValidator
     */
    public function validateRegister($data)
    {
        $this->errors = [];

        // First Name - tương đương @Size(min=3)
        if (empty($data['firstName'])) {
            $this->errors['firstName'] = 'First name không được để trống';
        } elseif (strlen($data['firstName']) < 2) {
            $this->errors['firstName'] = 'First name phải có ít nhất 2 ký tự';
        }

        // Email - tương đương @Email + @NotNull
        if (empty($data['email'])) {
            $this->errors['email'] = 'Email không được để trống';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email không hợp lệ';
        } elseif ($this->userModel->existsByEmail($data['email'])) {
            $this->errors['email'] = 'Email đã được sử dụng';
        }

        // Password - tương đương @StrongPassword
        $this->validatePassword($data['password'] ?? '', 'password');

        // Confirm Password
        if (empty($data['confirmPassword'])) {
            $this->errors['confirmPassword'] = 'Xác nhận mật khẩu không được để trống';
        } elseif ($data['password'] !== $data['confirmPassword']) {
            $this->errors['confirmPassword'] = 'Mật khẩu xác nhận không khớp';
        }

        return empty($this->errors);
    }

    /**
     * Validate dữ liệu tạo user (Admin)
     */
    public function validateCreate($data)
    {
        $this->errors = [];

        // Email
        if (empty($data['email'])) {
            $this->errors['email'] = 'Email không được để trống';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email không hợp lệ';
        } elseif ($this->userModel->existsByEmail($data['email'])) {
            $this->errors['email'] = 'Email đã được sử dụng';
        }

        // Password
        $this->validatePassword($data['password'] ?? '', 'password');

        // Full Name - tương đương @Size(min=3)
        if (empty($data['fullName'])) {
            $this->errors['fullName'] = 'Họ tên không được để trống';
        } elseif (strlen($data['fullName']) < 3) {
            $this->errors['fullName'] = 'Họ tên phải có ít nhất 3 ký tự';
        }

        // Phone (optional)
        if (!empty($data['phone']) && ! preg_match('/^[0-9]{10,11}$/', $data['phone'])) {
            $this->errors['phone'] = 'Số điện thoại không hợp lệ';
        }

        return empty($this->errors);
    }

    /**
     * Validate dữ liệu cập nhật user
     */
    public function validateUpdate($data, $userId)
    {
        $this->errors = [];

        // Email (kiểm tra trùng với user khác)
        if (empty($data['email'])) {
            $this->errors['email'] = 'Email không được để trống';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = 'Email không hợp lệ';
        } else {
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $userId) {
                $this->errors['email'] = 'Email đã được sử dụng';
            }
        }

        // Password (optional khi update)
        if (!empty($data['password'])) {
            $this->validatePassword($data['password'], 'password');
        }

        // Full Name
        if (empty($data['fullName'])) {
            $this->errors['fullName'] = 'Họ tên không được để trống';
        } elseif (strlen($data['fullName']) < 3) {
            $this->errors['fullName'] = 'Họ tên phải có ít nhất 3 ký tự';
        }

        // Phone
        if (!empty($data['phone']) && !preg_match('/^[0-9]{10,11}$/', $data['phone'])) {
            $this->errors['phone'] = 'Số điện thoại không hợp lệ';
        }

        return empty($this->errors);
    }

    /**
     * Validate password
     * Tương đương @StrongPassword annotation
     */
    private function validatePassword($password, $field)
    {
        if (empty($password)) {
            $this->errors[$field] = 'Mật khẩu không được để trống';
            return;
        }

        if (strlen($password) < 6) {
            $this->errors[$field] = 'Mật khẩu phải có ít nhất 6 ký tự';
            return;
        }

        // Strong password check (optional - uncomment nếu cần)
        // if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $password)) {
        //     $this->errors[$field] = 'Mật khẩu phải chứa chữ hoa, chữ thường, số và ký tự đặc biệt';
        // }
    }

    /**
     * Validate đăng nhập
     */
    public function validateLogin($data)
    {
        $this->errors = [];

        if (empty($data['email'])) {
            $this->errors['email'] = 'Email không được để trống';
        }

        if (empty($data['password'])) {
            $this->errors['password'] = 'Mật khẩu không được để trống';
        }

        return empty($this->errors);
    }

    /**
     * Lấy tất cả errors
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * Lấy error của một field
     */
    public function getError($field)
    {
        return $this->errors[$field] ?? null;
    }

    /**
     * Kiểm tra có error không
     */
    public function hasErrors()
    {
        return !empty($this->errors);
    }
}
