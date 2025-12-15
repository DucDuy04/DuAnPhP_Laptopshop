<?php
/**
 * Product Validator
 * Tương đương @Valid + validation annotations trong Product. java
 */

class ProductValidator
{

    private $errors = [];

    /**
     * Validate dữ liệu tạo/cập nhật product
     */
    public function validate($data, $isUpdate = false)
    {
        $this->errors = [];

        // Name - @NotEmpty
        if (empty($data['name'])) {
            $this->errors['name'] = 'Tên sản phẩm không được để trống';
        }

        // Price - @DecimalMin(value = "0", inclusive = false)
        if (!isset($data['price']) || $data['price'] === '') {
            $this->errors['price'] = 'Giá không được để trống';
        } elseif (!is_numeric($data['price']) || $data['price'] <= 0) {
            $this->errors['price'] = 'Giá phải lớn hơn 0';
        }

        // Detail Description - @NotEmpty
        if (empty($data['detailDesc'])) {
            $this->errors['detailDesc'] = 'Mô tả chi tiết không được để trống';
        }

        // Short Description - @NotEmpty
        if (empty($data['shortDesc'])) {
            $this->errors['shortDesc'] = 'Mô tả ngắn không được để trống';
        }

        // Quantity - @Min(value = 1)
        if (!isset($data['quantity']) || $data['quantity'] === '') {
            $this->errors['quantity'] = 'Số lượng không được để trống';
        } elseif (!is_numeric($data['quantity']) || $data['quantity'] < 1) {
            $this->errors['quantity'] = 'Số lượng phải lớn hơn hoặc bằng 1';
        }

        // Factory (optional but should be from list)
        $validFactories = ['APPLE', 'ASUS', 'DELL', 'HP', 'LENOVO', 'ACER', 'MSI', 'LG'];
        if (! empty($data['factory']) && !in_array($data['factory'], $validFactories)) {
            $this->errors['factory'] = 'Hãng sản xuất không hợp lệ';
        }

        // Target (optional but should be from list)
        $validTargets = ['gaming', 'van-phong', 'thiet-ke-do-hoa', 'hoc-tap', 'mong-nhe'];
        if (!empty($data['target']) && !in_array($data['target'], $validTargets)) {
            $this->errors['target'] = 'Mục đích sử dụng không hợp lệ';
        }

        // Image (required for create, optional for update)
        if (!$isUpdate) {
            if (empty($_FILES['image']) || $_FILES['image']['error'] === UPLOAD_ERR_NO_FILE) {
                $this->errors['image'] = 'Vui lòng chọn hình ảnh sản phẩm';
            }
        }

        // Validate image file if uploaded
        if (!empty($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $this->validateImage($_FILES['image']);
        }

        return empty($this->errors);
    }

    /**
     * Validate file hình ảnh
     */
    private function validateImage($file)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024; // 5MB

        // Check MIME type
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $this->errors['image'] = 'Chỉ chấp nhận file JPG, PNG, GIF hoặc WebP';
            return;
        }

        // Check size
        if ($file['size'] > $maxSize) {
            $this->errors['image'] = 'Kích thước file không được vượt quá 5MB';
        }
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
