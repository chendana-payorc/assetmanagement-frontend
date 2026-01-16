<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class EmployeeController extends BaseController
{
    public function index()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }

        helper('api');

        // Get page from URL
        $page = (int) $this->request->getGet('page');
        $page = $page > 0 ? $page : 1;

        // Fixed page size
        $limit = 5;
        $offset = ($page - 1) * $limit;

        $client = getApiClient();
        $baseUrl = getEmployeeApiUrl();
        $headers = getApiHeaders();

        // Build query string with filters AND pagination
        $queryParams = [
            'limit' => $limit,
            'offset' => $offset,
            'name' => $this->request->getGet('name'),
            'email' => $this->request->getGet('email'),
            'department_id' => $this->request->getGet('department_id'),
            'designation_id' => $this->request->getGet('designation_id'),
        ];

        // Remove empty parameters
        $queryParams = array_filter($queryParams, function($value) {
            return $value !== null && $value !== '';
        });

        $queryString = http_build_query($queryParams);

        try {
            $response = $client->get($baseUrl . '/list?' . $queryString, [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            $employees = $result['data'] ?? [];

            // Total employees count from backend
            $totalEmployees = $result['totalCount'] ?? 0;

            // Pagination calculations
            $totalPages = $totalEmployees > 0 ? ceil($totalEmployees / $limit) : 1;
            $page = max(1, min($page, $totalPages));
           
        } catch (\Exception $e) {
            $employees = [];
            $totalPages = 1;
        }

        return view('frontend/employee/employee-index', array_merge(
            compact('employees', 'page', 'totalPages'),
            ['organizations' => $this->organizations]
        ));
    }


    public function filterUsers()
{
    $client = getApiClient();
    $baseUrl = getEmployeeApiUrl();
    $headers = getApiHeaders();

    $query = http_build_query([
        'name' => $this->request->getGet('name'),
        'email' => $this->request->getGet('email'),
        'department_id' => $this->request->getGet('department_id'),
        'designation_id' => $this->request->getGet('designation_id')
    ]);

    try {
        $response = $client->get($baseUrl . "/list?$query", [
            'headers' => $headers,
        ]);

        $result = json_decode($response->getBody(), true);
        return $this->response->setJSON($result);

    } catch (\Exception $e) {
        return $this->response->setJSON([
            'success' => false,
            'data' => []
        ]);
    }
}


    public function store()
    {
        $client = getApiClient();
        $baseUrl = getEmployeeApiUrl();
        $headers = getApiHeaders();
        
        $data = array_filter([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'confirm_password' => $this->request->getPost('confirm_password'),
            'designation_id' => $this->request->getPost('designation_id') ?: null,
            'department_id' => $this->request->getPost('department_id') ?: null,
            'country_code' => $this->request->getPost('country_code') ?: null,
            'phone_number' => $this->request->getPost('phone_number') ?: null,
        ], fn($v) => $v !== null && $v !== '');
        //log_message('debug',  $baseUrl); 
        try {
            $response = $client->post($baseUrl . '/create', [
                'headers' => $headers,
                'form_params' => $data
            ]);

            $result = json_decode($response->getBody(), true);
            //log_message('debug',  $result); 
            return $this->response->setJSON([
                'success' => $result['success'] ?? true,
                'message' => $result['message'] ?? 'User created successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update($id)
    {
        $client = getApiClient();
        $baseUrl = getEmployeeApiUrl();
        $headers = getApiHeaders();

        $data = array_filter([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'designation_id' => $this->request->getPost('designation_id') ?: null,
            'department_id' => $this->request->getPost('department_id') ?: null,
            'country_code' => $this->request->getPost('country_code') ?: null,
            'phone_number' => $this->request->getPost('phone_number') ?: null,
            'password' => $this->request->getPost('password') ?: null,
            'confirm_password' => $this->request->getPost('confirm_password') ?:null,
        ], fn($v) => $v !== null && $v !== '');

        try {
            $headers['Content-Type'] = 'application/json';

            $response = $client->put($baseUrl . '/update/' . $id, [
                'headers' => $headers,
                'json' => $data
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON([
                'success' => $result['success'] ?? true,
                'message' => $result['message'] ?? 'User updated successfully'
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        $client = getApiClient();
        $baseUrl = getEmployeeApiUrl();
        $headers = getApiHeaders();

        try {
            $response = $client->delete($baseUrl . '/delete/' . $id, [
                'headers' => $headers
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON([
                'success' => $result['success'] ?? true,
                'message' => $result['message'] ?? 'User deleted successfully'
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    private function fetchDesignationsList(): array
    {
        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->get(getDesignationApiUrl('/list'), [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchDepartmentsList(): array
    {
        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->get(getDepartmentApiUrl('/list'), [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    public function editRecord()
    {
        $encryptedId = $this->request->getPost('id');

        if (!$encryptedId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'User ID is required'
            ]);
        }

        $client = getApiClient();
        $baseUrl = getEmployeeApiUrl();
        $headers = getApiHeaders();

        try {
            $response = $client->get($baseUrl . '/' . $encryptedId, [
                'headers' => $headers
            ]);

            $result = json_decode($response->getBody(), true);
            $employee = $result['data'] ?? null;

            if (!$employee) {
                return $this->response->setStatusCode(404)->setJSON([
                    'success' => false,
                    'error' => 'User not found'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Failed to fetch user data: ' . $e->getMessage()
            ]);
        }

        $departments = $this->fetchDepartmentsList();
        $designations = $this->fetchDesignationsList();

        return view('frontend/employee/edit-form',array_merge(
            compact('employee', 'departments', 'designations'),
            ['organizations' => $this->organizations]
        ));
    }
}
