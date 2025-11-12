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
      
        $response = $client->get($this->apiBaseUrl . '/getAllAdmins', [
            'headers' => $headers,
        ]);
    
        $result = json_decode($response->getBody(), true);        $users = $result['data'] ?? [];
        return view('frontend/user/users-index', compact('users'));
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
    
            // 🔹 Ensure consistent keys for frontend
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
        ], fn($v) => $v !== null && $v !== '');

        if (!empty($password)) {
            $formParams['password'] = $password;
        }

        $headers['Content-Type'] = 'application/json';

        $response = $client->put($this->apiBaseUrl . '/update/' . $id, [
            'headers' => $headers,
            'json' => $formParams
        ]);

        $result = json_decode($response->getBody(), true);

        // ✅ Ensure consistent structure
        if (!isset($result['success'])) {
            $result['success'] = true;
        }
        if (!isset($result['message'])) {
            $result['message'] = 'User updated successfully';
        }

        return $this->response->setJSON($result);

    } catch (\Exception $e) {
        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => $e->getMessage()
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

        // Ensure consistent structure for frontend
        if (!isset($result['success'])) {
            $result['success'] = true;
        }
        if (!isset($result['message'])) {
            $result['message'] = 'User deleted successfully';
        }

        return $this->response->setJSON($result);

    } catch (\Exception $e) {
        return $this->response->setStatusCode(500)->setJSON([
            'success' => false,
            'message' => $e->getMessage() // use message, not error, for consistency
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
   
    public function editRecord()
    {
        $encryptedId = $this->request->getPost('id');
        if (!$encryptedId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'User ID is required'
            ]);
        }
    
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;
    
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
    
        try {
            $apiUrl = $this->apiBaseUrl . '/' . $encryptedId;
            $response = $client->get($apiUrl, ['headers' => $headers]);
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
    
        // Fetch department & designation lists (plain IDs are fine here)
        $departments  = $this->fetchDepartmentsList();
        $designations = $this->fetchDesignationsList();
    
        return view('frontend/user/edit-form', [
            'user' => $user,
            'departments' => $departments,
            'designations' => $designations
        ]);
    }
    

}