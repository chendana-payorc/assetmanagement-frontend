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
    public function editRecord()
    {
        // Step 1️⃣: Get the ID from POST
        $encryptedId = $this->request->getPost('id');
        if (!$encryptedId) {
            return $this->response->setStatusCode(400)->setJSON([
                'success' => false,
                'error' => 'User ID is required'
            ]);
        }
    
        // Step 2️⃣: Setup cURL client and headers
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;
    
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }
    
        // Step 3️⃣: Decrypt the ID first 🔓
        try {
            $decryptResp = $client->post('http://localhost:3000/api/decrypt', [
                'headers' => $headers,
                'json' => ['id' => $encryptedId],
                'timeout' => 5
            ]);
    
            $decResult = json_decode($decryptResp->getBody(), true);
            $decryptedId = $decResult['data']['id'] ?? null;
    
            if (!$decryptedId) {
                return $this->response->setStatusCode(400)->setJSON([
                    'success' => false,
                    'error' => 'Failed to decrypt ID before fetching user'
                ]);
            }
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => 'Decrypt API failed: ' . $e->getMessage()
            ]);
        }
    
        // Step 4️⃣: Now fetch user with decrypted ID
        try {
            $apiUrl = $this->apiBaseUrl . '/' . $decryptedId;
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
    
        // Step 5️⃣: Decrypt department/designation IDs (optional)
        try {
            $decryptResponse = $client->post('http://localhost:3000/api/decrypt', [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => [
                    'designation_id' => $user['designation_id'] ?? '',
                    'department_id'  => $user['department_id'] ?? '',
                ],
                'timeout' => 5
            ]);
    
            $decResult = json_decode($decryptResponse->getBody(), true);
    
            if (!empty($decResult['success']) && !empty($decResult['data'])) {
                $user['designation_id'] = $decResult['data']['designation_id'] ?? $user['designation_id'];
                $user['department_id']  = $decResult['data']['department_id'] ?? $user['department_id'];
            }
        } catch (\Exception $e) {
            $user['designation_id'] = $user['designation_id'] ?? '';
            $user['department_id']  = $user['department_id'] ?? '';
        }
    
        // Step 6️⃣: Fetch dropdown lists
        $departments  = $this->fetchDepartmentsList();
        $designations = $this->fetchDesignationsList();
    
        // Step 7️⃣: Load the edit form view
        return view('frontend/user/edit-form', [
            'user' => $user,
            'departments' => $departments,
            'designations' => $designations
        ]);
    }
    
    

}