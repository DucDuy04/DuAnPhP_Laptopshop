<?php

//Client routes

// Home
$router->get('/', 'HomeController@index');

// Authentication
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->post('/logout', 'AuthController@logout');
$router->get('/access-denied', 'AuthController@accessDenied');

// Products (Client)
$router->get('/products', 'ProductController@index');
$router->get('/product/{id}', 'ProductController@show');

// Cart
$router->get('/cart', 'CartController@index');
$router->post('/add-product-to-cart/{id}', 'CartController@addToCart');
$router->post('/delete-cart-product/{id}', 'CartController@removeFromCart');
$router->post('/cart/update', 'CartController@update');

// Checkout & Orders
$router->get('/checkout', 'CheckoutController@index');
$router->post('/place-order', 'CheckoutController@placeOrder');
$router->get('/thanks', 'CheckoutController@thanks');
$router->get('/order-history', 'CheckoutController@orderHistory');
// Order detail (client)
$router->get('/order/{id}', 'CheckoutController@showOrder');


//Admin routes

// Dashboard
$router->get('/admin', 'admin/DashboardController@index');

// User Management
$router->get('/admin/user', 'admin/UserController@index');
$router->get('/admin/user/{id}', 'admin/UserController@show');
$router->get('/admin/user/create', 'admin/UserController@create');
$router->post('/admin/user/create', 'admin/UserController@store');
$router->get('/admin/user/update/{id}', 'admin/UserController@edit');
$router->post('/admin/user/update/{id}', 'admin/UserController@update');
$router->get('/admin/user/delete/{id}', 'admin/UserController@confirmDelete');
$router->post('/admin/user/delete/{id}', 'admin/UserController@delete');

// Product Management
$router->get('/admin/product', 'admin/ProductController@index');
$router->get('/admin/product/{id}', 'admin/ProductController@show');
$router->get('/admin/product/create', 'admin/ProductController@create');
$router->post('/admin/product/create', 'admin/ProductController@store');
$router->get('/admin/product/update/{id}', 'admin/ProductController@edit');
$router->post('/admin/product/update/{id}', 'admin/ProductController@update');
$router->get('/admin/product/delete/{id}', 'admin/ProductController@confirmDelete');
$router->post('/admin/product/delete', 'admin/ProductController@delete');

// Order Management
$router->get('/admin/order', 'admin/OrderController@index');
$router->get('/admin/order/{id}', 'admin/OrderController@show');
$router->get('/admin/order/update/{id}', 'admin/OrderController@edit');
$router->post('/admin/order/update/{id}', 'admin/OrderController@update');
$router->get('/admin/order/delete/{id}', 'admin/OrderController@confirmDelete');
$router->post('/admin/order/delete/{id}', 'admin/OrderController@delete');
