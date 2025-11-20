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
$routes->get('/filter-users', 'UserController::filterUsers');

$routes->get('/employee-list', 'EmployeeController::index');
$routes->post('/employee-store', 'EmployeeController::store');
$routes->post('/employee-edit', 'EmployeeController::editRecord');
$routes->post('/employee-update/(:any)', 'EmployeeController::update/$1');
$routes->delete('/employee-delete/(:any)', 'EmployeeController::delete/$1');
$routes->get('/filter-employee', 'EmployeeController::filterUsers');


$routes->get('/asset-list', 'AssetController::index');
$routes->post('/asset-store', 'AssetController::store');
$routes->post('/asset-edit', 'AssetController::editRecord');
$routes->post('/asset-update/(:any)', 'AssetController::update/$1');
$routes->delete('/asset-delete/(:any)', 'AssetController::delete/$1');

$routes->get('assetcategory-list', 'AssetCategoryController::index');
$routes->get('assetcategory', 'AssetCategoryController::fetch'); 
$routes->post('/assetcategory-store', 'AssetCategoryController::store');
$routes->post('/assetcategory-edit', 'AssetCategoryController::editRecord');
$routes->post('assetcategory-update/(:any)', 'AssetCategoryController::update/$1');
$routes->delete('assetcategory-delete/(:any)', 'AssetCategoryController::delete/$1');

$routes->get('department-list', 'DepartmentController::index');
$routes->get('department', 'DepartmentController::fetch'); 
$routes->post('/department-store', 'DepartmentController::store');
$routes->post('/department-edit', 'DepartmentController::editRecord');
$routes->post('department-update/(:any)', 'DepartmentController::update/$1');
$routes->delete('department-delete/(:any)', 'DepartmentController::delete/$1');

$routes->get('/designation-list', 'DesignationController::index');
$routes->post('/designation-store', 'DesignationController::store');
$routes->post('/designation-edit', 'DesignationController::editRecord');
$routes->post('/designation-update/(:any)', 'DesignationController::update/$1');
$routes->delete('/designation-delete/(:any)', 'DesignationController::delete/$1');

$routes->get('/organization-list', 'OrganizationController::index');
$routes->post('/organization-store', 'OrganizationController::store');
$routes->post('/organization-edit', 'OrganizationController::editRecord');
$routes->post('/organization-update/(:any)', 'OrganizationController::update/$1');
$routes->delete('/organization-delete/(:any)', 'OrganizationController::delete/$1');




// $routes->group('admin', function($routes) {


// });
