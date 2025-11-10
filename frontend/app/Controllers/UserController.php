<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class UserController extends Controller
{
    private $apiBaseUrl;
    private $headers;

    public function __construct()
    {
        helper(['url', 'session']);
        $this->apiBaseUrl = env('API_BASE_URL') . '/admin';

        $this->headers = [
            'Accept' => 'application/json',
            'username' => env('API_USERNAME'),
            'password' => env('API_PASSWORD'),
        ];
    }

    public function index()
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
    
        $headers = [
            'Accept' => 'application/json',
            'username' => env('API_USERNAME'),
            'password' => env('API_PASSWORD'),
        ];
    
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
    
        $response = $client->get($this->apiBaseUrl . '/list', [
            'headers' => $headers,
        ]);
    
        $result = json_decode($response->getBody(), true);
        $users = $result['data'] ?? [];

        $departments = $this->fetchDepartmentsList();
        $designations = $this->fetchDesignationsList();

        $departmentMap = [];
        foreach ($departments as $department) {
            if (isset($department['id'])) {
                $departmentMap[$department['id']] = $department['name'] ?? '';
            }
        }

        $designationMap = [];
        foreach ($designations as $designation) {
            if (isset($designation['id'])) {
                $designationMap[$designation['id']] = $designation['name'] ?? '';
            }
        }

        $users = array_map(function ($user) use ($departmentMap, $designationMap) {
            $departmentId = $user['department_id'] ?? null;
            $designationId = $user['designation_id'] ?? null;

            if ($departmentId && isset($departmentMap[$departmentId])) {
                $user['department_name'] = $departmentMap[$departmentId];
            }

            if ($designationId && isset($designationMap[$designationId])) {
                $user['designation_name'] = $designationMap[$designationId];
            }

            return $user;
        }, $users);

        return view('frontend/users-index', compact('users'));
    }
   

    public function getDesignations()
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;
    
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
    
        try {
            $response = $client->get('http://localhost:3000/api/designation/list', [
                'headers' => $headers
            ]);
    
            return $this->response->setJSON(json_decode($response->getBody(), true));
    
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    public function getDepartments()
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;
    
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
    
        try {
            $response = $client->get('http://localhost:3000/api/department/list', [
                'headers' => $headers
            ]);
    
            return $this->response->setJSON(json_decode($response->getBody(), true));
    
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function store()
    {
        $name           = $this->request->getPost('name');
        $email          = $this->request->getPost('email');
        $password       = $this->request->getPost('password');
        $designationId  = $this->request->getPost('designation_id');
        $departmentId   = $this->request->getPost('department_id');
        $countryCode    = $this->request->getPost('country_code');
        $phoneNumber    = $this->request->getPost('phone_number');

        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
       
        $headers = $this->headers;
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $response = $client->post($this->apiBaseUrl . '/register', [
                'headers' => $headers,
                'form_params' => array_filter([
                    'name' => $name,
                    'email' => $email,
                    'password' => $password,
                    'designation_id' => $designationId ?: null,
                    'department_id' => $departmentId ?: null,
                    'country_code' => $countryCode ?: null,
                    'phone_number' => $phoneNumber ?: null,
                ], function ($value) {
                    return $value !== null && $value !== '';
                })
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'User created successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
    

    public function update($id)
    {
        $name           = $this->request->getPost('name');
        $email          = $this->request->getPost('email');
        $password       = $this->request->getPost('password');
        $designationId  = $this->request->getPost('designation_id');
        $departmentId   = $this->request->getPost('department_id');
        $countryCode    = $this->request->getPost('country_code');
        $phoneNumber    = $this->request->getPost('phone_number');

        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
       
        $headers = $this->headers;
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $formParams = array_filter([
                'name' => $name,
                'email' => $email,
                'designation_id' => $designationId ?: null,
                'department_id' => $departmentId ?: null,
                'country_code' => $countryCode ?: null,
                'phone_number' => $phoneNumber ?: null,
            ], function ($value) {
                return $value !== null && $value !== '';
            });

            if ($password !== null && $password !== '') {
                $formParams['password'] = $password;
            }

            $headersWithJson = $headers;
            $headersWithJson['Content-Type'] = 'application/json';

            $response = $client->put($this->apiBaseUrl . '/update/' . $id, [
                'headers' => $headersWithJson,
                'json' => $formParams
            ]);

            $result = json_decode($response->getBody(), true);
            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'User updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function delete($id)
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
       
        $headers = $this->headers;
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $response = $client->delete($this->apiBaseUrl . '/delete/' . $id, [
                'headers' => $headers
            ]);

            $result = json_decode($response->getBody(), true);

            return $this->response->setJSON($result ?: [
                'success' => true,
                'message' => 'User deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    private function fetchDesignationsList(): array
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');

        $headers = $this->headers;
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $response = $client->get('http://localhost:3000/api/designation/list', [
                'headers' => $headers
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

    private function fetchDepartmentsList(): array
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');

        $headers = $this->headers;
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $response = $client->get('http://localhost:3000/api/department/list', [
                'headers' => $headers
            ]);

            $result = json_decode($response->getBody(), true);
            return $result['data'] ?? [];
        } catch (\Exception $e) {
            return [];
        }
    }

}