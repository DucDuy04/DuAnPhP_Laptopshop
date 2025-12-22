<?php

/**
Hàm hỗ trợ hỏi logic chung như format dữ liệu, kiểm tra trạng thái
 */

// Hàm escape để tránh Cross-Site Scripting
function e($string)
{
    return htmlspecialchars($string ??  '', ENT_QUOTES, 'UTF-8');
}

function formatMoney($amount)
{
    return number_format($amount, 0, ',', '. ') . ' đ';
}


function formatDate($date, $format = 'd/m/Y H:i')
{
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

// Hàm tạo CSRF token
function csrfField()
{
    return '<input type="hidden" name="_csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">';
}

// Hàm tạo đường dẫn tới thư mục public
function asset($path)
{
    return '/public/' . ltrim($path, '/');
}


// Hàm tạo URL tuyệt đối
function url($path = '')
{
    return '/' . ltrim($path, '/');
}


// Hàm debug dữ liệu và dừng chương trình
function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}
