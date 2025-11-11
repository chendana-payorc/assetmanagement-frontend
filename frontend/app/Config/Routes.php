<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'AdminController::dashboard');
$routes->get('login', 'AdminController::login');
$routes->post('login', 'AdminController::loginPost');
$routes->get('register', 'AdminController::register');
$routes->post('register', 'AdminController::registerPost');
$routes->get('logout', 'AdminController::logout');

$routes->get('/users-list', 'UserController::index');
$routes->post('/user-store', 'UserController::store');
$routes->post('/user-edit', 'UserController::editRecord');
$routes->post('/user-update/(:any)', 'UserController::update/$1');
$routes->delete('/user-delete/(:any)', 'UserController::delete/$1');
$routes->get('/designations', 'UserController::getDesignations');
$routes->get('/departments', 'UserController::getDepartments');

$routes->get('/asset-list', 'AssetController::index');
$routes->post('/asset-store', 'AssetController::store');
$routes->post('/asset-update/(:any)', 'AssetController::update/$1');
$routes->delete('/asset-delete/(:any)', 'AssetController::delete/$1');

$routes->get('department-list', 'DepartmentController::index');
$routes->post('/department-store', 'DepartmentController::store');
$routes->post('department-update/(:any)', 'DepartmentController::update/$1');
$routes->delete('department-delete/(:any)', 'DepartmentController::delete/$1');

$routes->get('/designation-list', 'DesignationController::index');
$routes->post('/designation-store', 'DesignationController::store');
$routes->post('/designation-update/(:any)', 'DesignationController::update/$1');
$routes->delete('/designation-delete/(:any)', 'DesignationController::delete/$1');



// $routes->group('admin', function($routes) {


// });
