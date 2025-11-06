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
$routes->get('/users-create', 'UserController::create');
$routes->get('/users-edit', 'UserController::edit');

$routes->get('/department-list', 'DepartmentController::index');
$routes->get('/department-create', 'DepartmentController::create');
$routes->get('/department-edit', 'DepartmentController::edit');

$routes->get('/designation-list', 'DesignationController::index');
$routes->get('/designation-create', 'DesignationController::create');
$routes->get('/designation-edit', 'DesignationController::edit');


// $routes->group('admin', function($routes) {


// });
