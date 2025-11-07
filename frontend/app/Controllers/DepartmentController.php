<?php

namespace App\Controllers;

use CodeIgniter\Controller;
class DepartmentController extends Controller
{
    private $apiBaseUrl;
    private $headers;

    public function __construct()
    {
        helper(['url', 'session']);
        $this->apiBaseUrl = env('API_BASE_URL') . '/department';

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
    
        $response = $client->get('http://localhost:3000/api/department/list', [
            'headers' => $headers,
        ]);
    
        $result = json_decode($response->getBody(), true);
        $departments = $result['data'] ?? []; // ✅ get only the data list
    
        return view('frontend/department-index', compact('departments'));
    }
    

    public function store()
    {
        $name = $this->request->getPost('name');
        
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
       
        $headers = $this->headers;
        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $response = $client->post($this->apiBaseUrl . '/create', [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                ]
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Department created successfully'
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
        $name = $this->request->getPost('name');
        $status = $this->request->getPost('status');

        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $client->put($this->apiBaseUrl . '/update/' . $id, [
                'headers' => $headers,
                'form_params' => [
                    'name' => $name,
                    'status' => $status
                ]
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Department updated successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    // DELETE Department
    public function delete($id)
    {
        $client = \Config\Services::curlrequest();
        $token = session()->get('admin_token');
        $headers = $this->headers;

        if ($token) {
            $headers['Authorization'] = 'Bearer ' . $token;
        }

        try {
            $client->delete($this->apiBaseUrl . '/delete/' . $id, [
                'headers' => $headers
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Department deleted successfully'
            ]);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}
