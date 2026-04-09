<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('items/generate-qr/(:segment)', 'Items::generateQr/$1');

service('auth')->routes($routes);
