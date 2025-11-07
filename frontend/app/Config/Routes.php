<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('login', 'AdminController::login');
$routes->post('login', 'AdminController::loginPost');
$routes->get('register', 'AdminController::register');
$routes->post('register', 'AdminController::registerPost');
$routes->get('logout', 'AdminController::logout');

$routes->get('/users-list', 'UserController::index');

$routes->get('department-list', 'DepartmentController::index');
$routes->post('/department-store', 'DepartmentController::store');
$routes->post('department-update/(:num)', 'DepartmentController::update/$1');
$routes->delete('department-delete/(:num)', 'DepartmentController::delete/$1');

$routes->get('/designation-list', 'DesignationController::index');
$routes->post('/designation-store', 'DesignationController::store');
$routes->post('/designation-update/(:num)', 'DesignationController::update/$1');
$routes->delete('/designation-delete/(:num)', 'DesignationController::delete/$1');



// $routes->group('admin', function($routes) {


// });
