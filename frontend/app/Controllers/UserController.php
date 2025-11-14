<?php

namespace App\Controllers;
use CodeIgniter\Controller;

class UserController extends Controller
{
    public function index()
    {
        $token = session()->get('admin_token');
        if (!$token) {
            return redirect()->to('/login')->with('error', 'Please login first');
        }
        $client = getApiClient();
        $baseUrl = getApiBaseUrl();
        $headers = getApiHeaders();

        try {
            $response = $client->get($baseUrl . '/getAllAdmins', [
                'headers' => $headers,
            ]);

            $result = json_decode($response->getBody(), true);
            $users = $result['data'] ?? [];
        } catch (\Exception $e) {
            $users = [];
        }

        return view('frontend/user/users-index', compact('users'));
    }

    public function getDesignations()
    {
        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->get('http://localhost:3000/api/designation/list', [
                'headers' => $headers
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON([
                'success' => true,
                'data' => $result['data'] ?? $result
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getDepartments()
    {
        $client = getApiClient();
        $headers = getApiHeaders();

        try {
            $response = $client->get('http://localhost:3000/api/department/list', [
                'headers' => $headers
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON([
                'success' => true,
                'data' => $result['data'] ?? $result
            ]);

        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store()
    {
        $client = getApiClient();
        $baseUrl = getApiBaseUrl();
        $headers = getApiHeaders();
        
        $data = array_filter([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'designation_id' => $this->request->getPost('designation_id') ?: null,
            'department_id' => $this->request->getPost('department_id') ?: null,
            'country_code' => $this->request->getPost('country_code') ?: null,
            'phone_number' => $this->request->getPost('phone_number') ?: null,
        ], fn($v) => $v !== null && $v !== '');
        //log_message('debug',  $baseUrl); 
        try {
            $response = $client->post($baseUrl . '/register', [
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
        $baseUrl = getApiBaseUrl();
        $headers = getApiHeaders();

        $data = array_filter([
            'name' => $this->request->getPost('name'),
            'email' => $this->request->getPost('email'),
            'designation_id' => $this->request->getPost('designation_id') ?: null,
            'department_id' => $this->request->getPost('department_id') ?: null,
            'country_code' => $this->request->getPost('country_code') ?: null,
            'phone_number' => $this->request->getPost('phone_number') ?: null,
            'password' => $this->request->getPost('password') ?: null,
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
        $baseUrl = getApiBaseUrl();
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
        $baseUrl = getApiBaseUrl();
        $headers = getApiHeaders();

        try {
            $response = $client->get($baseUrl . '/' . $encryptedId, [
                'headers' => $headers
            ]);

            $result = json_decode($response->getBody(), true);
            $user = $result['data'] ?? null;

            if (!$user) {
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

        return view('frontend/user/edit-form', compact('user', 'departments', 'designations'));
    }
}
