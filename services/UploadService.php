<?php

/**
 * Upload Service
 * Tương đương UploadService.java
 */

class UploadService
{

    /**
     * Upload file
     * Tương đương handleSaveUploadFile() trong Java
     */
    public function handleSaveUploadFile($file, $folder = 'images')
    {
        // Kiểm tra file hợp lệ
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Map folder sang đúng thư mục
        $folderMap = [
            'products' => 'public/image/product',
            'avatars' => 'public/image/avatar'
        ];

        $targetFolder = $folderMap[$folder] ?? 'uploads/' . $folder;

        // Tạo thư mục nếu chưa tồn tại
        $uploadDir = __DIR__ . '/../' . $targetFolder . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Tạo tên file unique
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = time() . '_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $fileName;

        // Validate file type
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        if (!in_array(strtolower($extension), $allowedTypes)) {
            return null;
        }

        // Validate file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }

        return null;
    }

    /**
     * Xóa file
     */
    public function deleteFile($fileName, $folder = 'images')
    {
        // Map folder sang đúng thư mục
        $folderMap = [
            'products' => 'public/image/product',
            'avatars' => 'public/image/avatar'
        ];

        $targetFolder = $folderMap[$folder] ?? 'uploads/' . $folder;

        $filePath = __DIR__ . '/../' . $targetFolder . '/' . $fileName;
        if (file_exists($filePath)) {
            return unlink($filePath);
        }
        return false;
    }
}
