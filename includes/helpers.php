<?php

/**
 * Helper Functions
 */

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

function csrfField()
{
    return '<input type="hidden" name="_csrf_token" value="' . ($_SESSION['csrf_token'] ?? '') . '">';
}

/**
 * Asset URL - cho PHP built-in server
 */
function asset($path)
{
    return '/public/' . ltrim($path, '/');
}

/**
 * URL helper - cho PHP built-in server
 */
function url($path = '')
{
    return '/' . ltrim($path, '/');
}

function redirect($path)
{
    header("Location: " . url($path));
    exit;
}

function old($key, $default = '')
{
    return $_SESSION['old_input'][$key] ??  $default;
}

function error($key)
{
    return $_SESSION['errors'][$key] ?? null;
}

function hasError($key)
{
    return isset($_SESSION['errors'][$key]);
}

function clearValidation()
{
    unset($_SESSION['old_input']);
    unset($_SESSION['errors']);
}

function flash($key)
{
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}

function setFlash($key, $message)
{
    $_SESSION['flash'][$key] = $message;
}

function dd($data)
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
    die();
}
