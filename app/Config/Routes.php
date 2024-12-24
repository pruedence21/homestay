<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Auth::login');
$routes->get('auth/login', 'Auth::login');
$routes->post('auth/authenticate', 'Auth::authenticate');
$routes->get('auth/logout', 'Auth::logout');

// Protected routes
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Admin\Dashboard::index');
    $routes->get('kamar', 'Admin\Kamar::index');
    $routes->post('kamar/store', 'Admin\Kamar::store');
    $routes->post('kamar/update/(:num)', 'Admin\Kamar::update/$1');
    $routes->get('kamar/delete/(:num)', 'Admin\Kamar::delete/$1');
    $routes->get('kamar/tersedia', 'Admin\Kamar::getKamarTersedia');
    $routes->get('booking', 'Admin\Booking::index');
    $routes->post('booking/store', 'Admin\Booking::store');
    $routes->post('booking/check-availability', 'Admin\Booking::checkAvailability');
    $routes->post('booking/status/(:num)', 'Admin\Booking::updateStatus/$1');
    $routes->get('layanan', 'Admin\Layanan::index');
    $routes->post('layanan/store', 'Admin\Layanan::store');
    $routes->post('layanan/ubah/(:num)', 'Admin\Layanan::update/$1');
    $routes->get('layanan/hapus/(:num)', 'Admin\Layanan::delete/$1');
});

$routes->group('kasir', ['filter' => 'auth'], function($routes) {
    $routes->get('dashboard', 'Kasir\Dashboard::index');
});