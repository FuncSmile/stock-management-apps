<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('dashboard', 'Home::index');

$routes->group('items', function($routes) {
    $routes->get('/', 'Items::index');
    $routes->get('new', 'Items::new');
    $routes->post('create', 'Items::create');
    $routes->get('edit/(:segment)', 'Items::edit/$1');
    $routes->post('update/(:segment)', 'Items::update/$1');
    $routes->get('delete/(:segment)', 'Items::delete/$1');
    $routes->get('generate-qr/(:segment)', 'Items::generateQr/$1');
});

$routes->get('scan', 'Scan::index');

$routes->group('api', function($routes) {
    $routes->get('items/info/(:segment)', 'Items::info/$1');
    $routes->post('stock/batch-update', 'Items::batchUpdate'); // Placeholder for Issue #6
});

// Custom Auth Routes
$routes->get('login', '\App\Controllers\Auth\LoginController::loginView');
$routes->post('login', '\App\Controllers\Auth\LoginController::loginAction');
$routes->get('logout', '\App\Controllers\Auth\LoginController::logoutAction');

service('auth')->routes($routes);
